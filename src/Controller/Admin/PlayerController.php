<?php

namespace App\Controller\Admin;

use App\Entity\Player;
use App\Form\PlayerType;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\ByteString;

#[Route('/admin')]
class PlayerController extends AbstractController
{
    public function __construct(private PlayerRepository $playerRepository, private RequestStack $requestStack, private EntityManagerInterface $entityManager)
        {

        }
    #[Route('/player', name:'admin.player.index')]
    public function index():Response
    {
        return $this->render('admin/player/index.html.twig', [
            'entities' => $this->playerRepository->findAll(),
        ]);
    }

    #[Route('/player/form/add', name:'admin.player.form.add')]
    #[Route('/player/form/edit{id}', name:'admin.player.form.edit')]
    public function form(int $id = null):Response
    {
        $type = PlayerType::class;

        //si l'id est nul, un joueur est en phase de creation
        //si l'id n'est pas nul, un joueur est en phase de modification

        $model = $id ? $this->playerRepository->find($id) : new Player();

        //sauvegarder le nom de l'usage au cas ou aucune image n'etait selectionnéedans le formulaire
        $model->prevImage = $id ? $model->getPortrait() : null;
        //dd($model);

        $form = $this->createForm($type, $model);
        $form->handleRequest($this->requestStack->getCurrentRequest());

        if($form->isSubmitted() && $form->isValid()){
            //dd($model);
            //dd($form['portrait']->getData());
            //si une image est selectionnée
            if($form['portrait']->getData() instanceof UploadedFile)
                {
                    $file = $form['portrait']->getData();// objet de uploadedfile

                    //generer un nome aleotoire
                    $randomName = ByteString::fromRandom(32)->lower();
                    $fileExtension  = $file->guessClientExtension();
                    $fullFileName = "$randomName.$fileExtension";

                    //deplacer le fichier
                    $file->move('img/',$fullFileName);

                    //affecter le nom aleotoire a la propriété de l'amitié
                    $model->setPortrait($fullFileName);

                    //dd($randomName, $fileExtension, $fullFileName);

                    //supprimer ancienne  image
                    if($id) unlink("img/{$model->prevImage}");

                }
            //si aucune image n'est selectionnée
            else{
                $model->setPortrait($model->prevImage);
            }


            //acceder a la base de donnes
            $this->entityManager->persist($model);
            $this->entityManager->flush();

            //creation d un message flush
            $message = $id ? 'Player Has been updated' : 'Player Has been added';
            $this->addFlash('notice',$message);

            //redirection
            return $this->redirectToRoute('admin.player.index');
        }

        return $this->render('admin/player/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/player/form/remove{id}', name:'admin.player.remove')]
    public function remove(int $id) : Response
    {
        //selection de l'entité
        $entity = $this->playerRepository->find($id);

        //suppression de l'entité
        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        //message flush et redirection
        $this->addFlash('notice', 'Player has been removed');
        return $this->redirectToRoute('admin.player.index');
    }



}