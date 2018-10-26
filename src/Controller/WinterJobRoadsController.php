<?php

namespace App\Controller;

use App\Entity\WinterJobRoads;
use App\Form\WinterJobRoads1Type;
use App\Repository\LdapUserRepository;
use App\Repository\WinterJobRoadsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/winter/job/roads")
 * @IsGranted("WINTER")
 */
class WinterJobRoadsController extends Controller
{
    /**
     * @Route("/", name="winter_job_roads_index", methods="GET")
     */
    public function index(WinterJobRoadsRepository $winterJobRoadsRepository, LdapUserRepository $ldapUserRepository): Response
    {
        $username = $this->getUser()->getUserName();
        if (!$ldapUserRepository->findUnitIdByUserName($username)->getSubunit()) {
            $this->addFlash(
                'danger',
                'Jūs nepasirinkęs kelių tarnybos!'
            );
            return $this->redirectToRoute('ldap_user_index');
        }

        return $this->render('winter_job_roads/index.html.twig', ['winter_job_roads' => $winterJobRoadsRepository->findAll()]);
    }

    /**
     * @Route("/new", name="winter_job_roads_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $winterJobRoad = new WinterJobRoads();
        $form = $this->createForm(WinterJobRoads1Type::class, $winterJobRoad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($winterJobRoad);
            $em->flush();

            return $this->redirectToRoute('winter_job_roads_index');
        }

        return $this->render('winter_job_roads/new.html.twig', [
            'winter_job_road' => $winterJobRoad,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="winter_job_roads_show", methods="GET")
     */
    public function show(WinterJobRoads $winterJobRoad): Response
    {
        return $this->render('winter_job_roads/show.html.twig', ['winter_job_road' => $winterJobRoad]);
    }

    /**
     * @Route("/{id}/edit", name="winter_job_roads_edit", methods="GET|POST")
     */
    public function edit(Request $request, WinterJobRoads $winterJobRoad): Response
    {
        $form = $this->createForm(WinterJobRoads1Type::class, $winterJobRoad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('winter_job_roads_edit', ['id' => $winterJobRoad->getId()]);
        }

        return $this->render('winter_job_roads/edit.html.twig', [
            'winter_job_road' => $winterJobRoad,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="winter_job_roads_delete", methods="DELETE")
     */
    public function delete(Request $request, WinterJobRoads $winterJobRoad): Response
    {
        if ($this->isCsrfTokenValid('delete'.$winterJobRoad->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($winterJobRoad);
            $em->flush();
        }

        return $this->redirectToRoute('winter_job_roads_index');
    }
}
