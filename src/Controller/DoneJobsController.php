<?php

namespace App\Controller;

use App\Entity\DoneJobs;
use App\Entity\Inspection;
use App\Form\DoneJobsType;
use App\Repository\DoneJobsRepository;
use App\Repository\LdapUserRepository;
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
    public function index(TestController $testController, LdapUserRepository $ldapUserRepository ,DoneJobsRepository $doneJobsRepository, Request $request, AuthorizationCheckerInterface $authChecker): Response
    {
        $userName = $this->getUser()->getUserName();
        if (!$ldapUserRepository->findUnitIdByUserName($userName)->getSubunit()) {
            $this->addFlash(
                'danger',
                'Jūs nepasirinkęs kelių tarnybos!'
            );
            return $this->redirectToRoute('ldap_user_index');
        } else {
            $username = $this->getUser()->getUserName();
            $em = $this->get('doctrine.orm.entity_manager');
            $subUnitId = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getId();
            $filter = $request->query->get('filter');
            $filterByJobId = $request->query->get('filterJob');
            $filterByRoadId = $request->query->get('filterRoad');
            $dql = '';
            if (true === $authChecker->isGranted('ROLE_ADMIN')) {
                $dql = "SELECT d FROM App:DoneJobs d WHERE (d.Date LIKE '$filter%' AND d.JobId LIKE '$filterByJobId%' AND d.SectionId LIKE '$filterByRoadId%') ORDER BY d.Date DESC";
            } elseif (true === $authChecker->isGranted('ROLE_SUPER_VIEWER')) {
                $dql = "SELECT d FROM App:DoneJobs d WHERE (d.Date LIKE '$filter%' AND d.JobId LIKE '$filterByJobId%' AND d.SectionId LIKE '$filterByRoadId%') ORDER BY d.Date DESC";
            } elseif (true === $authChecker->isGranted('ROLE_UNIT_VIEWER')) {
                $dql = "SELECT d FROM App:DoneJobs d WHERE (d.SubUnitId = '$subUnitId' AND d.Date LIKE '$filter%' AND d.JobId LIKE '$filterByJobId%' AND d.SectionId LIKE '$filterByRoadId%' ) ORDER BY d.Date DESC";
            } elseif (true === $authChecker->isGranted('ROLE_SUPER_MASTER')) {
                $dql = "SELECT d FROM App:DoneJobs d WHERE (d.SubUnitId = '$subUnitId' d.Date LIKE '$filter%' AND d.JobId LIKE '$filterByJobId%' AND d.SectionId LIKE '$filterByRoadId%') ORDER BY d.Date DESC";
            } elseif (true === $authChecker->isGranted('ROLE_ROAD_MASTER')) {
                $dql = "SELECT d FROM App:DoneJobs d WHERE (d.SubUnitId = '$subUnitId' d.Date LIKE '$filter%' AND d.JobId LIKE '$filterByJobId%' AND d.SectionId LIKE '$filterByRoadId%') ORDER BY d.Date DESC";
            } elseif (true === $authChecker->isGranted('ROLE_WORKER')) {
                $dql = "SELECT d FROM App:DoneJobs d WHERE (d.Username = '$username' AND d.Date LIKE '$filter%' AND d.JobId LIKE '$filterByJobId%' AND d.SectionId LIKE '$filterByRoadId%') ORDER BY d.Date DESC";
            }

            //
            $query = $em->createQuery($dql);
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), $request->query->getInt('limit', 20));
            return $this->render('done_jobs/index.html.twig', ['pagination' => $pagination]);
        }
    }

    /**
     * @Route("/new", name="done_jobs_new", methods="GET|POST")
     */
    public function new(TestController $testController, LdapUserRepository $ldapUserRepository, Request $request): Response
    {

        $userName = $this->getUser()->getUserName();
        if (!$ldapUserRepository->findUnitIdByUserName($userName)->getSubunit()) {
            $this->addFlash(
                'danger',
                'Jūs nepasirinkęs kelių tarnybos!'
            );
            return $this->redirectToRoute('ldap_user_index');
        } else {
            $doneJob = new DoneJobs();
            $subUnitId = $ldapUserRepository->findUnitIdByUserName($userName)->getSubunit()->getId();
            $recordTime = new \DateTime("now");
            $doneJob->setUsername($userName);
            $doneJob->setDate($recordTime);
            $doneJob->setDoneJobDate(new \DateTime("now"));
            $doneJob->setSubUnitId($subUnitId);
            $form = $this->createForm(DoneJobsType::class, $doneJob);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($doneJob);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Naujas įrašas sėkmingai sukurtas!'
                );

                return $this->redirectToRoute('done_jobs_index');
            }

            return $this->render('done_jobs/new.html.twig', [
                'done_job' => $doneJob,
                'form' => $form->createView(),
            ]);
        }
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
                'success',
                'Įrašas sėkmingai atnaujintas!'
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
                'warning',
                'Įrašas sėkmingai ištrintas!'
            );
        }

        return $this->redirectToRoute('done_jobs_index');
    }


    /**
     * @Route("/add/{id}", name="done_jobs_add_job_to_inspection", methods="GET|POST")
     */

    public function addJobToInspectionById(LdapUserRepository $ldapUserRepository,Request $request, $id): Response
    {
        $doneJob = new DoneJobs();
        $inspection = $this->getDoctrine()->getRepository('App:Inspection')->find($id);
        $roadSection = $inspection->getRoadId();
        $roadSectionDetais = $this->getDoctrine()->getRepository('App:RoadSection')->findOneBy(['SectionId'=>$roadSection]);
        $roadLevel = $roadSectionDetais->getLevel();
        $roadSectionWithName = $roadSection .' '. $roadSectionDetais->getSectionName();
        $roadSectionBegin = $inspection->getRoadSectionBegin();
        $roadSectionEnd = $inspection->getRoadSectionEnd();
        $doneJob->setInspection($inspection);
        $doneJob->setRoadSection($roadSectionWithName);
        $doneJob->setRoadSectionBegin($roadSectionBegin);
        $doneJob->setRoadSectionEnd($roadSectionEnd);
        $doneJob->setSectionId($roadSection);
        $doneJob->setRoadSection($roadSectionWithName);
        $doneJob->setRoadLevel($roadLevel);
        $userName = $this->getUser()->getUserName();
        $subUnitId = $ldapUserRepository->findUnitIdByUserName($userName)->getSubunit()->getId();
        $recordTime = new \DateTime("now");
        $doneJob->setUsername($userName);
        $doneJob->setDate($recordTime);
        $doneJob->setSubUnitId($subUnitId);
        $doneJob->setDoneJobDate(new \DateTime("now"));
        $form = $this->createForm(DoneJobsType::class, $doneJob);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($doneJob);
            $em->flush();

            $this->addFlash(
                'success',
                'Naujas įrašas sėkmingai sukurtas!'
            );

            return $this->redirectToRoute('inspection_index');
        }

        return $this->render('done_jobs/new_for_inspection.html.twig', [
            'done_job' => $doneJob,
            'form' => $form->createView(),
        ]);
    }
}
