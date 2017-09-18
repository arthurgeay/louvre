<?php

namespace AStudio\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AStudio\BookingBundle\Entity\Order;
use AStudio\BookingBundle\Form\OrderType;

class OrderController extends Controller
{
    public function indexAction(Request $request)
    {
        $order = new Order();
        $form = $this->get('form.factory')->create(OrderType::class, $order);
        $form->remove('tickets');
        
        // PENSER A LA CONTRAINTE DES 1000 billets sur le champ date
        
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $session = $this->get('session');
            $session->set('nbTicket', $order->getNbTicket());
            $session->set('nameOrder', $order->getName());
            $session->set('mailOrder', $order->getMail());
            $session->set('typeTicket', $order->getType());
            $session->set('date', $order->getDateVisit());
            
            return $this->redirectToRoute('a_studio_booking_informations');
        }
        
        return $this->render('AStudioBookingBundle:Order:index.html.twig', array('form' => $form->createView()));
    }
    
    public function infosAction(Request $request)
    {
        $session = $this->get('session');

        $order = new Order();
        // Hydratation avec les infos de la commande
        $order->setName($session->get('nameOrder'))
              ->setNbTicket($session->get('nbTicket'))
              ->setMail($session->get('mailOrder'))
              ->setType($session->get('typeTicket'))
              ->setDateVisit($session->get('date'));

        $form = $this->get('form.factory')->create(OrderType::class, $order);
        // Supression des champs inutiles et déjà renseignés
        $form->remove('nbTicket')
             ->remove('dateVisit')
             ->remove('type')
             ->remove('mail')
             ->remove('name');

        // Nombre de ticket
        $nbTickets = $session->get('nbTicket');
        
        
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $session = $this->get('session');
            $session->set('tickets', $order->getTickets());

            return $this->redirectToRoute('a_studio_booking_summary');
        }
        
        return $this->render('AStudioBookingBundle:Order:infos.html.twig', array('form' => $form->createView(), 'nbTickets' => $nbTickets));
    }

    public function summaryAction()
    {
        $session = $this->get('session');
        $tickets = $session->get('tickets');
        $type = $session->get('typeTicket');

        $dateAct = new \Datetime();
        $year = $dateAct->format('Y');

        
        foreach($tickets->toArray() as $ticket)
        {
            $birthdate = $ticket->getBirthdate();
            $birthdateFormat = $birthdate->format('Y');
            $reduced = $ticket->getReducedPrice();

            if($type == 'journee')
            {
                if(($year - $birthdateFormat) < 4)
                {
                    $prices[] = '0';
                }
                else if($reduced == true)
                {
                    $prices[] = '10';
                }
                else if (($year - $birthdateFormat) >= 60)
                {
                    $prices[] = '12';
                }
                else if (($year - $birthdateFormat) > 12)
                {
                    $prices[] = '16';
                }
                else if (($year - $birthdateFormat) >= 4 && ($year - $birthdateFormat) <= 12)
                {
                    $prices[] = '8';
                }
            }
        }

        var_dump($prices);

        return $this->render('AStudioBookingBundle:Order:summary.html.twig', array('tickets' => $tickets, 'type' => $type, 'prices' => $prices));
    }
}
