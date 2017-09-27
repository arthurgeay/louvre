<?php

namespace AStudio\BookingBundle\Repository;

/**
 * OrderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrderRepository extends \Doctrine\ORM\EntityRepository
{
	public function countTickets($date) // Compteur du nombre de ticket
	{
		$qb = $this->createQueryBuilder('o');

		$qb
		   ->select('sum(o.nbTicket)')
		   ->where('o.dateVisit = :date')
		   ->setParameter('date', $date)
		   ;

		return $qb->getQuery()->getSingleScalarResult();
	}
}
