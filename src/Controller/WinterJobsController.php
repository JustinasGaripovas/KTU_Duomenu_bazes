<?php

namespace App\Controller;

use App\Entity\WinterJobs;
use App\Form\WinterJobsType;
use App\Repository\ChoicesRepository;
use App\Repository\LdapUserRepository;
use App\Repository\MechanismRepository;
use App\Repository\WinterJobsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/winter/jobs")
 * @IsGranted("WINTER")
 */
class WinterJobsController extends Controller
{
    /**
     * @Route("/", name="winter_jobs_index", methods="GET")
     */
    public function index(LdapUserRepository $ldapUserRepository, WinterJobsRepository $winterJobsRepository, Request $request): Response
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $username = $this->getUser()->getUserName();
        $subunit = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getSubunitId();

        if (!$ldapUserRepository->findUnitIdByUserName($username)->getSubunit()) {
            $this->addFlash(
                'danger',
                'Jūs nepasirinkęs kelių tarnybos!'
            );
            return $this->redirectToRoute('ldap_user_index');
        }

        $dataFiter = $request->query->get('dataFilter');
        $mechanizmFilter = $request->query->get('mechanizmFilter');
        $subunitFilter = $request->query->get('subunitFilter');

        /* (d.Date LIKE '$filter%' AND d.JobId LIKE '$filterByJobId%' AND d.SectionId LIKE '$filterByRoadId%') */

        if ($this->isGranted('ADMIN')) {
            $dql = "SELECT w FROM App:WinterJobs w WHERE (w.Date LIKE '$dataFiter%' AND w.Mechanism LIKE '$mechanizmFilter%' AND w.SubunitName LIKE '$subunitFilter%') ORDER BY w.CreatedAt DESC";
        }
        elseif ($this->isGranted('SUPER_VIEWER')){
            $dql = "SELECT w FROM App:WinterJobs w WHERE (w.Date LIKE '$dataFiter%' AND w.Mechanism LIKE '$mechanizmFilter%' AND w.SubunitName LIKE '$subunitFilter%') ORDER BY w.CreatedAt DESC";
        }
        elseif ($this->isGranted('UNIT_VIEWER')) {
            $dql = "SELECT w FROM App:WinterJobs w WHERE w.Subunit = '$subunit' AND (w.Date LIKE '$dataFiter%' AND w.Mechanism LIKE '$mechanizmFilter%' AND w.SubunitName LIKE '$subunitFilter%') ORDER BY w.CreatedAt DESC";
        }
        elseif ($this->isGranted('SUPER_MASTER')) {
            $dql = "SELECT w FROM App:WinterJobs w WHERE w.Subunit = '$subunit' AND (w.Date LIKE '$dataFiter%' AND w.Mechanism LIKE '$mechanizmFilter%' AND w.SubunitName LIKE '$subunitFilter%') ORDER BY w.CreatedAt DESC";
        }
        elseif ($this->isGranted('ROAD_MASTER')) {
            $dql = "SELECT w FROM App:WinterJobs w WHERE w.Subunit = '$subunit' AND (w.Date LIKE '$dataFiter%' AND w.Mechanism LIKE '$mechanizmFilter%' AND w.SubunitName LIKE '$subunitFilter%') ORDER BY w.CreatedAt DESC";
        }
        elseif($this->isGranted('WORKER') ) {
            $dql = "SELECT w FROM App:WinterJobs w WHERE w.Subunit = '$subunit' AND (w.Date LIKE '$dataFiter%' AND w.Mechanism LIKE '$mechanizmFilter%' AND w.SubunitName LIKE '$subunitFilter%') ORDER BY w.CreatedAt DESC";
        }

        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), $request->query->getInt('limit', $request->query->getInt('limit', 20)));
        return $this->render('winter_jobs/index.html.twig', ['winter_jobs' => $pagination]);
    }

    /**
     * @Route("/new", name="winter_jobs_new", methods="GET|POST")
     */
    public function new(ChoicesRepository $choicesRepository, MechanismRepository $mechanismRepository, LdapUserRepository $ldapUserRepository,Request $request): Response
    {
        $username = $this->getUser()->getUserName();
        if (!$ldapUserRepository->findUnitIdByUserName($username)->getSubunit()) {
            $this->addFlash(
                'danger',
                'Jūs nepasirinkęs kelių tarnybos!'
            );
            return $this->redirectToRoute('ldap_user_index');
        }

        $username = $this->getUser()->getUserName();
        $subunit = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit();
        $choicesName = array();
        $choicesKey = array();
        $choices = $mechanismRepository->findBySubunitField($subunit->getSubunitId());
        foreach ($choices as $item) {
            array_push($choicesName, $item['Type'] . ' | ' . $item['Number']);
            array_push($choicesKey, $item['Type'] . ' | ' . $item['Number']);
        }
        $choicesArray = array_combine($choicesKey, $choicesName);
        $choices = $choicesRepository->findChoiceByChoiceId(4);
        $choicesName = array();
        $choicesKey = array();
        foreach ($choices as $item) {
            array_push($choicesName, $item['Choice']);
            array_push($choicesKey, $item['Choice']);
        }
        $choiceArrayForJobs = array_combine($choicesKey, $choicesName);

        $winterJob = new WinterJobs();
        $winterJob->setSubunit($subunit->getSubunitId());
        $winterJob->setSubunitName($subunit->getName());
        $winterJob->setCreatedBy($this->getUser()->getUserName());
        $winterJob->setCreatedAt(new \DateTime("now"));

        $form = $this->createForm(WinterJobsType::class, $winterJob, ['mechanism_choices' => $choicesArray, 'jobs_choices' => $choiceArrayForJobs ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($winterJob);
            $em->flush();
            return $this->redirectToRoute('winter_jobs_index');

        }

        return $this->render('winter_jobs/new.html.twig', [
            'winter_job' => $winterJob,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="winter_jobs_show", methods="GET")
     */
    public function show(WinterJobs $winterJob): Response
    {
        $this->denyAccessUnlessGranted('SHOW',$winterJob);

        return $this->render('winter_jobs/show.html.twig', ['winter_job' => $winterJob]);
    }

    /**
     * @Route("/{id}/edit", name="winter_jobs_edit", methods="GET|POST")
     */
    public function edit(ChoicesRepository $choicesRepository, MechanismRepository $mechanismRepository, LdapUserRepository $ldapUserRepository, Request $request, WinterJobs $winterJob): Response
    {
        $this->denyAccessUnlessGranted('EDIT',$winterJob);

        $username = $this->getUser()->getUserName();
        $subunitId = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getSubunitId();
        $choicesName = array();
        $choicesKey = array();
        $choices = $mechanismRepository->findBySubunitField($subunitId);
        foreach ($choices as $item) {
            array_push($choicesName, $item['Type'] . ' | ' . $item['Number']);
            array_push($choicesKey, $item['Type'] . ' | ' . $item['Number']);
        }
        $choicesArray = array_combine($choicesKey, $choicesName);

        $choices = $choicesRepository->findChoiceByChoiceId(4);
        $choicesName = array();
        $choicesKey = array();
        foreach ($choices as $item) {
            array_push($choicesName, $item['Choice']);
            array_push($choicesKey, $item['Choice']);
        }
        $choiceArrayForJobs = array_combine($choicesKey, $choicesName);

        $form = $this->createForm(WinterJobsType::class, $winterJob, ['mechanism_choices' => $choicesArray, 'jobs_choices' => $choiceArrayForJobs]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $items = $winterJob->getRoadSections();
            $winterJob->setRoadSections($items);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('winter_jobs_index', ['id' => $winterJob->getId()]);
        }

        return $this->render('winter_jobs/edit.html.twig', [
            'winter_job' => $winterJob,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="winter_jobs_delete", methods="DELETE")
     */
    public function delete(Request $request, WinterJobs $winterJob): Response
    {
        $this->denyAccessUnlessGranted('DELETE',$winterJob);

        if ($this->isCsrfTokenValid('delete'.$winterJob->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($winterJob);
            $em->flush();
        }

        return $this->redirectToRoute('winter_jobs_index');
    }

}
