<?php
namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $entityManager;

    public function __construct(PropertyRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->entityManager = $em;
    }

    /**
     * @Route("/immobilien", name="property.index")
     * @return Response
     */
    public function index(): Response
    {
	    //$this->entityManager->flush();
        return $this->render('property/index.html.twig', ['current_menu' => 'properties']);
    }

	/**
	 * @Route("/immobilien/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
	 * @param Property $property
	 * @param String $slug
	 *
	 * @return Response
	 */
    public function show(Property $property, String $slug): Response
    {
	    $actuallySlug = $property->getSlug();
    	if($actuallySlug !== $slug){
    		return $this->redirectToRoute('property.show', [
    			'id'  => $property->getId(),
    		    'slug'=> $actuallySlug
		    ], 301);
	    }
    	return $this->render('property/show.html.twig', [
    		'property' => $property,
    		'current_menu' => 'properties'
	    ]);
{
}
    }
}