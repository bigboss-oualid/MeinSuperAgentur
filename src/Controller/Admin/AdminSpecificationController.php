<?php

namespace App\Controller\Admin;

use App\Entity\Specification;
use App\Form\SpecificationType;
use App\Repository\SpecificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/specification")
 */
class AdminSpecificationController extends AbstractController
{
	/**
	 * @Route("/", name="admin.specification.index", methods={"GET"})
	 * @param SpecificationRepository $specificationRepository
	 *
	 * @return Response
	 */
    public function index(SpecificationRepository $specificationRepository): Response
    {
        return $this->render('admin/specification/index.html.twig', [
            'specifications' => $specificationRepository->findAll(),
        ]);
    }

	/**
	 * @Route("/new", name="admin.specification.new", methods={"GET","POST"})
	 * @param Request $request
	 *
	 * @return Response
	 */
    public function new(Request $request): Response
    {
        $specification = new Specification();
        $form = $this->createForm(SpecificationType::class, $specification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($specification);
            $entityManager->flush();

            return $this->redirectToRoute('admin.specification.index');
        }

        return $this->render('admin/specification/new.html.twig', [
            'specification' => $specification,
            'form' => $form->createView(),
        ]);
    }

	/**
	 * @Route("/{id}/edit", name="admin.specification.edit", methods={"GET","POST"})
	 * @param Request       $request
	 * @param Specification $specification
	 *
	 * @return Response
	 */
    public function edit(Request $request, Specification $specification): Response
    {
        $form = $this->createForm(SpecificationType::class, $specification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.specification.index');
        }

        return $this->render('admin/specification/edit.html.twig', [
            'specification' => $specification,
            'form' => $form->createView(),
        ]);
    }

	/**
	 * @Route("/{id}", name="admin.specification.delete", methods={"DELETE"})
	 * @param Request       $request
	 * @param Specification $specification
	 *
	 * @return Response
	 */
    public function delete(Request $request, Specification $specification): Response
    {
        if ($this->isCsrfTokenValid('delete'.$specification->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($specification);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.specification.index');
    }
}
