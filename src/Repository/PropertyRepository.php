<?php

namespace App\Repository;

use App\Entity\Picture;
use App\Entity\Property;
use App\Entity\PropertySearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
	/**
	 * @var PaginatorInterface
	 */
	private $paginator;

	/**
	 * PropertyRepository constructor.
	 *
	 * @param ManagerRegistry    $registry
	 * @param PaginatorInterface $paginator
	 */
	public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Property::class);
	    $this->paginator = $paginator;
    }

	/**
	 * @param PropertySearch $search
	 *
	 * @param int            $page
	 *
	 * @return PaginationInterface
	 */
    public function paginateAllVisible(PropertySearch $search, int $page): PaginationInterface
    {
        $query = $this->findVisibleQuery();

        if($maxPrice = $search->getMaxPrice())
        {
			$query = $query
				->where('p.price <= :maxprice')
	            ->setParameter('maxprice', $maxPrice);

        }

	    if($minSurface = $search->getMinSurface())
	    {
		    $query = $query
			    ->andWhere('p.surface >= :minsurface')
	            ->setParameter('minsurface', $minSurface);

        }

	    dump($search->getLat(),$search->getLng(),$search->getDistance());
        if(($lat = $search->getLat()) && ($lng = $search->getLng()) && ($distance = $search->getDistance()))
        {
        	$query = $query
		        ->andWhere('(6353 * 2 * ASIN(SQRT( POWER(SIN((p.lat - :lat) * pi()/180 / 2), 2) + COS(p.lat * pi()/180) * COS(:lat * pi()/180) * POWER(SIN((p.lng - :lng) * pi()/180 / 2), 2) ))) <= :distance')
	            ->setParameter('lng', $lng)
	            ->setParameter('lat', $lat)
		        ->setParameter('distance', $distance)
	        ;
        }

	    if(($specifications = $search->getSpecifications())->count() > 0)
	    {
	    	$k = 0;
	    	foreach($specifications as $specification) {
			    $k++;
			    $query = $query
				    ->andWhere(":specification$k MEMBER OF p.specifications")
				    ->setParameter("specification$k", $specification);
		    }
	    }

	    $properties = $this->paginator->paginate(
		    $query->getQuery(),
		    $page,
		    12
	    );

		$this->hydratePicture($properties);
        return $properties;
    }


	/**
	 * @return Property[]
	 */
	public function findLatest(): array
	{
		$properties = $this->findVisibleQuery()
			->setMaxResults(4)
			->getQuery()
			->getResult()
			;
		$this->hydratePicture($properties);
		return $properties;
	}

	/**
	 * return All unsold properties
	 *
	 * @return QueryBuilder
	 */
	private function findVisibleQuery(): QueryBuilder
	{
		return $this->createQueryBuilder('p')
			->where('p.sold = false');
    }

	private function hydratePicture($properties) {

		if(method_exists($properties, 'getItems')) {
			$properties = $properties->getItems();
		}
		$pictures = $this->getEntityManager()->getRepository(Picture::class)->findForProperties($properties);
		foreach($properties as $property) {
			/** @var $property Property */
			if($pictures->containsKey($property->getId())) {
				$property->setPicture($pictures->get($property->getId()));
			}
		}
	}
}
