<?php

namespace AStudio\BookingBundle\Calculator;

class Calculator
{
	public function prices($session)
	{
		$tickets = $session->get('tickets');
        $type = $session->get('typeTicket');

        $dateAct = new \Datetime(); // On créer une date actuelle 
        $year = $dateAct->format('Y');

        
        foreach($tickets->toArray() as $ticket) // Boucle qui récupère chaque ticket 
        {
            $birthdate = $ticket->getBirthdate();
            $birthdateFormat = $birthdate->format('Y');
            $reduced = $ticket->getReducedPrice();

            if($type == 'journee')
            {
                if(($year - $birthdateFormat) < 4) // Gratuit
                {
                    $prices[] = '0';
                }
                else if($reduced == true) // Réduit
                {
                    $prices[] = '10';
                }
                else if (($year - $birthdateFormat) >= 60) // Sénior
                {
                    $prices[] = '12';
                }
                else if (($year - $birthdateFormat) > 12) // Normal
                {
                    $prices[] = '16';
                }
                else if (($year - $birthdateFormat) >= 4 && ($year - $birthdateFormat) <= 12) // Enfant
                {
                    $prices[] = '8';
                }
            }
            else if($type == 'demijour')
            {
                if(($year - $birthdateFormat) < 4)
                {
                    $prices[] = '0';
                }
                else if($reduced == true)
                {
                    $prices[] = '5';
                }
                else if (($year - $birthdateFormat) >= 60)
                {
                    $prices[] = '6';
                }
                else if (($year - $birthdateFormat) > 12)
                {
                    $prices[] = '8';
                }
                else if (($year - $birthdateFormat) >= 4 && ($year - $birthdateFormat) <= 12)
                {
                    $prices[] = '4';
                }
            }
        }

        return $prices;
	}


    public function total($prices)
    {
        $price = array_sum($prices);
        return $price;
    }
}