<?php

namespace App\Controller;

use App\Entity\LdapUser;
use App\Entity\Unit;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    /**
     * @Route("/", name="/", schemes={"https"})
     */
    public function rewriteDefaultPath() {
        return $this->redirectToRoute('main');
    }
    /**
     * @Route("/main", name="main", schemes={"https"})
     */
    public function index()
    {
        if(!$this->getUserNameFromSession()){
            return $this->render('main/index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
        else {
          $this->saveUserInDataBase();
            return $this->render('main/index.html.twig', [
               'controller_name' => 'MainController',
            ]);
        }

    }

    /**
     * @Route("/login", name="login", schemes={"https"})
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {
        if ($this->getUserNameFromSession() === null) {
            // get the login error if there is one
            $error = $authUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authUtils->getLastUsername();
            return $this->render('test/login.html.twig', array(
                'last_username' => $lastUsername,
                'error' => $error,
            ));
        } else {
            return $this->redirectToRoute('main');
        }
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }

    /**
     * @Route("/admin/users", name="/admin/users")
     */

    public function saveUserInDataBase(){
        if ($this->chechIfUserExistInDatabase($this->getUserNameFromSession()) === false) {
            $em = $this->getDoctrine()->getManager();
            $unit = $this->getDoctrine()->getRepository('App:Unit')->findOneBy(array('UnitId'=>0));
            $ldapUser = new LdapUser();
            $ldapUser->setName($this->getUserNameFromSession());
            $ldapUser->setUnit($unit);

            $em -> persist($ldapUser);
            $em -> flush();

            return $this->redirectToRoute('main');
        }
        else {
            return $this->redirectToRoute('main');
        }
    }
    public function chechIfUserExistInDatabase($userName){

        $em = $this->getDoctrine()->getRepository('App:LdapUser');
        $ldapUser = $em->findBy(array('name' => $userName));
        if(!$ldapUser) {
            return false;
        }
        else {
            return true;
        }
    }
    public function getUserNameFromSession() {

        if (!$this->getUser()){
            return null;
        }
        else {
            return $this->getUser()->getUserName();
        }
    }
}
