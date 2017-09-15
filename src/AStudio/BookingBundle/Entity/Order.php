<?php

namespace AStudio\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Order
 *
 * @ORM\Table(name="`order`")
 * @ORM\Entity(repositoryClass="AStudio\BookingBundle\Repository\OrderRepository")
 */
class Order
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_ticket", type="integer")
     * @Assert\Range(
     * min = 1,
     * minMessage = "Le nombre de billet doit être supérieur ou égal à {limit}",
     * invalidMessage = "{{ value }} n'est pas un nombre."
     * )
     */
    private $nbTicket;
    
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     * @Assert\NotBlank(message = "Ce champ ne doit pas être vide")
     */
    private $type;
    
    /**
    * @var \DateTime
    *
    * @ORM\Column(name="dateOfVisit", type="datetime")
    * @Assert\DateTime(message = "La date n'est pas valide.")
    * @Assert\NotBlank(message = "Ce champ ne doit pas être vide")
    * @Assert\NotNull(message = "Ce champ ne doit pas être nul")
    */
    private $dateVisit;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank(message = "Ce champ ne doit pas être vide")
     * @Assert\Length(max = 30, maxMessage = "Ce champ ne peut contenir plus de 30 caractères")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255)
     * @Assert\Email(
     * message = "L'email {{ value }} n'est pas une adresse email valide",
     * checkMX = true
     * )
     */
    private $mail;
    
    /**
    *
    * @ORM\OneToMany(targetEntity="\AStudio\BookingBundle\Entity\Ticket", cascade="persist", mappedBy="order")
    */
    private $tickets;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nbTicket
     *
     * @param integer $nbTicket
     *
     * @return a_Order
     */
    public function setNbTicket($nbTicket)
    {
        $this->nbTicket = $nbTicket;

        return $this;
    }

    /**
     * Get nbTicket
     *
     * @return int
     */
    public function getNbTicket()
    {
        return $this->nbTicket;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return a_Order
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return a_Order
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Order
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set dateVisit
     *
     * @param \DateTime $dateVisit
     *
     * @return Order
     */
    public function setDateVisit($dateVisit)
    {
        $this->dateVisit = $dateVisit;

        return $this;
    }

    /**
     * Get dateVisit
     *
     * @return \DateTime
     */
    public function getDateVisit()
    {
        return $this->dateVisit;
    }
    
    /**
    * @Assert\Callback
    */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        //$isNotDate = $this->isNotDate($this->getDateVisit());
        //$sundaytuesday = $this->isSundayOrTuesday($this->getDateVisit());
        //$isPastDays = $this->isPastDays($this->getDateVisit());
        //$isAfter2Pm = $this->isAfter2Pm($this->getDateVisit(), $this->getType());
        //$isClosed = $this->isClosed($this->getDateVisit());
        
        if($this->isNotDate($this->getDateVisit()) == false)
        {
           $context->buildViolation('Veuillez rentrer une date valide.')
                ->atPath('dateOfVisit')
                ->addViolation();
        }
        else
        {
            if($this->isSundayOrTuesday($this->getDateVisit()) == true)
            {
            $context->buildViolation('Le musée est fermé le dimanche et le mardi.')
                ->atPath('dateOfVisit')
                ->addViolation();
            }
        
            if($this->isPastDays($this->getDateVisit()) == true) 
            {
                $context->buildViolation('Vous ne pouvez pas réserver pour un jour passé')
                    ->atPath('dateOfVisit')
                    ->addViolation();
            }
        
            if($this->isAfter2Pm($this->getDateVisit(), $this->getType()) == true)
            {
                $context->buildViolation('Vous ne pouvez pas réserver de billet journée après 14h pour ce jour')
                    ->atPath('dateOfVisit')
                    ->addViolation();
            }
        
           if($this->isClosed($this->getDateVisit()) == true)
           {
               $context->buildViolation('Le musée est fermé pendant les jours fériés.')
                    ->atPath('dateOfVisit')
                    ->addViolation();
           }
        }
            
        
    }
    
    public function isNotDate($date)
    {
        if($date == null)
        {
            return false;
        }
        else {
            $result = $date->format('Y-m-d');
            return (\DateTime::createFromFormat('Y-m-d', $result) !== true);
        }
        
        
    }
    
    public function isSundayOrTuesday($date) 
    {
        $result = $date->format('Y-m-d');
        $timestamp = strtotime($result);
        $jour = date('w', $timestamp);
        
        // SI mardi ou dimanche
        if($jour == 0 || $jour == 2) {
            return true;
        }
    }
    
    public function isPastDays($date)
    {   
        $datef = $date->format('Y-m-d');
        $dateAct = new \DateTime();
        $dateformat = $dateAct->format("Y-m-d");
        if($datef < $dateformat)
        {
            return true;
        }
    }
    
    
    public function isAfter2Pm($date, $type)
    {
        $dateForm = $date->format('Y-m-d');
        $dateAct = new \DateTime();
        $dateActFormat = $dateAct->format('Y-m-d');
        
        if($dateActFormat == $dateForm && $type == 'journee')
        {
            $dateFormat = $dateAct->format('H:m:s');
            $limit = new \DateTime('14:00:00');
            $limitFormat = $limit->format('H:m:s');
            
            if($dateFormat >= $limitFormat)
            {
                return true;
            }
        }
        
    }
        
    public function isClosed($date) 
    {
        $result = $date->format('Y-m-d');
        $timestamp = strtotime($result);
        $notWorkable = $this->isNotWorkable($timestamp);
        return $notWorkable;
    }

    
    function isNotWorkable($date)
	{
 
	  	if ($date === null)
	  	{
	    	$date = time();
	  	}
 
	 	$date = strtotime(date('m/d/Y',$date));
 
	 	$year = date('Y',$date);
 
		$easterDate  = easter_date($year);
		$easterDay   = date('j', $easterDate);
		$easterMonth = date('n', $easterDate);
		$easterYear   = date('Y', $easterDate);
 
		$holidays = array(
	    // Dates fixes
	    mktime(0, 0, 0, 1,  1,  $year),  // 1er janvier
	    mktime(0, 0, 0, 5,  1,  $year),  // Fête du travail
	    mktime(0, 0, 0, 5,  8,  $year),  // Victoire des alliés
	    mktime(0, 0, 0, 7,  14, $year),  // Fête nationale
	    mktime(0, 0, 0, 8,  15, $year),  // Assomption
	    mktime(0, 0, 0, 11, 1,  $year),  // Toussaint
	    mktime(0, 0, 0, 11, 11, $year),  // Armistice
	    mktime(0, 0, 0, 12, 25, $year),  // Noel
 
	    // Dates variables
	    mktime(0, 0, 0, $easterMonth, $easterDay + 1,  $easterYear),
	    mktime(0, 0, 0, $easterMonth, $easterDay + 39, $easterYear),
	    mktime(0, 0, 0, $easterMonth, $easterDay + 50, $easterYear),
		);
 
  	return in_array($date, $holidays);
	}
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tickets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ticket
     *
     * @param \AStudio\BookingBundle\Entity\Ticket $ticket
     *
     * @return Order
     */
    public function addTicket(\AStudio\BookingBundle\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \AStudio\BookingBundle\Entity\Ticket $ticket
     */
    public function removeTicket(\AStudio\BookingBundle\Entity\Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }
}
