<?php

namespace App\Controller;

use App\Entity\Mechanism;
use App\Form\MechanismType;
use App\Repository\LdapUserRepository;
use App\Repository\MechanismRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/mechanism")
 */
class MechanismController extends AbstractController
{
    /**
     * @Route("/", name="mechanism_index")
     */
    public function index(MechanismRepository $mechanismRepository)
    {
        return $this->render('mechanism/index.html.twig', ['mechanisms' => $mechanismRepository->findAll()]);
    }

    /**
     * @Route("/new", name="mechanism_new", methods="GET|POST")
     */
    public function new(LdapUserRepository $ldapUserRepository, Request $request): Response
    {
        $userName = $this->getUser()->getUserName();
        $subUnitId = $ldapUserRepository->findUnitIdByUserName($userName)->getSubunit()->getId();

        $mechanism = new Mechanism();
        $form = $this->createForm(MechanismType::class, $mechanism);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mechanism);
            $em->flush();

            return $this->redirectToRoute('mechanism_index');
        }

        return $this->render('mechanism/new.html.twig', [
            'mechanism' => $mechanism,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mechanism_show", methods="GET")
     */
    public function show(Mechanism $mechanism): Response
    {
        return $this->render('mechanism/show.html.twig', ['mechanism' => $mechanism]);
    }

    /**
     * @Route("/{id}/edit", name="mechanism_edit", methods="GET|POST")
     */
    public function edit(LdapUserRepository $ldapUserRepository, Request $request, Mechanism $mechanism): Response
    {
        $userName = $this->getUser()->getUserName();
        $subUnitId = $ldapUserRepository->findUnitIdByUserName($userName)->getSubunit()->getId();

        $form = $this->createForm(MechanismType::class, $mechanism);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mechanism_edit', ['id' => $mechanism->getId()]);
        }

        return $this->render('mechanism/edit.html.twig', [
            'mechanism' => $mechanism,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mechanism_delete", methods="DELETE")
     */
    public function delete(Request $request, Mechanism $mechanism): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mechanism->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($mechanism);
            $em->flush();
        }

        return $this->redirectToRoute('mechanism_index');
    }
}
