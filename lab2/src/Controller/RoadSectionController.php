<?php

namespace App\Controller;

use App\Entity\RoadSection;
use App\Form\RoadSectionType;
use App\Repository\RoadSectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/road/section")
 */
class RoadSectionController extends AbstractController
{
    /**
     * @Route("/", name="road_section_index", methods={"GET"})
     */
    public function index(RoadSectionRepository $roadSectionRepository): Response
    {
        return $this->render('road_section/index.html.twig', [
            'road_sections' => $roadSectionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="road_section_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $roadSection = new RoadSection();
        $form = $this->createForm(RoadSectionType::class, $roadSection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($roadSection);
            $entityManager->flush();

            return $this->redirectToRoute('road_section_index');
        }

        return $this->render('road_section/new.html.twig', [
            'road_section' => $roadSection,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="road_section_show", methods={"GET"})
     */
    public function show(RoadSection $roadSection): Response
    {
        return $this->render('road_section/show.html.twig', [
            'road_section' => $roadSection,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="road_section_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, RoadSection $roadSection): Response
    {
        $form = $this->createForm(RoadSectionType::class, $roadSection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('road_section_index', [
                'id' => $roadSection->getId(),
            ]);
        }

        return $this->render('road_section/edit.html.twig', [
            'road_section' => $roadSection,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="road_section_delete", methods={"DELETE"})
     */
    public function delete(Request $request, RoadSection $roadSection): Response
    {
        if ($this->isCsrfTokenValid('delete'.$roadSection->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($roadSection);
            $entityManager->flush();
        }

        return $this->redirectToRoute('road_section_index');
    }
}
