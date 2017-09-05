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
        
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $session = $this->get('session');
            $session->set('nbTicket', $order->getNbTicket());
            $session->set('nameOrder', $order->getName());
            $session->set('mailOrder', $order->getMail());
            $session->set('typeTicket', $order->getType());
            
            return $this->redirectToRoute('a_studio_core_homepage');
        }
        return $this->render('AStudioBookingBundle:Order:index.html.twig', array('form' => $form->createView()));
    }
    
    public function infosAction(Request $request)
    {
        $ticket = new Ticket();
        $form = $this->get('form.factory')->create(TicketType::class, $ticket);
        
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            
        }
        return $this->render('AStudioBookingBundle:Order:infos.html.twig', array('form' => $form->createView()));
    }
}
