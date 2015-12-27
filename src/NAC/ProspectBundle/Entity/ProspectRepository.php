<?php

namespace NAC\ProspectBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
/**
 * ProspectRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProspectRepository extends EntityRepository
{   
	public function getProspects($page, $nbPerPage)
	  {
		$query = $this->createQueryBuilder('p')
		  ->leftJoin('p.domaines', 'd')
		  ->addSelect('d')
		  ->orderBy('p.date', 'DESC')
		  ->getQuery()
		;

		$query
		  // On définit l'annonce à partir de laquelle commencer la liste
		  ->setFirstResult(($page-1) * $nbPerPage)
		  // Ainsi que le nombre d'annonce à afficher sur une page
		  ->setMaxResults($nbPerPage)
		;

		// Enfin, on retourne l'objet Paginator correspondant à la requête construite
		// (n'oubliez pas le use correspondant en début de fichier)
		return new Paginator($query, true);
	  }
	  
	
}