<?php

namespace AStudio\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use AStudio\BookingBundle\Validator\SundayOrTuesday;
use AStudio\BookingBundle\Validator\PastDays;
use AStudio\BookingBundle\Validator\After2Pm;
use AStudio\BookingBundle\Validator\Holidays;
use AStudio\BookingBundle\Validator\VerifTicket;

/**
 * Order
 *
 * @ORM\Table(name="`order`")
 * @ORM\Entity(repositoryClass="AStudio\BookingBundle\Repository\OrderRepository")
 * @VerifTicket()
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
    * @SundayOrTuesday()
    * @PastDays()
    * @After2Pm()
    * @Holidays()
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
    * @Assert\Valid()
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
        $ticket->setOrder($this);

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
