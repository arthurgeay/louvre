<?php

namespace AStudio\BookingBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PastDays extends Constraint
{
  public $message = "Vous ne pouvez pas réserver pour un jour passé.";

  public function validateBy()
  {
  	return 'astudio_booking_pastdays';
  }
}