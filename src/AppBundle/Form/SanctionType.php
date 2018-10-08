<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DateTime;

class SanctionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('reason', EntityType::class, [
                'class' => 'AppBundle\Entity\SanctionReason',
                'required' => true,
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'select a reason...'
            ])
            ->add('details', TextareaType::class, [
                'attr' => ['class' => 'form-control',
                    'placeholder' => 'details...',
                    'maxlength' => 255,
                    'rows' => 5],
                'label' => ''
            ]) //TODO CG limite nb char
            ->add('done', ChoiceType::class, [
                'label' => 'remind me ? ',
                'choices' => [
                    'yes' => false,
                    'no' => true
                ],
                'expanded' =>true,
                'multiple' => false
            ])
            ->add('student', EntityType::class, [
                'class' => 'AppBundle\Entity\Student',
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success'],
                'label' => 'Save'
            ]);
    }

    /**
    * {@inheritdoc}
    */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Sanction',
            'class' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_sanction';
    }

}
