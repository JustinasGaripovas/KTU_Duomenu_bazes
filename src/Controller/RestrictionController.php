<?php

namespace App\Controller;

use App\Entity\Restriction;
use App\Form\RestrictionType;
use App\Repository\RestrictionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/restriction")
 */
class RestrictionController extends Controller
{
    /**
     * @Route("/", name="restriction_index", methods="GET")
     */
    public function index(RestrictionRepository $restrictionRepository): Response
    {
        return $this->render('restriction/index.html.twig', ['restrictions' => $restrictionRepository->findAll()]);
    }

    /**
     * @Route("/new", name="restriction_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $restriction = new Restriction();
        $form = $this->createForm(RestrictionType::class, $restriction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($restriction);
            $em->flush();

            return $this->redirectToRoute('restriction_index');
        }

        return $this->render('restriction/new.html.twig', [
            'restriction' => $restriction,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="restriction_show", methods="GET")
     */
    public function show(Restriction $restriction): Response
    {
        return $this->render('restriction/show.html.twig', ['restriction' => $restriction]);
    }

    /**
     * @Route("/{id}/edit", name="restriction_edit", methods="GET|POST")
     */
    public function edit(Request $request, Restriction $restriction): Response
    {
        $form = $this->createForm(RestrictionType::class, $restriction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('restriction_edit', ['id' => $restriction->getId()]);
        }

        return $this->render('restriction/edit.html.twig', [
            'restriction' => $restriction,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="restriction_delete", methods="DELETE")
     */
    public function delete(Request $request, Restriction $restriction): Response
    {
        if ($this->isCsrfTokenValid('delete'.$restriction->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($restriction);
            $em->flush();
        }

        return $this->redirectToRoute('restriction_index');
    }
}
