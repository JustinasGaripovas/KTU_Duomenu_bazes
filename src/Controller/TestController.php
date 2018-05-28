<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\Authentication\SimpleAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class TestController extends Controller
{
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
        //$user = 'darius.zvirblis';
        //var_dump('----------------------------------------------------------------------------------');
        //$sc = "admddarizvi";
        //$session = new Session();

        //var_dump($this->user='admddarizvi');


       /* if ($sc == 'admddarizvi') {
            //$sc = $this->getUser()->getUserName();
            $test_user = new User("admddarizvi",null,array('ROLE_USER'),true,true,true,true);
            $token = new UsernamePasswordToken("admddarizvi", $test_user, 'main', array('ROLE_ADMIN'));
            var_dump($session->get('_security_main'));
            var_dump("............................................................................");
            $session->set('_security_main', serialize($token));
            $session->save();
            var_dump($session->get("_security_main",'username'));
        }

        var_dump($this->getUser()->getUsername());*/

        $ldap = Ldap::create('ext_ldap', array(
            'host' => '192.168.192.10',
            'encryption' => 'none',
        ));
        if(!$ldap){
            echo "error";
        }
        else{

            $ad = ldap_connect("ldap://192.168.192.10");
           @ldap_bind($ad,'cn=testas testas,cn=Users,dc=KP,dc=local', 'ZXCvbn123++');

           $this->getDN($ad,"darius.zvirblis", "dc=KP,dc=local");
           var_dump($this->getDN($ad,"darius.zvirblis", "dc=KP,dc=local"));

           //LDAP recursive search
           $ch = $this->checkGroupEx($ad, "cn=Darius Zvirblis, OU=Administracija ,dc=KP,dc=local", "CN=KN-CB-Bendri,OU=Groups,OU=Administracija,DC=KP,DC=local");

           var_dump($ch);
           //end of LDAP recursive search
           /* $query = $ldap->query('dc=KP,dc=local', '(&(objectclass=User)(sAMAccountName=darius.zvirblis))');
            $results = $query->execute();
            if (!$results){
                var_dump("error");
            }
            else{
                var_dump($results);

            }*/
        }

        //var_dump($this->getUser());
        //$em = $this->getDoctrine()->getManager();
        //$user=new Test2();
        //$user->setName($this->getUser()->getUsername());
        //$user->setRoles('ROLE_ADMIN');
        //$em->persist($user);
        //$em->flush();


        //$em=$this->getDoctrine()->getManager()->getRepository(Test2::class);
        //$user2=$em->findBy(['Name' => $this->getUser()->getUsername()]);
        //var_dump($user2);

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
        var_dump($entries);
        if ($entries['count'] <= 0) {
            var_dump($entries['count']);
            return FALSE; };
        if (empty($entries[0]['memberof'])) { return FALSE; } else {
            for ($i = 0; $i < $entries[0]['memberof']['count']; $i++) {
                if ($entries[0]['memberof'][$i] == $groupdn) { return TRUE; }
                elseif ($this->checkGroupEx($ad, $entries[0]['memberof'][$i], $groupdn)) { return TRUE; };
            };
        };
        return FALSE;
    }

    function getDN($ad, $samaccountname, $basedn) {
        $attributes = array('dn');
        $result = ldap_search($ad, $basedn,
            "(samaccountname={$samaccountname})", $attributes);
        if ($result === FALSE) { return ''; }
        $entries = ldap_get_entries($ad, $result);
        if ($entries['count']>0) { return $entries[0]['dn']; }
        else { return ''; }
    }

}