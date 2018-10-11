<?php

namespace App\Controller;

use App\Entity\WinterMaintenance;
use App\Form\WinterMaintenanceType;
use App\Repository\ChoicesRepository;
use App\Repository\LdapUserRepository;
use App\Repository\RoadSectionRepository;
use App\Repository\WinterMaintenanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/winter/maintenance")
 */
class WinterMaintenanceController extends Controller
{
    /**
     * @Route("/", name="winter_maintenance_index", methods="GET")
     */
    public function index(WinterMaintenanceRepository $winterMaintenanceRepository): Response
    {
        return $this->render('winter_maintenance/index.html.twig', ['winter_maintenances' => $winterMaintenanceRepository->findAll()]);
    }

    /**
     * @Route("/new", name="winter_maintenance_new", methods="GET|POST")
     */
    public function new(ChoicesRepository $choicesRepository,RoadSectionRepository $roadSectionRepository, Request $request, LdapUserRepository $ldapUserRepository): Response
    {
        $userName = $this->getUser()->getUserName();
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
