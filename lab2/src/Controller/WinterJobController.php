<?php

namespace App\Controller;

use App\Entity\WinterJob;
use App\Form\WinterJobType;
use App\Repository\WinterJobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/winter/job")
 */
class WinterJobController extends AbstractController
{
    /**
     * @Route("/", name="winter_job_index", methods={"GET"})
     */
    public function index(WinterJobRepository $winterJobRepository): Response
    {
        return $this->render('winter_job/index.html.twig', [
            'winter_jobs' => $winterJobRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="winter_job_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $winterJob = new WinterJob();
        $form = $this->createForm(WinterJobType::class, $winterJob);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($winterJob);
            $entityManager->flush();

            return $this->redirectToRoute('winter_job_index');
        }

        return $this->render('winter_job/new.html.twig', [
            'winter_job' => $winterJob,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="winter_job_show", methods={"GET"})
     */
    public function show(WinterJob $winterJob): Response
    {
        return $this->render('winter_job/show.html.twig', [
            'winter_job' => $winterJob,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="winter_job_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, WinterJob $winterJob): Response
    {
        $form = $this->createForm(WinterJobType::class, $winterJob);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('winter_job_index', [
                'id' => $winterJob->getId(),
            ]);
        }

        return $this->render('winter_job/edit.html.twig', [
            'winter_job' => $winterJob,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="winter_job_delete", methods={"DELETE"})
     */
    public function delete(Request $request, WinterJob $winterJob): Response
    {
        if ($this->isCsrfTokenValid('delete'.$winterJob->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($winterJob);
            $entityManager->flush();
        }

        return $this->redirectToRoute('winter_job_index');
    }
}
