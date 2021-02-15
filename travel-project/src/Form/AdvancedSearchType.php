<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class AdvancedSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder/* ->setMethod('GET') */
            ->add('search', SearchType::class, [
                'label' => 'Pays',
            ])
            ->add('startDate', DateType::class, [
                'label' => 'Date de début prévue',
                'years' => range(date('Y'), date('Y') + 5),
                'constraints' => new GreaterThan('today'),
                'widget' => 'single_text'
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Date de fin prévue',
                'widget' => 'single_text',
                //https://stackoverflow.com/questions/44983045/date-after-another-date-in-symfony-form
                'constraints' => [
                    new Callback(function ($object, ExecutionContextInterface $context) {
                        $start = $context->getRoot()->getData()['startDate'];
                        $stop = $object;

                        if (is_a($start, \DateTime::class) && is_a($stop, \DateTime::class)) {
                            if ($stop->format('U') - $start->format('U') < 0) {
                                $context
                                    ->buildViolation('Stop must be after start')
                                    ->addViolation();
                            }
                        }
                    }),
                ]
            ])
            ->add('language', ChoiceType::class, [
                'label' => 'Langues parlées',
                'choices' => [
                    'Francophone' => 1,
                    'Non francophone' => 2,
                ],
                'multiple' => false
            ])

            ->add('temperature', ChoiceType::class, [
                'label' => 'Temperature',
                'choices' => [
                    'Climat froid' => 3,
                    'Climat modéré' => 4,
                    'Climat chaud' => 5,
                ],
                'multiple' => false
            ])
            ->add('cost', ChoiceType::class, [
                'label' => 'Coût de la vie',
                'choices' => [
                    'Pas chère' => 6,
                    'Chère' => 7,
                ],
                'multiple' => false
            ])

            ->add('area', ChoiceType::class, [
                'label' => 'Aire',
                'choices' => [
                    'Aire urbaine' => 8,
                    'Campagne' => 9,
                ],
                'multiple' => false
            ])
            ->add('landscape', ChoiceType::class, [
                'label' => 'Paysage',
                'choices' => [
                    'Montagne' => 10,
                    'Mer' => 11,
                    'Cascade' => 12,
                    'Forêt' => 13,
                ],
                'expanded' => true,
                'multiple' => true
            ])

            ->add('sportingActivities', ChoiceType::class, [
                'label' => 'Activités sportives',
                'choices' => [
                    'Ski' => 14,
                    'Surf' => 15,
                    'Plongée' => 16,
                    'Randonnée' => 25,
                    'Escalade' => 17,
                ],
                'expanded' => true,
                'multiple' => true,
            ])

            ->add('placesToVisit', ChoiceType::class, [
                'label' => 'Lieux à visiter',
                'choices' => [
                    'Monument' => 18,
                    'Site archéologique' => 19,
                    'Aquarium' => 20,
                    'Musée' => 21,
                    'Parc d\'attraction' => 22,
                    'Grotte' => 23,
                    'Château' => 24,
                ],
                'expanded' => true,
                'multiple' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => City::class,
        ]);
    }
}
