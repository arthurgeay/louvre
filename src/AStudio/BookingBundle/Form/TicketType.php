<?php

namespace AStudio\BookingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lastname', TextType::class, array(
                'label' => 'Nom',
                'attr' => ['class' => 'col-sm-5']))
                ->add('firstname', TextType::class, array(
                'label' => 'Prénom',
                'attr' => ['class' => 'col-sm-5']))
                ->add('birthdate', DateType::class, array(
                    'label' => 'Date de naissance',
                    'widget' => 'single_text',
                    'attr' => ['class' => 'col-sm-5 js-datepicker'],
                    'html5' => false))
                ->add('reducedprice', CheckboxType::class, array(
                    'required' => false,
                    'label' => 'Tarif réduit'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AStudio\BookingBundle\Entity\Ticket'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'astudio_bookingbundle_ticket';
    }


}
