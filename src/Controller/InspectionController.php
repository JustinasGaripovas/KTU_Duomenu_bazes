<?php

namespace App\Controller;

use App\Entity\Inspection;
use App\Form\InspectionType;
use App\Repository\InspectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/inspection")
 */
class InspectionController extends Controller
{
    /**
     * @Route("/", name="inspection_index", methods="GET")
     */
    public function index(InspectionRepository $inspectionRepository): Response
    {
        return $this->render('inspection/index.html.twig', ['inspections' => $inspectionRepository->findAll()]);
    }

    /**
     * @Route("/new", name="inspection_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $inspection = new Inspection();
        $form = $this->createForm(InspectionType::class, $inspection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inspection);
            $em->flush();

            return $this->redirectToRoute('inspection_index');
        }

        return $this->render('inspection/new.html.twig', [
            'inspection' => $inspection,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="inspection_show", methods="GET")
     */
    public function show(Inspection $inspection): Response
    {
        return $this->render('inspection/show.html.twig', ['inspection' => $inspection]);
    }

    /**
     * @Route("/{id}/edit", name="inspection_edit", methods="GET|POST")
     */
    public function edit(Request $request, Inspection $inspection): Response
    {
        $form = $this->createForm(InspectionType::class, $inspection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('inspection_edit', ['id' => $inspection->getId()]);
        }

        return $this->render('inspection/edit.html.twig', [
            'inspection' => $inspection,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="inspection_delete", methods="DELETE")
     */
    public function delete(Request $request, Inspection $inspection): Response
    {
        if ($this->isCsrfTokenValid('delete'.$inspection->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($inspection);
            $em->flush();
        }

        return $this->redirectToRoute('inspection_index');
    }
}
