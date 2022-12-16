<?php

namespace App\Controller\Admin;

use App\Entity\Nationalteam;
use App\Form\NationalTeamType;
use App\Repository\NationalteamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\ByteString;

#[Route('/admin')]
class NationalTeamController extends AbstractController
{
    public function __construct(private NationalteamRepository $nationalteamRepository, private RequestStack $requestStack, private EntityManagerInterface $entityManager)
    {

    }

    #[Route('/admin', name:'admin.nationalteam.index')]
    public function index():Response
    {
        return $this->render('admin/nationalteam/index.html.twig', [
            'entities' => $this->nationalteamRepository->findAll(),
        ]);
    }


    #[Route('/admin/nationalteam/form/add', name:'admin.nationalteam.form.add')]
    #[Route('/admin/nationalteam/form/edit{id}', name:'admin.nationalteam.form.edit')]
    public function form(int $id = null):Response
    {
        $type = NationalTeamType::class;

        //si l'id est nul, un joueur est en phase de creation
        //si l'id n'est pas nul, un joueur est en phase de modification

        $model = $id ? $this->nationalteamRepository->find($id) : new Nationalteam();

        //sauvegarder le nom de l'usage au cas ou aucune image n'etait selectionnéedans le formulaire
        $model->prevImage = $id ? $model->getFlag() : null;
        //dd($model);

        $form = $this->createForm($type, $model);
        $form->handleRequest($this->requestStack->getCurrentRequest());

        if($form->isSubmitted() && $form->isValid()){
            //dd($model);
            //dd($form['portrait']->getData());
            //si une image est selectionnée
            if($form['flag']->getData() instanceof UploadedFile)
            {
                $file = $form['flag']->getData();// objet de uploadedfile

                //generer un nome aleotoire
                $randomName = ByteString::fromRandom(32)->lower();
                $fileExtension  = $file->guessClientExtension();
                $fullFileName = "$randomName.$fileExtension";

                //deplacer le fichier
                $file->move('img/flags',$fullFileName);

                //affecter le nom aleotoire a la propriété de l'amitié
                $model->setFlag($fullFileName);

                //dd($randomName, $fileExtension, $fullFileName);

                //supprimer ancienne  image
                if($id) unlink("img/flags/{$model->prevImage}");

            }
            //si aucune image n'est selectionnée
            else{
                $model->setFlag($model->prevImage);
            }


            //acceder a la base de donnes
            $this->entityManager->persist($model);
            $this->entityManager->flush();

            //creation d un message flush
            $message = $id ? 'National Team Has been updated' : 'National Team  Has been added';
            $this->addFlash('notice',$message);

            //redirection
            return $this->redirectToRoute('admin.nationalteam.index');
        }

        return $this->render('admin/nationalteam/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}