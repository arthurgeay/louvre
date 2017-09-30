<?php

namespace AStudio\BookingBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class After2Pm extends Constraint
{
  public $message = "Vous ne pouvez pas réserver après 14h00 pour ce jour.";

  public function validateBy()
  {
  	return 'astudio_booking_after2pm';
  }
}