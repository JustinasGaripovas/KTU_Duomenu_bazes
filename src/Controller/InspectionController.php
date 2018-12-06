<?php

namespace App\Controller;

use App\Entity\DoneJobs;
use App\Controller\TestController;
use App\Entity\Inspection;
use App\Form\InspectionType;
use App\Repository\InspectionRepository;
use App\Repository\LdapUserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


/**
 * @Route("/inspection")
 * @IsGranted("INSPECTION")
 */
class InspectionController extends Controller
{
    /**
     * @Route("/", name="inspection_index", methods="GET")
     */

    public function index(TestController $testController ,LdapUserRepository $ldapUserRepository, InspectionRepository $inspectionRepository, Request $request, AuthorizationCheckerInterface $authChecker): Response
    {
        $username = $this->getUser()->getUserName();
        if (!$ldapUserRepository->findUnitIdByUserName($username)->getSubunit()) {
            $this->addFlash(
                'danger',
                'Jūs nepasirinkęs kelių tarnybos!'
            );
            return $this->redirectToRoute('ldap_user_index');
        }
        else {
            $subUnitId = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getId();
            $dql = '';

            $dateFlter = $request->query->get('dateFlter');
            $sectionIdFiler = $request->query->get('sectionIdFiler');
            $sectionTypeFilter = $request->query->get('sectionTypeFilter');

            $isAdditional = ($sectionTypeFilter == "Sustiprinta")?true:false;

           // (i.isAdditional LIKE '$isAdditional%' AND i.RoadId LIKE '$sectionIdFiler%' AND i.Date LIKE '$dateFlter%')

            if ($this->isGranted('ADMIN')) {
                $dql = "SELECT i FROM App:Inspection i WHERE (i.IsAdditional = '$isAdditional' AND i.RoadId LIKE '$sectionIdFiler%' AND i.Date LIKE '$dateFlter%') ORDER BY i.id DESC";
            }
            elseif ($this->isGranted('SUPER_VIEWER')){
                $dql = "SELECT i FROM App:Inspection i WHERE (i.IsAdditional = '$isAdditional' AND i.RoadId LIKE '$sectionIdFiler%' AND i.Date LIKE '$dateFlter%') ORDER BY i.id DESC";
            }
            elseif ($this->isGranted('UNIT_VIEWER')) {
                $dql = "SELECT i FROM App:Inspection i WHERE i.SubUnitId = '$subUnitId' AND (i.IsAdditional = '$isAdditional' AND i.RoadId LIKE '$sectionIdFiler%' AND i.Date LIKE '$dateFlter%') ORDER BY i.Date DESC";
            }
            elseif ($this->isGranted('SUPER_MASTER')) {
                $dql = "SELECT i FROM App:Inspection i WHERE i.SubUnitId = '$subUnitId' AND (i.IsAdditional = '$isAdditional' AND i.RoadId LIKE '$sectionIdFiler%' AND i.Date LIKE '$dateFlter%') ORDER BY i.Date DESC";
            }
            elseif ($this->isGranted('ROAD_MASTER')) {
                $dql = "SELECT i FROM App:Inspection i WHERE i.SubUnitId = '$subUnitId' AND (i.IsAdditional = '$isAdditional' AND i.RoadId LIKE '$sectionIdFiler%' AND i.Date LIKE '$dateFlter%') ORDER BY i.Date DESC";
            }
            elseif($this->isGranted('WORKER') ) {
                $dql = "SELECT i FROM App:Inspection i WHERE i.Username = '$username' AND (i.IsAdditional = '$isAdditional' AND i.RoadId LIKE '$sectionIdFiler%' AND i.Date LIKE '$dateFlter%') ORDER BY i.Date DESC";
            }

            $em = $this->get('doctrine.orm.entity_manager');
            $query = $em->createQuery($dql);
            $paginator  = $this->get('knp_paginator');
            $pagination = $paginator->paginate($query, $request->query->getInt('page', 1),$request->query->getInt('limit', 20));



            return $this->render('inspection/index.html.twig', ['pagination' => $pagination]);
        }
    }

    /**
     * @Route("/new", name="inspection_new", methods="GET|POST")
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
            $subUnitId = $ldapUserRepository->findUnitIdByUserName($userName)->getSubunit()->getId();
            $inspection = new Inspection();
            $inspection->setUsername($this->getUser()->getUserName());
            $inspection->setDate(new \DateTime("now"));
            $inspection->setSubUnitId($subUnitId);
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
    }

    /**
     * @Route("/{id}", name="inspection_show", methods="GET")
     */
    public function show(Inspection $inspection): Response
    {
        $this->denyAccessUnlessGranted('SHOW',$inspection);

        return $this->render('inspection/show.html.twig', ['inspection' => $inspection]);
    }

    /**
     * @Route("/{id}/edit", name="inspection_edit", methods="GET|POST")
     */
    public function edit(Request $request, Inspection $inspection): Response
    {
        $this->denyAccessUnlessGranted('EDIT',$inspection);


        $form = $this->createForm(InspectionType::class, $inspection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('inspection_index', ['id' => $inspection->getId()]);
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
        $this->denyAccessUnlessGranted('DELETE',$inspection);

        if ($this->isCsrfTokenValid('delete'.$inspection->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($inspection);
            $em->flush();
        }

        return $this->redirectToRoute('inspection_index');
    }
}
