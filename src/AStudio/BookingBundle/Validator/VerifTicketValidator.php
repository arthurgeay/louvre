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
  
  public function validate($value, Constraint $constraint)
  {

  $result = $this
                  ->em
                  ->getRepository('AStudioBookingBundle:Order')
                  ->countTickets($value)
                  ;
   
   	if($result >= 1000) // On prend le résultat + le nombre 
    {
      $this->context->addViolation($constraint->message);
    } 
  }
}