<?php

namespace App\Controller;

use App\Entity\Subunit;
use App\Form\SubunitType;
use App\Repository\SubunitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/subunit")
 */
class SubunitController extends AbstractController
{
    /**
     * @Route("/", name="subunit_index", methods={"GET"})
     */
    public function index(SubunitRepository $subunitRepository): Response
    {
        return $this->render('subunit/index.html.twig', [
            'subunits' => $subunitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="subunit_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $subunit = new Subunit();
        $form = $this->createForm(SubunitType::class, $subunit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subunit);
            $entityManager->flush();

            return $this->redirectToRoute('subunit_index');
        }

        return $this->render('subunit/new.html.twig', [
            'subunit' => $subunit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="subunit_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Subunit $subunit): Response
    {
        $form = $this->createForm(SubunitType::class, $subunit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();



            return $this->redirectToRoute('subunit_index', [
                'id' => $subunit->getId(),
            ]);
        }

        return $this->render('subunit/edit.html.twig', [
            'subunit' => $subunit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="subunit_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Subunit $subunit): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subunit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($subunit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('subunit_index');
    }
}
