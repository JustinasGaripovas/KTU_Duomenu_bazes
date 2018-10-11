<?php

namespace App\Controller;

use App\Entity\WinterJobRoadSection;
use App\Entity\WinterJobs;
use App\Form\WinterJobsType;
use App\Repository\ChoicesRepository;
use App\Repository\LdapUserRepository;
use App\Repository\MechanismRepository;
use App\Repository\WinterJobsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/winter/jobs")
 */
class WinterJobsController extends Controller
{
    /**
     * @Route("/", name="winter_jobs_index", methods="GET")
     */
    public function index(WinterJobsRepository $winterJobsRepository, Request $request): Response
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT w FROM App:WinterJobs w  ORDER BY w.CreatedAt DESC";
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), $request->query->getInt('limit', 20));
        return $this->render('winter_jobs/index.html.twig', ['winter_jobs' => $pagination]);
    }

    /**
     * @Route("/new", name="winter_jobs_new", methods="GET|POST")
     */
    public function new(ChoicesRepository $choicesRepository, MechanismRepository $mechanismRepository, LdapUserRepository $ldapUserRepository,Request $request): Response
    {
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

        $winterJob = new WinterJobs();
        $winterJob->setSubunit($subunitId);
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
        return $this->render('winter_jobs/show.html.twig', ['winter_job' => $winterJob]);
    }

    /**
     * @Route("/{id}/edit", name="winter_jobs_edit", methods="GET|POST")
     */
    public function edit(ChoicesRepository $choicesRepository, MechanismRepository $mechanismRepository, LdapUserRepository $ldapUserRepository, Request $request, WinterJobs $winterJob): Response
    {
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
        if ($this->isCsrfTokenValid('delete'.$winterJob->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($winterJob);
            $em->flush();
        }

        return $this->redirectToRoute('winter_jobs_index');
    }

}
