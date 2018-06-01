<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class TestController extends Controller
{

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Route("/test", name="test")
     */
    public function index()
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('noreply@keliuprieziura.lt')
            ->setTo('darius.zvirblis@keliuprieziura.lt')
            ->setBody('test',
                'text/html'
            );

        $this->get('mailer')->send($message);

    }

    /**
    public function login(Request $request, AuthenticationUtils $authUtils)
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('test/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    */
    /**
     * @Route("/admin", name="admin")
     *
     */
    public function admin()
    {

        $ldap = Ldap::create('ext_ldap', array(
            'host' => '192.168.192.10',
            'encryption' => 'none',
        ));
        if(!$ldap){
            echo "error";
        }
        else{

           $ad = ldap_connect("ldap://192.168.192.10");
            ldap_set_option($ad, LDAP_OPT_REFERRALS, 0);
            ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
           @ldap_bind($ad,'cn=testas testas,cn=Users,dc=KP,dc=local', 'ZXCvbn123++');

           //$this->getDN($ad, 'testas.testas', 'cn=Users, dc=KP,dc=local');
           // var_dump(ldap_search($ad,"DC=KP,DC=local",'(&(objectCategory=person)(samaccountname=*))',array('samaccountname')));
           //LDAP recursive search

            //var_dump($this->getDN($ad, $this->getUser()->getUserName(),'dc=KP,dc=local'));

           //var_dump($this->checkGroupEx($ad,  $this->getDN($ad, $this->getUser()->getUserName(), 'dc=KP,dc=local'), "ADMD.admins"));

           $isMemberOfGroup = $this->checkGroupEx($ad,  $this->getDN($ad, $this->getUser()->getUserName(), 'dc=KP,dc=local'), "ADMD.admins");

           //var_dump($isMemberOfGroup);

           if ($isMemberOfGroup === true) {
               $userName = $this->getUser()->getUserName();
               $this->tokenStorage->setToken(new UsernamePasswordToken(new User($userName, null), null, 'main', array('ROLE_ADMIN')));

           }

        }

        return $this->render('test/admin.html.twig');
    }

    /**
     * @Route("/admin/check", name="admin/check")
     *
     */
    public function getAdminRole(AuthorizationCheckerInterface $authChecker) {
        if (false === $authChecker->isGranted('ROLE_ADMIN')) {
            var_dump("ne adminas");
        }

        else {

            var_dump('adminas');
        }
    }


    function checkGroupEx($ad, $userdn, $groupdn) {
        $attributes = array('memberof');
        $result = ldap_read($ad, $userdn, '(objectclass=*)', $attributes);
        if ($result === FALSE) { return FALSE; };
        $entries = ldap_get_entries($ad, $result);
        if ($entries['count'] <= 0) {
            var_dump($entries);
            return FALSE; };
        if (empty($entries[0]['memberof'])) { return FALSE; } else {
            for ($i = 0; $i < $entries[0]['memberof']['count']; $i++) {
                if ($this->getCN($entries[0]['memberof'][$i]) == $groupdn) { return TRUE; }
                elseif ($this->checkGroupEx($ad, $entries[0]['memberof'][$i], $groupdn)) { return TRUE; };
            };
        };
        return FALSE;
    }


    function getCN($dn) {
        preg_match('/[^,]*/', $dn, $matchs, PREG_OFFSET_CAPTURE, 3);
        return $matchs[0][0];
    }


    function getDN($ad, $samaccountname, $basedn) {

        $attributes = array('dn');
        //$attributes = array("displayname", "mail", "samaccountname");
        $result = ldap_search($ad, $basedn, "(sAMAccountName=$samaccountname)", $attributes);
        if ($result === FALSE) {
            return '';
        }

        $entries = ldap_get_entries($ad, $result);

        if ($entries['count']>0) {
            return $entries[0]['dn'];
        }

        else {
            return '';
        }
    }

}