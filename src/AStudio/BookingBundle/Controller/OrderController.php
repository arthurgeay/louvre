<?php

namespace AStudio\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrderController extends Controller
{
    public function indexAction()
    {
        return $this->render('AStudioBookingBundle:Order:index.html.twig');
    }
}
