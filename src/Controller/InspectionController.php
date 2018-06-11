<?php

namespace App\Controller;

use App\Entity\DoneJobs;
use App\Controller\TestController;
use App\Entity\Inspection;
use App\Form\InspectionType;
use App\Repository\InspectionRepository;
use App\Repository\LdapUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


/**
 * @Route("/inspection")
 */
class InspectionController extends Controller
{
    /**
     * @Route("/", name="inspection_index", methods="GET")
     */

    public function index(TestController $testController ,LdapUserRepository $ldapUserRepository, InspectionRepository $inspectionRepository, Request $request, AuthorizationCheckerInterface $authChecker): Response
    {
        if ($testController->checkIfUserHasSubunitId() != true) {
            $this->addFlash(
                'notice',
                'Jūs nepasirinkęs kelių tarnybos!'
            );
            return $this->redirectToRoute('ldap_user_index');
        }
        else {

            $username = $this->getUser()->getUserName();
            $subUnitId = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getId();
            $dql = '';
            if (true === $authChecker->isGranted('ROLE_ADMIN')) {
                $dql = "SELECT i FROM App:Inspection i ORDER BY i.id DESC";
            }
            elseif (true === $authChecker->isGranted('ROLE_SUPER_VIEWER')){
                $dql = "SELECT i FROM App:Inspection i ORDER BY i.id DESC";
            }
            elseif (true === $authChecker->isGranted('ROLE_KT_VIEWER')) {
                $dql = "SELECT i FROM App:Inspection i WHERE i.SubUnitId = '$subUnitId' ORDER BY i.Date DESC";
            }
            elseif (true === $authChecker->isGranted('ROLE_KT_MASTER')) {
                $dql = "SELECT i FROM App:Inspection i WHERE i.SubUnitId = '$subUnitId' ORDER BY i.Date DESC";
            }
            elseif (true === $authChecker->isGranted('ROLE_ROAD_MASTER')) {
                $dql = "SELECT i FROM App:Inspection i WHERE i.SubUnitId = '$subUnitId' ORDER BY i.Date DESC";
            }
            elseif(true === $authChecker->isGranted('ROLE_WORKER') ) {
                $dql = "SELECT i FROM App:Inspection i WHERE i.Username = '$username' ORDER BY i.Date DESC";
            }
            $em = $this->get('doctrine.orm.entity_manager');
            $query = $em->createQuery($dql);
            $paginator  = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                20/*limit per page*/
            );

            return $this->render('inspection/index.html.twig', ['pagination' => $pagination]);
        }
        }

    /**
     * @Route("/new", name="inspection_new", methods="GET|POST")
     */

    public function new(TestController $testController, LdapUserRepository $ldapUserRepository, Request $request): Response
    {
        $testController->checkIfUserHasSubunitId();

        $userName = $this->getUser()->getUserName();
        $subUnitId = $ldapUserRepository->findUnitIdByUserName($userName)->getSubunit()->getId();
        $inspection = new Inspection();
        $inspection -> setUsername($this->getUser()->getUserName());
        $inspection -> setDate(new \DateTime("now"));
        $inspection -> setSubUnitId($subUnitId);
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

    /**
     * @Route("/{id}", name="inspection_show", methods="GET")
     */
    public function show(Inspection $inspection): Response
    {
        return $this->render('inspection/show.html.twig', ['inspection' => $inspection]);
    }

    /**
     * @Route("/{id}/edit", name="inspection_edit", methods="GET|POST")
     */
    public function edit(Request $request, Inspection $inspection): Response
    {
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
        if ($this->isCsrfTokenValid('delete'.$inspection->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($inspection);
            $em->flush();
        }

        return $this->redirectToRoute('inspection_index');
    }
}
