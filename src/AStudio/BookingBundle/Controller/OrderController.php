<?php

namespace AStudio\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AStudio\BookingBundle\Entity\Order;
use AStudio\BookingBundle\Entity\Ticket;
use AStudio\BookingBundle\Form\OrderType;

class OrderController extends Controller
{
    public function indexAction(Request $request)
    {
        $order = new Order();
        $form = $this->get('form.factory')->create(OrderType::class, $order);
        $form->remove('tickets');
        
        
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $session = $this->get('session'); // On stocke en session les infos de la commande
            $session->set('nbTicket', $order->getNbTicket());
            $session->set('nameOrder', $order->getName());
            $session->set('mailOrder', $order->getMail());
            $session->set('typeTicket', $order->getType());
            $session->set('date', $order->getDateVisit());
            
            return $this->redirectToRoute('a_studio_booking_informations');
        }
        
        return $this->render('AStudioBookingBundle:Order:index.html.twig', array('form' => $form->createView()));
    }
    
    public function infosAction(Request $request)
    {
        $session = $this->get('session');

        // Si les variables de session n'existent pas
        if(!$session->has('nbTicket'))
        {
            $session->clear();
            return $this->redirectToRoute('a_studio_booking_homepage'); // On redirige sur la 1ère étape
        }

        $order = new Order();
        // Hydratation avec les infos de la commande
        $order->setName($session->get('nameOrder'))
              ->setNbTicket($session->get('nbTicket'))
              ->setMail($session->get('mailOrder'))
              ->setType($session->get('typeTicket'))
              ->setDateVisit($session->get('date'));

        $form = $this->get('form.factory')->create(OrderType::class, $order);
        // Supression des champs inutiles et déjà renseignés
        $form->remove('nbTicket')
             ->remove('dateVisit')
             ->remove('type')
             ->remove('mail')
             ->remove('name');

        // Nombre de ticket
        $nbTickets = $session->get('nbTicket');
        
        
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $session = $this->get('session');
            $session->set('tickets', $order->getTickets());

            return $this->redirectToRoute('a_studio_booking_summary');
        }
        
        return $this->render('AStudioBookingBundle:Order:infos.html.twig', array('form' => $form->createView(), 'nbTickets' => $nbTickets));
    }

    public function summaryAction(Request $request)
    {
        $session = $this->get('session');

        // Si les variables de session n'existent pas
        if(!$session->has('tickets'))
        {
            $session->clear();
            return $this->redirectToRoute('a_studio_booking_homepage'); // On redirige sur la 1ère étape
        }


        $tickets = $session->get('tickets');

        $calculator = $this->container->get('a_studio_booking.calculator'); // Appel du service de calcul
        $prices = $calculator->prices($session); // Prix par billet
        $total = $calculator->total($prices); // Calcul du total des billets


        $error = false;
        if($request->isMethod('POST'))
        {
            try{
                $token = $request->request->get('stripeToken');
                \Stripe\Stripe::setApiKey($this->getParameter('stripe_secret_key'));
                \Stripe\Charge::create(array(
                    "amount" => $total * 100,
                    "currency" => "eur",
                    "source" => $token,
                    "description" => "Musée du Louvre"
                ));

            } catch(\Stripe\Error\Card $e) {
                $error = 'Il y a un problème avec votre carte bancaire : Veuillez réessayer !';
            }

            if(!$error)
            {
                $order = new Order(); // Hydratation de l'objet Order
                $order->setNbTicket($session->get('nbTicket'))
                      ->setName($session->get('nameOrder'))
                      ->setMail($session->get('mailOrder'))
                      ->setType($session->get('typeTicket'))
                      ->setDateVisit($session->get('date'));

                foreach($tickets->toArray() as $ticket) // Hydratation de Ticket
                {
                    $ticketsF = new Ticket();
                    $ticketsF->setLastName($ticket->getLastName())
                            ->setFirstname($ticket->getFirstname())
                            ->setReducedprice($ticket->getReducedprice())
                            ->setBirthdate($ticket->getBirthdate());

                    $order->addTicket($ticketsF);
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($order); 
                $em->flush(); // On enregistre la commande et les billets en BDD


                $this->get('a_studio_core.email')->sendTicket($session, $tickets, $total); // Service d'envoi du ticket par mail
                 
                
                $session->set('total', $total); // Enregistrement en session

                return $this->redirectToRoute('a_studio_booking_confirm');
            }
        }

        return $this->render('AStudioBookingBundle:Order:summary.html.twig', array(
         'tickets' => $tickets,
         'prices' => $prices,
         'total' => $total,
         'stripe_public_key' => $this->getParameter('stripe_public_key'),
         'error' => $error
         ));
    }

    public function confirmAction()
    {
        $session = $this->get('session');

        if(!$session->has('nbTicket') || !$session->has('tickets') || !$session->has('total'))
        {
            $session->clear();
            return $this->redirectToRoute('a_studio_booking_homepage');
        }

        $session->clear(); // On supprime les variables de session

        return $this->render('AStudioBookingBundle:Order:confirm.html.twig');
    }
}
