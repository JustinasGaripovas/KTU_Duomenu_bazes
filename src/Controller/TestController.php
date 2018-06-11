<?php

namespace App\Controller;

use App\Repository\LdapUserRepository;
use PhpOffice\PhpSpreadsheet\Calculation\DateTime;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;




class TestController extends Controller
{

    public function __construct( LdapUserRepository $ldapUserRepository ,TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        $this->ldapUseRepository = $ldapUserRepository;
    }


    public function checkIfUserHasSubunitId() {

        if (!$this->ldapUseRepository->findUnitIdByUserName($this->getUser()->getUserName())) {
            return false;
        }
        else {
            return true;
        }
    }

    /**
     * @Route("/test", name="test")
     */
    public function index()
    {
        $fileName = md5($this->getUser()->getUserName() . microtime());
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load('job_tmpl_3.xlsx');
// Set document properties
        $spreadsheet->getProperties()->setCreator($this->getUser()->getUserName())
            ->setLastModifiedBy('VĮ Kelių priežiūra')
            ->setTitle('Atliktų darbų ataskaita')
            ->setSubject('Atliktų darbų ataskaita')
            ->setDescription('Atliktų darbų ataskaita')
            ->setKeywords('Atliktų darbų ataskaita')
            ->setCategory('Atliktų darbų ataskaita');

            $em = $this->get('doctrine.orm.entity_manager');
            $dql = "SELECT i FROM App:DoneJobs i";
            $query = $em->createQuery($dql);
            $report = $query->execute();
            $index = 3;
            $styleArray = [  'font' => [ 'bold' => false ] ];
            $styleArrayHeader = [  'font' => [ 'bold' => true ] ];
            foreach ($report as $rep) {
            $spreadsheet->getActiveSheet()->insertNewRowBefore($index, 1);
            $spreadsheet->getActiveSheet()->setCellValue('F'.$index, $rep->getJobId());
            $spreadsheet->getActiveSheet()->setCellValue('G'.$index, $rep->getJobName());
            $spreadsheet->getActiveSheet()->setCellValue('H'.$index, $rep->getUnitOf());
            $spreadsheet->getActiveSheet()->setCellValue('I'.$index, $rep->getQuantity());
            $spreadsheet->getActiveSheet()->setCellValue('B'.$index, $rep->getDoneJobDate()->format('Y-m-d'));
            $spreadsheet->getActiveSheet()
                ->setCellValue('D'.$index, $rep->getSectionId().'('.$rep->getRoadSectionBegin().'-'.$rep->getRoadSectionEnd().')');
                $spreadsheet->getActiveSheet()
                    ->getRowDimension($index)
                    ->setRowHeight(40);
                $spreadsheet->getActiveSheet()
                    ->getColumnDimension('G')->setWidth(40);
                $spreadsheet->getActiveSheet()
                    ->getStyle($index)
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $spreadsheet->getActiveSheet()
                    ->getStyle($index)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $spreadsheet->getActiveSheet()
                    ->getStyle($index)
                    ->getAlignment()
                    ->setWrapText(true);
                $spreadsheet->getActiveSheet()->getStyle('A'.$index)->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('B'.$index)->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('C'.$index)->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('D'.$index)->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('E'.$index)->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('F'.$index)->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('G'.$index)->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('H'.$index)->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('I'.$index)->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('J'.$index)->applyFromArray($styleArray);
                $index ++;
            }
            $spreadsheet->getActiveSheet()->removeRow($index,1);
        //$spreadsheet->getActiveSheet()->setCellValue('A1', $report[0]);
            // Set page orientation and size
        $spreadsheet->getActiveSheet()
            ->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $spreadsheet->getActiveSheet()
            ->getPageSetup()
            ->setPaperSize(PageSetup::PAPERSIZE_A4);

// Rename worksheet


        return $this->file($fileName.'.xlsx');

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

           //$isMemberOfGroup = $this->checkGroupEx($ad,  $this->getDN($ad, $this->getUser()->getUserName(), 'dc=KP,dc=local'), "DAIS.admins");

           //var_dump($isMemberOfGroup);
            $userName = $this->getUser()->getUserName();
            if($this->checkGroupEx($ad,  $this->getDN($ad, $this->getUser()->getUserName(), 'dc=KP,dc=local'), "DAIS.road_master") === true ){
                $this->tokenStorage->setToken(new UsernamePasswordToken(new User($userName, null), null, 'main', array('ROLE_ROAD_MASTER')));
            }
            if ($this->checkGroupEx($ad,  $this->getDN($ad, $this->getUser()->getUserName(), 'dc=KP,dc=local'), "DAIS.super_master") === true ){
                $this->tokenStorage->setToken(new UsernamePasswordToken(new User($userName, null), null, 'main', array('ROLE_SUPER_MASTER')));
            }
            if ($this->checkGroupEx($ad,  $this->getDN($ad, $this->getUser()->getUserName(), 'dc=KP,dc=local'), "DAIS.unit_viewer") === true ){
                $this->tokenStorage->setToken(new UsernamePasswordToken(new User($userName, null), null, 'main', array('ROLE_UNIT_VIEWER')));
            }
            if ($this->checkGroupEx($ad,  $this->getDN($ad, $this->getUser()->getUserName(), 'dc=KP,dc=local'), "DAIS.super_viewer") === true ){
                $this->tokenStorage->setToken(new UsernamePasswordToken(new User($userName, null), null, 'main', array('ROLE_SUPER_VIEWER')));
            }
            if ($this->checkGroupEx($ad,  $this->getDN($ad, $this->getUser()->getUserName(), 'dc=KP,dc=local'), "DAIS.admins") === true) {
               $this->tokenStorage->setToken(new UsernamePasswordToken(new User($userName, null), null, 'main', array('ROLE_ADMIN')));
            }
            if ($this->checkGroupEx($ad,  $this->getDN($ad, $this->getUser()->getUserName(), 'dc=KP,dc=local'), "DAIS.worker") === true){
                $this->tokenStorage->setToken(new UsernamePasswordToken(new User($userName, null), null, 'main', array('ROLE_WORKER')));
            }
        }

        return $this->redirectToRoute('main');
    }

    /**
     * @Route("/admin/check", name="admin/check")
     *
     */
    public function getAdminRole() {
        return $this->render('edit_view_done_job.html.twig');
    }


    function checkGroupEx($ad, $userdn, $groupdn) {
        $attributes = array('memberof');
        $result = ldap_read($ad, $userdn, '(objectclass=*)', $attributes);
        if ($result === FALSE) {
            return FALSE;
        };
        $entries = ldap_get_entries($ad, $result);
        if ($entries['count'] <= 0) {
            return FALSE; };
        if (empty($entries[0]['memberof'])) {
            return FALSE;
        } else {
            for ($i = 0; $i < $entries[0]['memberof']['count']; $i++) {
                if ($this->getCN($entries[0]['memberof'][$i]) == $groupdn) {
                    return TRUE;
                }
                elseif ($this->checkGroupEx($ad, $entries[0]['memberof'][$i], $groupdn)) {
                    return TRUE;
                };
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