<?php

namespace App\Controller;

use App\Entity\InsuredEvent;
use App\Form\InsuredEventType;
use App\Repository\InsuredEventRepository;
use App\Repository\LdapUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/insured/event")
 */
class InsuredEventController extends Controller
{
    /**
     * @Route("/", name="insured_event_index", methods="GET")
     */
    public function index(LdapUserRepository $ldapUserRepository, InsuredEventRepository $insuredEventRepository, Request $request): Response
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
            $dql = '';
                $dql = "SELECT ie FROM App:InsuredEvent ie ORDER BY ie.id DESC";

            //
            $query = $em->createQuery($dql);
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                20/*limit per page*/
            );
        }
        return $this->render('insured_event/index.html.twig', ['insured_events' => $pagination]);
    }

    /**
     * @Route("/new", name="insured_event_new", methods="GET|POST")
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
     * @Route("/{id}", name="insured_event_show", methods="GET")
     */
    public function show(InsuredEvent $insuredEvent): Response
    {
        return $this->render('insured_event/show.html.twig', ['insured_event' => $insuredEvent]);
    }

    /**
     * @Route("/{id}/edit", name="insured_event_edit", methods="GET|POST")
     */
    public function edit(Request $request, InsuredEvent $insuredEvent): Response
    {
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
        if ($this->isCsrfTokenValid('delete'.$insuredEvent->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($insuredEvent);
            $em->flush();
        }

        return $this->redirectToRoute('insured_event_index');
    }
}
