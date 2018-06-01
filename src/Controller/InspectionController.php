<?php

namespace App\Controller;

use App\Entity\DoneJobs;
use App\Entity\Inspection;
use App\Form\InspectionType;
use App\Repository\InspectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @Route("/inspection")
 */
class InspectionController extends Controller
{
    /**
     * @Route("/", name="inspection_index", methods="GET")
     */

    public function index(InspectionRepository $inspectionRepository, Request $request): Response
    {
        $username = $this->getUser()->getUserName();
        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT i FROM App:Inspection i WHERE i.Username = '$username' ORDER BY i.id DESC";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            20/*limit per page*/
        );

        return $this->render('inspection/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * @Route("/new", name="inspection_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $inspection = new Inspection();
        $job = new DoneJobs();
        $job->setJobId('');
        $job->setJobName('');
        $job->setRoadSection('');
        $job->setRoadSectionBegin('0');
        $job->setRoadSectionEnd('0');
        $job->setUsername('');
        $job->setDate(new \DateTime("now"));
        $inspection -> setUsername($this->getUser()->getUserName());
        $inspection -> setDate(new \DateTime("now"));
        $inspection->addJob($job);
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
