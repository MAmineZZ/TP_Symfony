<?php

namespace App\Form;

use App\Entity\Nationalteam;
use App\Entity\Player;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
//use Symfony\Config\Framework\Validation\NotCompromisedPasswordConfig;

class PlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'First name is required',
                    ]),
                    new Length([
                        'min'=>2,
                        'minMessage'=>'First Name is too short'
                    ])
                ],
            ])

            ->add('lastname', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Last name is required',
                    ]),
                ],
            ])

            ->add('birthday', BirthdayType::class,[
                    'empty_data' => null,
                    'widget' => 'single_text',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Birthday is required',
                        ]),
                    ],
            ])

            ->add('portrait', FileType::class, [
                'data_class' => null,
                'constraints' => $options['data']->getID() ? [] : [
                    new NotBlank([
                        'message' => 'Portrait is required',
                    ]),
                ],
            ])

            ->add('number', IntegerType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Number must be positive',
                    ]),
                ],
            ])

            ->add('team', EntityType::class,[
                'class' => team::class,
                'choice_label' => 'name',
            ])

            ->add('nationalTeam', EntityType::class,[
                'class' => nationalteam::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
        ]);
    }
}
