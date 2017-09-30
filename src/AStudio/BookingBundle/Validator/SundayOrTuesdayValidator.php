<?php
namespace AStudio\BookingBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SundayOrTuesdayValidator extends ConstraintValidator
{
  public function validate($value, Constraint $constraint)
  {
    
   	$result = $value->format('Y-m-d');
    $timestamp = strtotime($result);
    $jour = date('w', $timestamp);
        
    // SI mardi ou dimanche
    if($jour == 0 || $jour == 2)
    {
        $this->context->addViolation($constraint->message);
    }
  }
}