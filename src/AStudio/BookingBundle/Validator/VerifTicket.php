<?php

namespace AStudio\BookingBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class VerifTicket extends Constraint
{
  public $message = "La limite de billet réservé a atteint sa limite ! Veuillez choisir une autre date.";

  public function validateBy()
  {
  	return 'astudio_booking_verifticket';
  }
}