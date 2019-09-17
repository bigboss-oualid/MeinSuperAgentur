<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class PropertySearch
{
	/**
	 * @var int|null
	 */
	private $maxPrice;

	/**
	 * @var int|null
	 * @Assert\Range(min=10, max=400)
	 */
	private $minSurface;

	/**
	 * @var ArrayCollection
	 */
	private $specifications;

	public function __construct()
	{
		$this->specifications = new ArrayCollection();
	}

	/**
	 * @return int|null
	 */
	public function getMaxPrice(): ?int
	{
		return $this->maxPrice;
	}

	/**
	 * @param int $maxPrice
	 *
	 * @return PropertySearch
	 */
	public function setMaxPrice(int $maxPrice): PropertySearch
	{
		$this->maxPrice = $maxPrice;
		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getMinSurface(): ?int
	{
		return $this->minSurface;
	}

	/**
	 * @param int $minSurface
	 *
	 * @return PropertySearch
	 */
	public function setMinSurface(int $minSurface): PropertySearch
	{
		$this->minSurface = $minSurface;
		return $this;
	}

	/**
	 * @return ArrayCollection
	 */
	public function getSpecifications(): ArrayCollection
	{
		return $this->specifications;
	}

	/**
	 * @param ArrayCollection $specifications
	 */
	public function setSpecifications(ArrayCollection $specifications): void
	{
		$this->specifications = $specifications;
	}

}