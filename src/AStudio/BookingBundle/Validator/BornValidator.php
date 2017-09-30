<?php
namespace AStudio\BookingBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BornValidator extends ConstraintValidator
{
  
  public function validate($value, Constraint $constraint)
  {
    $dateAct = new \Datetime();

    if ($value > $dateAct) { 
      $this->context->addViolation($constraint->message);
    }   
  }
}