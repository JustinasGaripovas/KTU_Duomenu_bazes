<?php

namespace App\Controller;

use App\Entity\Restriction;
use App\Form\RestrictionType;
use App\Repository\LdapUserRepository;
use App\Repository\RestrictionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


/**
 * @Route("/restriction")
 */
class RestrictionController extends Controller
{
    /**
     * @Route("/", name="restriction_index", methods="GET")
     */
    public function index(LdapUserRepository $ldapUserRepository, RestrictionRepository $restrictionRepository, Request $request, AuthorizationCheckerInterface $authChecker): Response
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
            $subUnit = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getName();
            $dql = '';
            if (true === $authChecker->isGranted('ROLE_ADMIN')) {
                $dql = "SELECT r FROM App:Restriction r ORDER BY r.id DESC";
            }
            else {
                $dql = "SELECT r FROM App:Restriction r WHERE r.Subunit = '$subUnit' ORDER BY r.id DESC";
            }
            }

            $em = $this->get('doctrine.orm.entity_manager');
            $query = $em->createQuery($dql);
            $paginator  = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                20/*limit per page*/
            );

        return $this->render('restriction/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * @Route("/new", name="restriction_new", methods="GET|POST")
     */
    public function new(LdapUserRepository $ldapUserRepository, Request $request): Response
    {
        $username = $this->getUser()->getUserName();
        $subUnitName = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getName();
        $restriction = new Restriction();
        $restriction->setSubunit($subUnitName);
        $form = $this->createForm(RestrictionType::class, $restriction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($restriction);
            $em->flush();

            return $this->redirectToRoute('restriction_index');
        }

        return $this->render('restriction/new.html.twig', [
            'restriction' => $restriction,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="restriction_show", methods="GET")
     */
    public function show(Restriction $restriction): Response
    {
        return $this->render('restriction/show.html.twig', ['restriction' => $restriction]);
    }

    /**
     * @Route("/{id}/edit", name="restriction_edit", methods="GET|POST")
     */
    public function edit(LdapUserRepository $ldapUserRepository, Request $request, Restriction $restriction): Response
    {
        $username = $this->getUser()->getUserName();
        $subUnitName = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getName();
        $form = $this->createForm(RestrictionType::class, $restriction);
        $form->get('subunit')->setData($subUnitName);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('restriction_edit', ['id' => $restriction->getId()]);
        }

        return $this->render('restriction/edit.html.twig', [
            'restriction' => $restriction,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="restriction_delete", methods="DELETE")
     */
    public function delete(Request $request, Restriction $restriction): Response
    {
        if ($this->isCsrfTokenValid('delete'.$restriction->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($restriction);
            $em->flush();
        }

        return $this->redirectToRoute('restriction_index');
    }
}
