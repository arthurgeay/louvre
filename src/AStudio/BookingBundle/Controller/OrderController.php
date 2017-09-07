<?php

namespace AStudio\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AStudio\BookingBundle\Entity\Order;
use AStudio\BookingBundle\Form\OrderType;
use AStudio\BookingBundle\Entity\Ticket;
use AStudio\BookingBundle\Form\TicketType;

class OrderController extends Controller
{
    public function indexAction(Request $request)
    {
        $order = new Order();
        $form = $this->get('form.factory')->create(OrderType::class, $order);
        
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
        $ticket = new Ticket();
        $form = $this->get('form.factory')->create(TicketType::class, $ticket);
        
        // Get numbers of ticket
        $session = $this->get('session');
        $nbTickets = $session->get('nbTicket');
        
        
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            
        }
        
        return $this->render('AStudioBookingBundle:Order:infos.html.twig', array('form' => $form->createView(), 'nbTickets' => $nbTickets));
    }
}
