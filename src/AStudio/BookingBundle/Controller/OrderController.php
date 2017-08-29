<?php

namespace AStudio\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function indexAction()
    {
        $content = $this->get('templating')->render('AStudioBookingBundle:Order:index.html.twig');
        
        return new Response($content);
    }
}
