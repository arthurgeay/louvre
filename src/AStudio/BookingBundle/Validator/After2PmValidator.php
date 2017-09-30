<?php
namespace AStudio\BookingBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class After2PmValidator extends ConstraintValidator
{
  
  public function validate($value, Constraint $constraint)
  {
    
   	$dateForm = $value->format('Y-m-d'); // Date passée
    $dateAct = new \DateTime(); // Date actuelle
    $dateActFormat = $dateAct->format('Y-m-d'); // Date actuelle formatée

    $values = $this->context->getRoot()->getData(); // Values form
    $type = $values->getType();
    
        
    if($dateActFormat == $dateForm && $type == 'journee')
    {
      $dateFormat = $dateAct->format('H:m:s'); // Date actuelle formatée 
      $limit = new \DateTime('14:00:00'); // Limite
      $limitFormat = $limit->format('H:m:s');
            
      if($dateFormat >= $limitFormat)
      {
        $this->context->addViolation($constraint->message);
      }
    }
      
  }
}