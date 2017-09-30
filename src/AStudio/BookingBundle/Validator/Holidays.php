<?php

namespace AStudio\BookingBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Holidays extends Constraint
{
  public $message = "Le musée est fermé pendant les jours fériés.";

  public function validateBy()
  {
  	return 'astudio_booking_holidays';
  }
}