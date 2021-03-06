<?php

namespace AStudio\BookingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AStudio\BookingBundle\Form\TicketType;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class OrderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nbTicket', IntegerType::class)
                ->add('dateVisit', DateType::class, array(
                    'widget' => 'single_text',
                    'attr' => ['class' => 'js-datepicker form-control'],
                    'html5' => false))
                ->add('type', ChoiceType::class, array(
                'choices' => array(
                    'Choisir un type de billet...' => null,
                    'Journée' => 'journee',
                    'Demi-journée' => 'demijour'),
                    'choice_translation_domain' => true ))
                ->add('name', TextType::class)
                ->add('mail', EmailType::class)
                ->add('tickets', CollectionType::class, [
                    'label' => false,
                    'entry_type' => TicketType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                ])
                ;

    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AStudio\BookingBundle\Entity\Order'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'astudio_bookingbundle_order';
    }


}
