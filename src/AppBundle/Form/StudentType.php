<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('surname', TextType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('class', EntityType::class, [
                'class'=>'AppBundle\Entity\Classs',
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('island', EntityType::class, [
                'class'=>'AppBundle\Entity\Island',
                'query_builder' => function(EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('i')
                        ->where('i.class=:class')
                        ->setParameter('class', $options['class']->getId());
                },
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('phoneNumber', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false
            ]);
    }

    /**
    * {@inheritdoc}
    */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Student',
            'class' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_student';
    }

}
