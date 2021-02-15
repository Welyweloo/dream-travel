<?php

namespace App\Form;

use App\Entity\Review;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Image;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rate', ChoiceType::class, [
                'label' => 'Note de la destination',
                'mapped' => false,
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
                'data' => 5,
                'expanded' => true,
                //'required' => true,
            ])
            ->add('travelDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date du voyage',
                'required' => false,
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Donner votre avis sur la destination',
                'required' => false,
                'row_attr' => ['rows' => 4],
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre de la photo',
                'mapped' => false,
                'required' => false,
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Photo',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '1024k',
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Review::class,

        ]);
    }
}
