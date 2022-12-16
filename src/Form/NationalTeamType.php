<?php

namespace App\Form;

use App\Entity\Nationalteam;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class NationalTeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Country name is required',
                    ]),
                    new Length([
                        'min'=>2,
                        'minMessage'=>'Country Name is too short'
                    ])
                ],
            ])

            ->add('flag', FileType::class, [
                'data_class' => null,
                'constraints' => $options['data']->getID() ? [] : [
                    new NotBlank([
                        'message' => 'flag is required',
                    ]),
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Nationalteam::class
        ]);
    }
}
