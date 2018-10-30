<?php

namespace App\Controller;

use App\Entity\InsuredEvent;
use App\Form\InsuredEventType;
use App\Form\InsuredEventTypeEdit;
use App\Repository\InsuredEventRepository;
use App\Repository\LdapUserRepository;
use App\Repository\SubunitRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("INSURED")
 */
class InsuredEventController extends Controller
{
    /**
     * @Route("insured/event/", name="insured_event_index", methods="GET")
     */
    public function index(LdapUserRepository $ldapUserRepository, InsuredEventRepository $insuredEventRepository, Request $request, SubunitRepository $subunitRepository): Response
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

            $subunitName = $ldapUserRepository->findUnitIdByUserName($userName)->getSubunit()->getName();

            $dql = '';

            if ($this->isGranted('ADMIN')) {
                $dql = "SELECT ie FROM App:InsuredEvent ie WHERE (ie.isInsuredType = 1) ORDER BY ie.id DESC";
            }
            elseif ($this->isGranted('SUPER_VIEWER')){
                $dql = "SELECT ie FROM App:InsuredEvent ie WHERE (ie.isInsuredType = 1) ORDER BY ie.id DESC";
            }
            elseif ($this->isGranted('UNIT_VIEWER')) {
                $dql = "SELECT ie FROM App:InsuredEvent ie WHERE (ie.isInsuredType = 1) AND ie.Subunit = '$subunitName' ORDER BY ie.id DESC";
            }
            elseif ($this->isGranted('SUPER_MASTER')) {
                $dql = "SELECT ie FROM App:InsuredEvent ie WHERE (ie.isInsuredType = 1) AND ie.Subunit = '$subunitName' ORDER BY ie.id DESC";
            }
            elseif ($this->isGranted('ROAD_MASTER')) {
                $dql = "SELECT ie FROM App:InsuredEvent ie WHERE (ie.isInsuredType = 1) AND ie.Subunit = '$subunitName' ORDER BY ie.id DESC";
            }
            elseif($this->isGranted('WORKER') ) {
                $dql = "SELECT ie FROM App:InsuredEvent ie WHERE (ie.isInsuredType = 1) AND ie.Subunit = '$subunitName' ORDER BY ie.id DESC";
            }


            //
            $query = $em->createQuery($dql);
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                20/*limit per page*/
            );
        }
        return $this->render('insured_event/index.html.twig', [
            'insured_events' => $pagination,
            'insured' => true,
        ]);
    }

    /**
     * @Route("uninsured/event/", name="uninsured_event_index", methods="GET")
     */
    public function indexUninsured(LdapUserRepository $ldapUserRepository, InsuredEventRepository $insuredEventRepository, Request $request, SubunitRepository $subunitRepository): Response
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

            $subunitName = $ldapUserRepository->findUnitIdByUserName($userName)->getSubunit()->getName();

            $dql = '';

            if ($this->isGranted('ADMIN')) {
                $dql = "SELECT ie FROM App:InsuredEvent ie WHERE (ie.isInsuredType = 0) ORDER BY ie.id DESC";
            }
            elseif ($this->isGranted('SUPER_VIEWER')){
                $dql = "SELECT ie FROM App:InsuredEvent ie WHERE (ie.isInsuredType = 0) ORDER BY ie.id DESC";
            }
            elseif ($this->isGranted('UNIT_VIEWER')) {
                $dql = "SELECT ie FROM App:InsuredEvent ie WHERE (ie.isInsuredType = 0) AND ie.Subunit = '$subunitName' ORDER BY ie.id DESC";
            }
            elseif ($this->isGranted('SUPER_MASTER')) {
                $dql = "SELECT ie FROM App:InsuredEvent ie WHERE (ie.isInsuredType = 0) AND ie.Subunit = '$subunitName' ORDER BY ie.id DESC";
            }
            elseif ($this->isGranted('ROAD_MASTER')) {
                $dql = "SELECT ie FROM App:InsuredEvent ie WHERE (ie.isInsuredType = 0) AND ie.Subunit = '$subunitName' ORDER BY ie.id DESC";
            }
            elseif($this->isGranted('WORKER') ) {
                $dql = "SELECT ie FROM App:InsuredEvent ie WHERE (ie.isInsuredType = 0) AND ie.Subunit = '$subunitName' ORDER BY ie.id DESC";
            }
            //
            $query = $em->createQuery($dql);
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                20/*limit per page*/
            );
        }
        return $this->render('insured_event/index.html.twig', [
            'insured_events' => $pagination,
            'insured' => false,
        ]);
    }

    /**
     * @Route("uninsured/event/new", name="uninsured_event_new", methods="GET|POST")
     */
    public function newUninsured(LdapUserRepository $ldapUserRepository ,Request $request): Response
    {
        $userName = $this->getUser()->getUserName();
        $subUnitName = $ldapUserRepository->findUnitIdByUserName($userName)->getSubunit()->getName();
        $insuredEvent = new InsuredEvent();
        $insuredEvent->setSubunit($subUnitName);
        $form = $this->createForm(InsuredEventType::class, $insuredEvent);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->persist($insuredEvent);
            $em->flush();

            return $this->redirectToRoute('uninsured_event_index');
        }

        return $this->render('insured_event/newUninsured.html.twig', [
            'insured_event' => $insuredEvent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("insured/event/new", name="insured_event_new", methods="GET|POST")
     */
    public function new(LdapUserRepository $ldapUserRepository ,Request $request): Response
    {
        $userName = $this->getUser()->getUserName();
        $subUnitName = $ldapUserRepository->findUnitIdByUserName($userName)->getSubunit()->getName();
        $insuredEvent = new InsuredEvent();
        $insuredEvent->setSubunit($subUnitName);
        $form = $this->createForm(InsuredEventType::class, $insuredEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($insuredEvent);
            $em->flush();

            return $this->redirectToRoute('insured_event_index');
        }

        return $this->render('insured_event/new.html.twig', [
            'insured_event' => $insuredEvent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("insured/event/{id}", name="insured_event_show", methods="GET")
     */
    public function show(InsuredEvent $insuredEvent): Response
    {
        $this->denyAccessUnlessGranted('SHOW',$insuredEvent);

        return $this->render('insured_event/show.html.twig', ['insured_event' => $insuredEvent]);
    }

    /**
     * @Route("insured/event/{id}/add", name="insured_event_add", methods="GET|POST")
     */
    public function add(Request $request, InsuredEvent $insuredEvent): Response
    {
        $this->denyAccessUnlessGranted('SHOW',$insuredEvent);

        $form = $this->createForm(InsuredEventTypeEdit::class, $insuredEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('insured_event_index');
        }

        return $this->render('insured_event/add.html.twig', [
            'insured_event' => $insuredEvent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("uninsured/event/{id}/edit", name="uninsured_event_edit", methods="GET|POST")
     */
    public function editUninsured(Request $request, InsuredEvent $insuredEvent): Response
    {
        $this->denyAccessUnlessGranted('EDIT',$insuredEvent);

        $form = $this->createForm(InsuredEventType::class, $insuredEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('uninsured_event_index');
        }

        return $this->render('insured_event/editUninsured.html.twig', [
            'insured_event' => $insuredEvent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("insured/event/{id}/edit", name="insured_event_edit", methods="GET|POST")
     */
    public function edit(Request $request, InsuredEvent $insuredEvent): Response
    {
        $this->denyAccessUnlessGranted('EDIT',$insuredEvent);

        $form = $this->createForm(InsuredEventType::class, $insuredEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('insured_event_index');
        }

        return $this->render('insured_event/edit.html.twig', [
            'insured_event' => $insuredEvent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="insured_event_delete", methods="DELETE")
     */
    public function delete(Request $request, InsuredEvent $insuredEvent): Response
    {
        $this->denyAccessUnlessGranted('DELETE',$insuredEvent);

        if ($this->isCsrfTokenValid('delete'.$insuredEvent->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($insuredEvent);
            $em->flush();
        }

        return $this->redirectToRoute('insured_event_index');
    }
}
