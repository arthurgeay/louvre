<?php
namespace AStudio\BookingBundle\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VerifTicketValidator extends ConstraintValidator
{
  private $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }
  
  public function validate($order, Constraint $constraint)
  {

  $date = $order->getDateVisit(); // On récupère les infos du formulaire (date et nb de ticket)
  $nbTicket = $order->getNbTicket();

  $result = $this                 // On vérifie en BDD combien de ticket ont déjà été reservé pour la date demandé
                  ->em
                  ->getRepository('AStudioBookingBundle:Order') 
                  ->countTickets($date)
                  ;

   	if($result + $nbTicket > 1000) // Si le nb de ticket déjà réservé + le nb de ticket demandé est supérieur à 1000
    {
      $this->context->addViolation($constraint->message);
    } 
  } 
}