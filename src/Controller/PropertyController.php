<?php
namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\ContactType;
use App\Form\PropertySearchType;
use App\Notification\ContactNotification;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
	 * @param PaginatorInterface $paginator
	 * @param Request            $request
	 *
	 * @return Response
	 */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
    	//Create entity witch present our search
	    $search = new PropertySearch();

	    //create form
	    $form = $this->createForm(PropertySearchType::class, $search);

	    //manage the treatment in controller
	    $form->handleRequest($request);

        return $this->render('property/index.html.twig', [
        	'current_menu' => 'properties',
	        'properties'   => $this->repository->paginateAllVisible($search, $request->query->getInt('page',1)),
	        'form'         => $form->createView()
        ]);
    }

	/**
	 * @Route("/immobilien/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
	 * @param Property $property
	 * @param String $slug
	 *
	 * @return Response
	 */
    public function show(Property $property, String $slug, Request $request, ContactNotification $notification): Response
    {
	    $actuallySlug = $property->getSlug();
    	if($actuallySlug !== $slug){
    		return $this->redirectToRoute('property.show', [
    			'id'  => $property->getId(),
    		    'slug'=> $actuallySlug
		    ], 301);
	    }


	    $contact = new Contact();
	    $contact->setProperty($property->setPicture($property->getPictures()[0]));
	    $form = $this->createForm(ContactType::class, $contact);
	    $form->handleRequest($request);

	    if($form->isSubmitted() && $form->isValid()){
		    $notification->notify($contact);
	    	$this->addFlash('success', 'Ihre E-mail ist erfolgreich gesendet ');

		    return $this->redirectToRoute('property.show', [
			    'id'  => $property->getId(),
			    'slug'=> $actuallySlug
		    ]);

	    }
    	return $this->render('property/show.html.twig', [
    		'property'     => $property,
    		'current_menu' => 'properties',
		    'form'         => $form->createView()
	    ]);
{
}
    }
}