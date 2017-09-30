<?php

namespace AStudio\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CoreController extends Controller
{
    public function indexAction()
    {
        return $this->render('AStudioCoreBundle:Home:index.html.twig');
    }
    
    public function contactAction()
    {
        return $this->render('AStudioCoreBundle:Contact:contact.html.twig');
    }
}
