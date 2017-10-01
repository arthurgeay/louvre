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
    		$message = (new \Swift_Message('Contact - Musée du Louvre'))
                    ->setFrom($contact->getEmail())
                    ->setTo('arthurgeay.contact@gmail.com')
                    ->setBody($this->renderView('Emails/mail_contact.html.twig',
                        array('lastname' => $contact->getLastname(),
                        	  'firstname' => $contact->getFirstname(),
                        	  'email' => $contact->getEmail(),
                        	  'message' => $contact->getMessage())),'text/html');

                $this->get('mailer')->send($message);

               	$request->getSession()->getFlashBag()->add('success', 'Votre message a bien été envoyé !');

               	return $this->redirectToRoute('a_studio_core_contact');
    	}

        return $this->render('AStudioCoreBundle:Contact:contact.html.twig', array('form' => $form->createView()));
    }
}
