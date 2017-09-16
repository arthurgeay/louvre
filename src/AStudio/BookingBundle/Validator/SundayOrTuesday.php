<?php

namespace AStudio\BookingBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SundayOrTuesday extends Constraint
{
  public $message = "Le musée est fermé le dimanche et le mardi.";

  public function validateBy()
  {
  	return 'astudio_booking_sundayortuesday';
  }
}