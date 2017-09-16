<?php
namespace AStudio\BookingBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PastDaysValidator extends ConstraintValidator
{
  public function validate($value, Constraint $constraint)
  {
    
   	$datef = $value->format('Y-m-d');
    $dateAct = new \DateTime();
    $dateformat = $dateAct->format("Y-m-d");
    
    if($datef < $dateformat)
    {
      $this->context->addViolation($constraint->message);
    }
      
  }
}