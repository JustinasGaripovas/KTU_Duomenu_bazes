<?php

namespace App\Controller;

use App\Entity\DoneJobs;
use App\Entity\Inspection;
use App\Form\DoneJobsType;
use App\Repository\DoneJobsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


/**
 * @Route("/done/jobs")
 */
class DoneJobsController extends Controller
{
    /**
     * @Route("/", name="done_jobs_index", methods="GET")
     */
    public function index(DoneJobsRepository $doneJobsRepository, Request $request, AuthorizationCheckerInterface $authChecker): Response
    {
        if (false === $authChecker->isGranted('ROLE_ADMIN')) {
            $username = $this->getUser()->getUserName();
            $em = $this->get('doctrine.orm.entity_manager');
            $dql = "SELECT d FROM App:DoneJobs d WHERE d.Username = '$username' ORDER BY d.Date DESC";
            $query = $em->createQuery($dql);

            $paginator  = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                20/*limit per page*/
            );
        }

        else {

            $em = $this->get('doctrine.orm.entity_manager');
            $dql = "SELECT d FROM App:DoneJobs d ORDER BY d.Date DESC";
            $query = $em->createQuery($dql);

            $paginator  = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                20/*limit per page*/
            );
        }

            return $this->render('done_jobs/index.html.twig', ['pagination' => $pagination]);

    }

    /**
     * @Route("/new", name="done_jobs_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $doneJob = new DoneJobs();
        $userName = $this->getUser()->getUserName();
        $recordTime = new \DateTime("now");
        $doneJob->setUsername($userName);
        $doneJob->setDate($recordTime);
        $doneJob->setDoneJobDate(new \DateTime("now"));
        $form = $this->createForm(DoneJobsType::class, $doneJob);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($doneJob);
            $em->flush();

            $this->addFlash(
                'notice',
                'New record successfully added to database!'
            );

            return $this->redirectToRoute('done_jobs_index');
        }

        return $this->render('done_jobs/new.html.twig', [
            'done_job' => $doneJob,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="done_jobs_show", methods="GET")
     */
    public function show(DoneJobs $doneJob): Response
    {
        return $this->render('done_jobs/show.html.twig', ['done_job' => $doneJob]);
    }

    /**
     * @Route("/{id}/edit", name="done_jobs_edit", methods="GET|POST")
     */
    public function edit(Request $request, DoneJobs $doneJob): Response
    {
        $userName = $this->getUser()->getUserName();
        $form = $this->createForm(DoneJobsType::class, $doneJob);
        $doneJob->setUsername($userName);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Record updated successfully!'
            );
            //return $this->redirectToRoute('done_jobs_edit', ['id' => $doneJob->getId()]);
            return $this->redirectToRoute('done_jobs_index');
        }

        return $this->render('done_jobs/edit.html.twig', [
            'done_job' => $doneJob,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="done_jobs_delete", methods="DELETE")
     */
    public function delete(Request $request, DoneJobs $doneJob): Response
    {
        if ($this->isCsrfTokenValid('delete'.$doneJob->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($doneJob);
            $em->flush();
            $this->addFlash(
                'notice',
                'Record deleted successfully!'
            );
        }

        return $this->redirectToRoute('done_jobs_index');
    }


    /**
     * @Route("/add/{id}", name="done_jobs_add_job_to_inspection", methods="GET|POST")
     */

    public function addJobToInspectionById(Request $request, $id): Response
    {
        $doneJob = new DoneJobs();
        $inspection = $this->getDoctrine()->getRepository('App:Inspection')->find($id);
        $doneJob->setInspection($inspection);
        $userName = $this->getUser()->getUserName();
        $recordTime = new \DateTime("now");
        $doneJob->setUsername($userName);
        $doneJob->setDate($recordTime);
        $doneJob->setDoneJobDate(new \DateTime("now"));
        $form = $this->createForm(DoneJobsType::class, $doneJob);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($doneJob);
            $em->flush();

            $this->addFlash(
                'notice',
                'New record successfully added to database!'
            );

            return $this->redirectToRoute('inspection_index');
        }

        return $this->render('done_jobs/new_for_inspection.html.twig', [
            'done_job' => $doneJob,
            'form' => $form->createView(),
        ]);
    }
}
