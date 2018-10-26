<?php

namespace App\Controller;

use App\Entity\FloodedRoads;
use App\Form\FloodedRoadsType;
use App\Repository\FloodedRoadsRepository;
use App\Repository\LdapUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/flooded/roads")
 */
class FloodedRoadsController extends Controller
{
    /**
     * @Route("/", name="flooded_roads_index", methods="GET")
     */
    public function index(LdapUserRepository $ldapUserRepository ,FloodedRoadsRepository $floodedRoadsRepository, Request $request): Response
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

            $subUnitId = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getName();

            $em2 = $this->get('doctrine.orm.entity_manager');

            if ($this->isGranted('ADMIN')) {
                $dql = "SELECT f FROM App:FloodedRoads f ORDER BY f.CreatedAt DESC";
            }
            elseif ($this->isGranted('SUPER_VIEWER')){
                $dql = "SELECT f FROM App:FloodedRoads f ORDER BY f.CreatedAt DESC";
            }
            elseif ($this->isGranted('UNIT_VIEWER')) {
                $dql = "SELECT f FROM App:FloodedRoads f WHERE f.SubunitId = '$subUnitId' ORDER BY f.CreatedAt DESC";
            }
            elseif ($this->isGranted('SUPER_MASTER')) {
                $dql = "SELECT f FROM App:FloodedRoads f WHERE f.SubunitId = '$subUnitId' ORDER BY f.CreatedAt DESC";
            }
            elseif ($this->isGranted('ROAD_MASTER')) {
                $dql = "SELECT f FROM App:FloodedRoads f WHERE f.SubunitId = '$subUnitId' ORDER BY f.CreatedAt DESC";
            }
            elseif($this->isGranted('WORKER') ) {
                $dql = "SELECT f FROM App:FloodedRoads f WHERE f.CreatedBy = '$username' ORDER BY f.CreatedAt DESC";
            }

            $query = $em2->createQuery($dql);
            $paginator  = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                20/*limit per page*/
        );

        return $this->render('flooded_roads/index.html.twig', ['flooded_roads' => $pagination]);
    }
    }

    /**
     * @Route("/new", name="flooded_roads_new", methods="GET|POST")
     */
    public function new(LdapUserRepository $ldapUserRepository,Request $request): Response
    {
        $userName = $this->getUser()->getUserName();
        $subUnitId = $ldapUserRepository->findUnitIdByUserName($userName)->getSubunit()->getName();
        $floodedRoad = new FloodedRoads();
        $floodedRoad->setCreatedBy($userName);
        $floodedRoad->setSubunitId($subUnitId);
        $floodedRoad->setCreatedAt(new \DateTime('now'));
        $form = $this->createForm(FloodedRoadsType::class, $floodedRoad);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($floodedRoad);
            $em->flush();

            return $this->redirectToRoute('flooded_roads_index');
        }

        return $this->render('flooded_roads/new.html.twig', [
            'flooded_road' => $floodedRoad,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="flooded_roads_show", methods="GET")
     */
    public function show(FloodedRoads $floodedRoad): Response
    {
        $this->denyAccessUnlessGranted('SHOW', $floodedRoad);

        return $this->render('flooded_roads/show.html.twig', ['flooded_road' => $floodedRoad]);
    }

    /**
     * @Route("/{id}/edit", name="flooded_roads_edit", methods="GET|POST")
     */
    public function edit(Request $request, FloodedRoads $floodedRoad): Response
    {
        $this->denyAccessUnlessGranted('EDIT', $floodedRoad);

        $form = $this->createForm(FloodedRoadsType::class, $floodedRoad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('flooded_roads_edit', ['id' => $floodedRoad->getId()]);
        }

        return $this->render('flooded_roads/edit.html.twig', [
            'flooded_road' => $floodedRoad,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="flooded_roads_delete", methods="DELETE")
     */
    public function delete(Request $request, FloodedRoads $floodedRoad): Response
    {
        $this->denyAccessUnlessGranted('DELETE', $floodedRoad);

        if ($this->isCsrfTokenValid('delete'.$floodedRoad->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($floodedRoad);
            $em->flush();
        }

        return $this->redirectToRoute('flooded_roads_index');
    }
}
