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
        //$order->setNbTicket(1);
        $form = $this->get('form.factory')->create(OrderType::class, $order);
        
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();
            
            return $this->redirectToRoute('a_studio_core_homepage');
        }
        return $this->render('AStudioBookingBundle:Order:index.html.twig', array('form' => $form->createView()));
    }
}
