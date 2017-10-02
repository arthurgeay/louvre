<?php

namespace AStudio\BookingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lastname', TextType::class, array(
                'label' => false, 
                'attr' => ['placeholder' => 'Nom']))
                ->add('firstname', TextType::class, array(
                'label' => false,
                'attr' => ['placeholder' => 'Prénom']))
                ->add('birthdate', BirthdayType::class, array(
                    'label' => false,
                    'placeholder' => 'La date',
                    'widget' => 'single_text',
                    'attr' => ['class' => 'js-datepicker', 'placeholder' => 'Date de naissance'],
                    'html5' => false))
                ->add('reducedprice', CheckboxType::class, array(
                    'required' => false,
                    'label' => 'Tarif réduit',
                    'label_attr' => ['for' => ''],
                    'attr' => ['data-toggle' => 'popover',
                    'title' => 'Information - Tarif réduit', 
                    'data-content' => 'Il est nécessaire de présenter sa carte d\'étudiant, militaire ou équivalent lors de l\'entrée dans le musée.',
                    'data-placement' => 'top'
                    ]
                    ));
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
