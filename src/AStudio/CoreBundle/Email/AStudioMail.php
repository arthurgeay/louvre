<?php

namespace AStudio\CoreBundle\Email;

class AStudioMail
{
	private $mailer;
	private $templating;

	public function __construct(\Swift_Mailer $mailer, $templating)
	{
		$this->mailer = $mailer;
		$this->templating = $templating;

	}

	public function sendTicket($name, $dateVisit, $mailOrder, $tickets, $total)
	{
		$message = (new \Swift_Message('Réservation billets - Musée du Louvre'))
                    ->setFrom('arthurgeay.contact@gmaiL.com')
                    ->setTo($mailOrder)
                    ->setBody($this->templating->render('Emails/ticket.html.twig',
                        array('name' => $name, 'tickets' => $tickets, 'total' => $total, 'date' => $dateVisit)),'text/html');

                $this->mailer->send($message);
	}

	public function sendMessage($contact)
	{
		$message = (new \Swift_Message('Contact - Musée du Louvre'))
                    ->setFrom($contact->getEmail())
                    ->setTo('arthurgeay.contact@gmail.com')
                    ->setBody($this->templating->render('Emails/mail_contact.html.twig',
                        array('lastname' => $contact->getLastname(),
                        	  'firstname' => $contact->getFirstname(),
                        	  'email' => $contact->getEmail(),
                        	  'message' => $contact->getMessage())),'text/html');

        $this->mailer->send($message);
	}
}