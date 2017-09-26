<?php

namespace AStudio\BookingBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Born extends Constraint
{
  public $message = "Cette date de naissance n'est pas valide";

  public function validateBy()
  {
  	return 'astudio_booking_born';
  }
}