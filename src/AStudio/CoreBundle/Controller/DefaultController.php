<?php

namespace AStudio\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AStudioCoreBundle:Default:index.html.twig');
    }
}
