<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new Email([
                        'message' => 'L\'email n\'est pas valide',
                        'mode' => 'html5',
                    ]),
                    new NotBlank([
                        'message' => 'Champs vide'
                    ]),

                ],
            ])

            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'required' => true,
                'label' => 'Envoyer un message',
                'constraints' =>  [
                    new Length([
                        'min' => 25,
                        'max' => 2000,
                        'minMessage' => "{{ limit }} caractères minimum",
                        'maxMessage' => "{{ limit }} caractères maximum",
                        'allowEmptyString' => false,
                    ]),
                    new NotBlank([
                        'message' => 'Champs vide'
                    ]),


                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
