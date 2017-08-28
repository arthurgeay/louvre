<?php

namespace AStudio\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AStudioBookingBundle:Default:index.html.twig');
    }
}
