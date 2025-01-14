<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\JobType;
use App\Repository\JobRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/job")
 * @IsGranted("ADMIN")
 */
class JobController extends Controller
{
    /**
     * @Route("/", name="job_index", methods="GET")
     */
    public function index(JobRepository $jobRepository): Response
    {
        return $this->render('job/index.html.twig', ['jobs' => $jobRepository->findAll()]);
    }

    /**
     * @Route("/new", name="job_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $job = new Job();

        $this->denyAccessUnlessGranted('EDIT',$job);

        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($job);
            $em->flush();

            return $this->redirectToRoute('job_index');
        }

        return $this->render('job/new.html.twig', [
            'job' => $job,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="job_show", methods="GET")
     */
    public function show(Job $job): Response
    {
        $this->denyAccessUnlessGranted('SHOW',$job);

        return $this->render('job/show.html.twig', ['job' => $job]);
    }

    /**
     * @Route("/{id}/edit", name="job_edit", methods="GET|POST")
     */
    public function edit(Request $request, Job $job): Response
    {
        $this->denyAccessUnlessGranted('EDIT',$job);

        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('job_edit', ['id' => $job->getId()]);
        }

        return $this->render('job/edit.html.twig', [
            'job' => $job,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="job_delete", methods="DELETE")
     */
    public function delete(Request $request, Job $job): Response
    {
        $this->denyAccessUnlessGranted('DELETE',$job);

        if ($this->isCsrfTokenValid('delete'.$job->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($job);
            $em->flush();
        }

        return $this->redirectToRoute('job_index');
    }
}
