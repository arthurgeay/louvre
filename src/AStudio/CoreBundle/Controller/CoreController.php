<?php

namespace AStudio\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AStudio\CoreBundle\Entity\Contact;
use AStudio\CoreBundle\Form\ContactType;

class CoreController extends Controller
{
    public function indexAction()
    {
        return $this->render('AStudioCoreBundle:Home:index.html.twig');
    }
    
    public function contactAction(Request $request)
    {
    	$contact = new Contact();
    	$form = $this->get('form.factory')->create(ContactType::class, $contact);

    	if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
    	{
    		$this->get('a_studio_core.email')->sendMessage($contact); // Envoi de mail

            $request->getSession()->getFlashBag()->add('success', 'Votre message a bien été envoyé !');

            return $this->redirectToRoute('a_studio_core_contact');
    	}

        return $this->render('AStudioCoreBundle:Contact:contact.html.twig', array('form' => $form->createView()));
    }
}
