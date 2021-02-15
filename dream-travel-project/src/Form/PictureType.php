<?php

namespace App\Form;

use App\Entity\Picture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add(
                'image',
                CollectionType::class,
                [
                    'entry_type' => PictureType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'label' => 'Image',
                    'by_reference' => false,
                    'mapped' => false,
                    'required' => false,
                ]
            )
            ->add('saveAndAdd', SubmitType::class, [
                'label' => 'Save and Add'
            ])
            /*   ->add('filename')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('review') */;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Picture::class,
        ]);
    }
}
