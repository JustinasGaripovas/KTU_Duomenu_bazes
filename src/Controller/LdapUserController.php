<?php

namespace App\Controller;

use App\Entity\LdapUser;
use App\Form\LdapUserType;
use App\Repository\LdapUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ldap/user")
 */
class LdapUserController extends Controller
{
    /**
     * @Route("/", name="ldap_user_index", methods="GET")
     */
    public function index(LdapUserRepository $ldapUserRepository): Response
    {
        $userName = $this->getUser()->getUserName();
        return $this->render('ldap_user/index.html.twig', ['ldap_users' => $ldapUserRepository->findBy(['name'=>$userName])]);
    }

    /**
     * @Route("/new", name="ldap_user_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $ldapUser = new LdapUser();
        $form = $this->createForm(LdapUserType::class, $ldapUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ldapUser);
            $em->flush();

            return $this->redirectToRoute('ldap_user_index');
        }

        return $this->render('ldap_user/new.html.twig', [
            'ldap_user' => $ldapUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ldap_user_show", methods="GET")
     */
    public function show(LdapUser $ldapUser): Response
    {
        return $this->render('ldap_user/show.html.twig', ['ldap_user' => $ldapUser]);
    }

    /**
     * @Route("/{id}/edit", name="ldap_user_edit", methods="GET|POST")
     */
    public function edit(Request $request, LdapUser $ldapUser): Response
    {
        $form = $this->createForm(LdapUserType::class, $ldapUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ldap_user_edit', ['id' => $ldapUser->getId()]);
        }

        return $this->render('ldap_user/edit.html.twig', [
            'ldap_user' => $ldapUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ldap_user_delete", methods="DELETE")
     */
    public function delete(Request $request, LdapUser $ldapUser): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ldapUser->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ldapUser);
            $em->flush();
        }

        return $this->redirectToRoute('ldap_user_index');
    }
}
