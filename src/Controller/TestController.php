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
    public function admin(SessionInterface $session, UserInterface $user)
    {
        var_dump('----------------------------------------------------------------------------------');
        //$sc = "admddarizvi";
        //$session = new Session();

        var_dump($this->user='admddarizvi');


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

        /*$ldap = Ldap::create('ext_ldap', array(
            'host' => '192.168.192.10',
            'encryption' => 'none',
        ));
        if(!$ldap){
            echo "error";
        }
        else{

            $ldap->bind('cn=testas testas,cn=Users,dc=DAIS,dc=local', 'ZXCvbn123++');
            $query = $ldap->query('dc=DAIS,dc=local', '(&(objectCategory=person)(objectclass=User)(sAMAccountName=darius.zvirblis))');
            $results = $query->execute();
            if (!$results){
                var_dump("error");
            }
            else{

            }
        }*/

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
}