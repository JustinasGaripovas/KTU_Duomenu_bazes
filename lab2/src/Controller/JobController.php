<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\JobType;
use App\Repository\JobRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/job")
 */
class JobController extends AbstractController
{
    /**
     * @Route("/", name="job_index", methods={"GET"})
     */
    public function index(JobRepository $jobRepository, EntityManagerInterface $entityManager): Response
    {
        $conn = $entityManager
            ->getConnection();
        $sql = 'SELECT  job.id as id,
                        job.description as descr,
                        job.quadrature as quad,
                        job.quadrature_unit as quadUnit,
                        job.code as code,
                        job.danger_level as danger,
                        winter_job.Created_at as createdAt
                        FROM job
                        INNER JOIN winter_job ON job.fk_winterjob_id = winter_job.id; ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $this->render('job/index.html.twig', [
            'jobs' => $stmt->fetchAll(),
        ]);
    }

    /**
     * @Route("/new", name="job_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($job);
            $entityManager->flush();

            dump($request);die;

            return $this->redirectToRoute('job_index');
        }

        return $this->render('job/new.html.twig', [
            'job' => $job,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="job_show", methods={"GET"})
     */
    public function show(Job $job): Response
    {
        return $this->render('job/show.html.twig', [
            'job' => $job,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="job_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Job $job): Response
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('job_index', [
                'id' => $job->getId(),
            ]);
        }

        return $this->render('job/edit.html.twig', [
            'job' => $job,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="job_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Job $job): Response
    {
        if ($this->isCsrfTokenValid('delete'.$job->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($job);
            $entityManager->flush();
        }

        return $this->redirectToRoute('job_index');
    }
}
