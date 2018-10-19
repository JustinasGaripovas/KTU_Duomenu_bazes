<?php

namespace App\Controller;

use App\Entity\WinterMaintenance;
use App\Form\WinterMaintenanceType;
use App\Repository\ChoicesRepository;
use App\Repository\LdapUserRepository;
use App\Repository\RoadSectionRepository;
use App\Repository\WinterMaintenanceRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/winter/maintenance")
 * @IsGranted("WINTER")
 */
class WinterMaintenanceController extends Controller
{
    /**
     * @Route("/", name="winter_maintenance_index", methods="GET")
     */
    public function index(WinterMaintenanceRepository $winterMaintenanceRepository, LdapUserRepository $ldapUserRepository, Request $request ): Response
    {
        $username = $this->getUser()->getUserName();
        $subunit = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getSubunitId();

        if (!$ldapUserRepository->findUnitIdByUserName($username)->getSubunit()) {
            $this->addFlash(
                'danger',
                'Jūs nepasirinkęs kelių tarnybos!'
            );
            return $this->redirectToRoute('ldap_user_index');
        }

        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "";

        if (true === $this->isGranted('ROLE_ROAD_MASTER')) {
            $dql = "SELECT w FROM App:WinterMaintenance w WHERE w.Subunit = '$subunit' ORDER BY w.CreatedAt DESC";
        } elseif (true === $this->isGranted('ROLE_SUPER_MASTER')) {
            $dql = "SELECT w FROM App:WinterMaintenance w WHERE w.Subunit = '$subunit' ORDER BY w.CreatedAt DESC";
        } elseif (true === $this->isGranted('ROLE_UNIT_VIEWER')) {
            $dql = "SELECT w FROM App:WinterMaintenance w WHERE w.Subunit = '$subunit' ORDER BY w.CreatedAt DESC";
        } elseif (true === $this->isGranted('ROLE_SUPER_VIEWER')) {
            $dql = "SELECT w FROM App:WinterMaintenance w  ORDER BY w.CreatedAt DESC";
        } elseif (true === $this->isGranted('ROLE_ADMIN')) {
            $dql = "SELECT w FROM App:WinterMaintenance w  ORDER BY w.CreatedAt DESC";
        } elseif (true === $this->isGranted('ROLE_WORKER')) {
            $dql = "SELECT w FROM App:WinterMaintenance w WHERE w.Subunit = '$subunit' ORDER BY w.CreatedAt DESC";
        }

        $query = $em->createQuery($dql);

        $dql = "SELECT s FROM App:Subunit s";
        $subunitArray = array();
        foreach ($em->createQuery($dql)->execute() as $item)
        {
            $subunitArray[$item->getSubunitId()] = $item->getName();
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), $request->query->getInt('limit', 20));
        return $this->render('winter_maintenance/index.html.twig', ['winter_maintenances' => $pagination,'subunits'=>$subunitArray]);
    }

    /**
     * @Route("/new", name="winter_maintenance_new", methods="GET|POST")
     */
    public function new(ChoicesRepository $choicesRepository,RoadSectionRepository $roadSectionRepository, Request $request, LdapUserRepository $ldapUserRepository): Response
    {
        $userName = $this->getUser()->getUserName();

        if (!$ldapUserRepository->findUnitIdByUserName($userName)->getSubunit()) {
            $this->addFlash(
                'danger',
                'Jūs nepasirinkęs kelių tarnybos!'
            );
            return $this->redirectToRoute('ldap_user_index');
        }

        $em = $this->getDoctrine()->getRepository('App:Subunit');
        $unitId = $ldapUserRepository->findUnitIdByUserName($userName)->getSubunit()->getId();
        $subUnitId = $em->findOneBy(['id' => $unitId])->getSubunitId();
        $winterMaintenance = new WinterMaintenance();
        $winterMaintenance->setCreatedAt(new \DateTime("now"));
        $winterMaintenance->setSubunit($subUnitId);
        $form = $this->createForm(WinterMaintenanceType::class, $winterMaintenance);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($winterMaintenance);
            $em->flush();

            return $this->redirectToRoute('winter_maintenance_index');
        }

        return $this->render('winter_maintenance/new.html.twig', [
            'winter_maintenance' => $winterMaintenance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="winter_maintenance_show", methods="GET")
     */
    public function show(WinterMaintenance $winterMaintenance): Response
    {
        return $this->render('winter_maintenance/show.html.twig', ['winter_maintenance' => $winterMaintenance]);
    }

    /**
     * @Route("/{id}/edit", name="winter_maintenance_edit", methods="GET|POST")
     */
    public function edit(Request $request, WinterMaintenance $winterMaintenance): Response
    {
        $form = $this->createForm(WinterMaintenanceType::class, $winterMaintenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('winter_maintenance_edit', ['id' => $winterMaintenance->getId()]);
        }

        return $this->render('winter_maintenance/edit.html.twig', [
            'winter_maintenance' => $winterMaintenance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="winter_maintenance_delete", methods="DELETE")
     */
    public function delete(Request $request, WinterMaintenance $winterMaintenance): Response
    {
        if ($this->isCsrfTokenValid('delete'.$winterMaintenance->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($winterMaintenance);
            $em->flush();
        }

        return $this->redirectToRoute('winter_maintenance_index');
    }
}
