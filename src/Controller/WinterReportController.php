<?php

namespace App\Controller;

use App\Entity\RoadSectionForWinterJobs;
use App\Entity\WinterJobRoadSection;
use App\Entity\WinterJobs;
use App\Form\ReportType;
use App\Form\WinterJobsReportType;
use App\Repository\LdapUserRepository;
use App\Utils\MaterialReportObjectForSubunit;
use App\Utils\WinterDoneJobsObjectForType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Utils\MaterialReportObject;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

/**
 * @IsGranted("WINTER")
 */
class WinterReportController extends Controller
{
    /**
     * @Route("/winter/report/jobs", name="winter_report_jobs")
     */
    public function winterMaintenanceReportJobs (LdapUserRepository $ldapUserRepository, Request $request, AuthorizationCheckerInterface $authChecker)
    {

        $username = $this->getUser()->getUserName();

        if (!$ldapUserRepository->findUnitIdByUserName($username)->getSubunit()) {
            $this->addFlash(
                'danger',
                'Jūs nepasirinkęs kelių tarnybos!'
            );
            return $this->redirectToRoute('ldap_user_index');
        }

        $form = $this->createForm(WinterJobsReportType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $from = $form->get('From')->getData();
            $to = $form->get('To')->getData();
            $dql = '';

            $subunit = $ldapUserRepository->findUnitIdByUserName($this->getUser()->getUserName())->getSubunit()->getSubunitId();

            if ($this->isGranted('ADMIN')) {
                $dql = "SELECT r FROM App:WinterJobs r WHERE (r.Date >= '$from' AND r.Date <= '$to')  ORDER BY r.Date ASC";
            } elseif ($this->isGranted('SUPER_VIEWER')) {
                $dql = "SELECT r FROM App:WinterJobs r WHERE (r.Date >= '$from' AND r.Date <= '$to')  ORDER BY r.Date ASC";
            } elseif ($this->isGranted('UNIT_VIEWER')) {
                $dql = "SELECT r FROM App:WinterJobs r WHERE (r.Date >= '$from' AND r.Date <= '$to') AND r.Subunit = '$subunit'  ORDER BY r.Date ASC";
            } elseif ($this->isGranted('SUPER_MASTER')) {
                $dql = "SELECT r FROM App:WinterJobs r WHERE (r.Date >= '$from' AND r.Date <= '$to') AND r.Subunit = '$subunit'  ORDER BY r.Date ASC";
            } elseif ($this->isGranted('ROAD_MASTER')) {
                $dql = "SELECT r FROM App:WinterJobs r WHERE (r.Date >= '$from' AND r.Date <= '$to') AND r.Subunit = '$subunit'  ORDER BY r.Date ASC";
            } elseif ($this->isGranted('WORKER')) {
                $dql = "SELECT r FROM App:WinterJobs r WHERE (r.Date >= '$from' AND r.Date <= '$to') AND r.Subunit = '$subunit'  ORDER BY r.Date ASC";
            }

            $em = $this->get('doctrine.orm.entity_manager');
            $query = $em->createQuery($dql);
            $report = $query->execute();

            if ($form->get('GenerateXLS')->isClicked()) {
                $fileName = md5($this->getUser()->getUserName() . microtime());
                $reader = IOFactory::createReader('Xlsx');
                $spreadsheet = $reader->load('winter_jobs_tmpl.xlsx');

                $spreadsheet->getActiveSheet()
                    ->setCellValue('A3', "Nuo: " . $from . " Iki: " . $to);

                $index = 5;
                foreach ($report as $item) {
                    foreach ($item->getRoadSections() as $value) {
                        $spreadsheet->getActiveSheet()
                            ->setCellValue('A' . $index, $item->getSubunitName())
                            ->setCellValue('B' . $index, $item->getDate()->format('Y-m-d'))
                            ->setCellValue('C' . $index, $item->getTimeFrom()->format('H:m'))
                            ->setCellValue('D' . $index, $item->getTimeTo()->format('H:m'))
                            ->setCellValue('E' . $index, $item->getMechanism())
                            ->setCellValue('F' . $index, $item->getJob())
                            ->setCellValue('G' . $index, $item->getJobId())
                            ->setCellValue('H' . $index, $item->getJobQuantity())
                            ->setCellValue('I' . $index, $value->getSectionId())
                            ->setCellValue('J' . $index, $value->getSectionType())
                            ->setCellValue('K' . $index, $value->getSectionBegin())
                            ->setCellValue('L' . $index, $value->getSectionEnd())
                            ->setCellValue('M' . $index, $value->getSaltValue())
                            ->setCellValue('N' . $index, $value->getSandValue())
                            ->setCellValue('O' . $index, $value->getSolutionValue())
                            ->setCellValue('P' . $index, $value->getQuadrature());

                        $index++;
                    }
                }

                // Rename worksheet
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $writer->save('files/' . $fileName . '.xlsx');
                return $this->file(('files/' . $fileName . '.xlsx'));
// Set document properties
            }
            return $this->render('winter_report/winter_jobs_report.html.twig', ['form' => $form->createView(), 'winter_jobs_report' => $report]);
        } else {
            return $this->render('winter_report/winter_jobs_report.html.twig', ['form' => $form->createView(), ['winter_jobs_report' => null]]);
        }
    }

    /**
     * @Route("/winter/report/materials", name="winter_report_materials")
     */
    public function winterMaintenanceReportMaterial (LdapUserRepository $ldapUserRepository, Request $request, AuthorizationCheckerInterface $authChecker) {

        $username = $this->getUser()->getUserName();

        if (!$ldapUserRepository->findUnitIdByUserName($username)->getSubunit()) {
            $this->addFlash(
                'danger',
                'Jūs nepasirinkęs kelių tarnybos!'
            );
            return $this->redirectToRoute('ldap_user_index');
        }

        $form = $this->createForm(WinterJobsReportType::class);

        $form->handleRequest($request);
        $subunit = $ldapUserRepository->findUnitIdByUserName($this->getUser()->getUserName())->getSubunit();

        if ($form->isSubmitted() && $form->isValid()) {
            $from = $form->get('From')->getData();
            $to = $form->get('To')->getData();

            if ($this->isGranted('ADMIN')) {
                $report = $this->getDaysMaterials($from, $to);
            }
            elseif ($this->isGranted('SUPER_VIEWER')){
                $report = $this->getDaysMaterials($from, $to);
            }
            elseif ($this->isGranted('UNIT_VIEWER')) {
                $report = $this->getDaysMaterialsForSubunit($from, $to, $subunit);
            }
            elseif ($this->isGranted('SUPER_MASTER')) {
                $report = $this->getDaysMaterialsForSubunit($from, $to, $subunit);
            }
            elseif ($this->isGranted('ROAD_MASTER')) {
                $report = $this->getDaysMaterialsForSubunit($from, $to, $subunit);
            }
            elseif($this->isGranted('WORKER') ) {
                $report = $this->getDaysMaterialsForSubunit($from, $to, $subunit);
            }

            if ($form->get('GenerateXLS')->isClicked()) {
                $fileName = md5($this->getUser()->getUserName() . microtime());
                $reader = IOFactory::createReader('Xlsx');
                $spreadsheet = $reader->load('winter_jobs_material_tmpl.xlsx');

                $spreadsheet->getActiveSheet()
                    ->setCellValue('A3', "Nuo: " . $from . " Iki: " . $to);

                $spreadsheet->getProperties()->setCreator($this->getUser()->getUserName())
                    ->setLastModifiedBy('VĮ Kelių priežiūra')
                    ->setTitle('Atliktų žiemos medžiagų ataskaita')
                    ->setSubject('Atliktų žiemos medžiagų ataskaita')
                    ->setDescription('Atliktų žiemos medžiagų ataskaita')
                    ->setKeywords('Atliktų žiemos medžiagų ataskaita')
                    ->setCategory('Atliktų žiemos medžiagų ataskaita');

                $index = 5;

                foreach ($report as $rep) {
                    if (!empty($rep)){
                        foreach ( $rep as $item){
                            $spreadsheet->getActiveSheet()
                                ->setCellValue('A' . $index, $item->getName())
                                ->setCellValue('B' . $index, $item->getSectionId())
                                ->setCellValue('C' . $index, $item->getSaltValue())
                                ->setCellValue('D' . $index, $item->getSandValue())
                                ->setCellValue('E' . $index, $item->getSolutionValue());
                            $index++;
                        }
                    }
                }

                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $writer->save('files/' . $fileName . '.xlsx');
                return $this->file(('files/' . $fileName . '.xlsx'));
            }
            // Rename worksheet

            return $this->render('winter_report/winter_material_report.html.twig', ['form' => $form->createView(), 'winter_material_report' => $report]);
        } else {
            return $this->render('winter_report/winter_material_report.html.twig', ['form' => $form->createView(), ['winter_material_report' => null]]);
        }
    }

    /**
     * @Route("/winter/report/materials_region", name="winter_report_materials_region")
     */
    public function winterReportMaterialRegional (LdapUserRepository $ldapUserRepository, Request $request, AuthorizationCheckerInterface $authChecker) {

        if($this->isGranted("ADMIN") || $this->isGranted("SUPER_VIEWER")) {
        }else{
            $this->denyAccessUnlessGranted("ADMIN");
        }

        $username = $this->getUser()->getUserName();

        if (!$ldapUserRepository->findUnitIdByUserName($username)->getSubunit()) {
            $this->addFlash(
                'danger',
                'Jūs nepasirinkęs kelių tarnybos!'
            );
            return $this->redirectToRoute('ldap_user_index');
        }

        $form = $this->createForm(WinterJobsReportType::class);

        $form->handleRequest($request);
        $subunit = $ldapUserRepository->findUnitIdByUserName($this->getUser()->getUserName())->getSubunit();

        if ($form->isSubmitted() && $form->isValid()) {
            $from = $form->get('From')->getData();
            $to = $form->get('To')->getData();

            $report = $this->getDaysMaterialsForRegion($from, $to);

            if ($form->get('GenerateXLS')->isClicked()) {
                $fileName = md5($this->getUser()->getUserName() . microtime());
                $reader = IOFactory::createReader('Xlsx');
                $spreadsheet = $reader->load('winter_jobs_material_regional_tmpl.xlsx');

                $spreadsheet->getActiveSheet()
                    ->setCellValue('A3', "Nuo: " . $from . " Iki: " . $to);

                $spreadsheet->getProperties()->setCreator($this->getUser()->getUserName())
                    ->setLastModifiedBy('VĮ Kelių priežiūra')
                    ->setTitle('Atliktų žiemos medžiagų ataskaita')
                    ->setSubject('Atliktų žiemos medžiagų ataskaita')
                    ->setDescription('Atliktų žiemos medžiagų ataskaita')
                    ->setKeywords('Atliktų žiemos medžiagų ataskaita')
                    ->setCategory('Atliktų žiemos medžiagų ataskaita');

                $index = 5;

                foreach ($report as $rep) {
                    $spreadsheet->getActiveSheet()
                        ->setCellValue('A' . $index, $rep->getRegionName())
                        ->setCellValue('B' . $index, $rep->getName())
                        ->setCellValue('C' . $index, $rep->getSaltValue())
                        ->setCellValue('D' . $index, $rep->getSandValue())
                        ->setCellValue('E' . $index, $rep->getSolutionValue());
                    $index++;
                }

                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $writer->save('files/' . $fileName . '.xlsx');
                return $this->file(('files/' . $fileName . '.xlsx'));
            }
            // Rename worksheet

            return $this->render('winter_report/winter_material_report_regional.html.twig', ['form' => $form->createView(), 'winter_subunits' => $report]);
        } else {
            return $this->render('winter_report/winter_material_report_regional.html.twig', ['form' => $form->createView(), ['winter_subunits' => null]]);
        }
    }

    /**
     * @Route("/winter/report/mechanism", name="winter_report_mechanism")
     */
    public function winterMaintenanceReportMechanism (AuthorizationCheckerInterface $authChecker,LdapUserRepository $ldapUserRepository, Request $request) {

        $username = $this->getUser()->getUserName();

        if (!$ldapUserRepository->findUnitIdByUserName($username)->getSubunit()) {
            $this->addFlash(
                'danger',
                'Jūs nepasirinkęs kelių tarnybos!'
            );
            return $this->redirectToRoute('ldap_user_index');
        }

        $form = $this->createForm(WinterJobsReportType::class);
        $form->handleRequest($request);
        $subunit = $ldapUserRepository->findUnitIdByUserName($this->getUser()->getUserName())->getSubunit();
        $dateNow = new \DateTime('now');
        if ($form->isSubmitted() && $form->isValid()) {
            $from = $form->get('From')->getData();
            $to = $form->get('To')->getData();

            if ($this->isGranted('ADMIN')) {
                $report = $this->getDaysMechanism($from, $to);
            }
            elseif ($this->isGranted('SUPER_VIEWER')){
                $report = $this->getDaysMechanism($from, $to);
            }
            elseif ($this->isGranted('UNIT_VIEWER')) {
                $report = $this->getDaysMechanismForSubunit($from, $to, $subunit);
            }
            elseif ($this->isGranted('SUPER_MASTER')) {
                $report = $this->getDaysMechanismForSubunit($from, $to, $subunit);
            }
            elseif ($this->isGranted('ROAD_MASTER')) {
                $report = $this->getDaysMechanismForSubunit($from, $to, $subunit);
            }
            elseif($this->isGranted('WORKER') ) {
                $report = $this->getDaysMechanismForSubunit($from, $to, $subunit);
            }

            $arrayKeys = array_keys($report);
            if ($form->get('GenerateXLS')->isClicked()) {
                $fileName = md5($this->getUser()->getUserName() . microtime());
                $reader = IOFactory::createReader('Xlsx');
                $spreadsheet = $reader->load('mechanizm_report.xlsx');
                $index = 5;
                $keyIndex = 0;
                $spreadsheet->getActiveSheet()
                    ->setCellValue('A1', $dateNow->format('Y-m-d'));
                foreach ($report as $rep) {
                    if($arrayKeys[$keyIndex] == 'Nepriskirta'){
                        $keyIndex ++;
                    }
                    else {
                        $spreadsheet->getActiveSheet()
                            ->setCellValue('A'. $index, $this->getRegionBySubunitId($this->getSubunitIdByName($arrayKeys[$keyIndex])))
                            ->setCellValue('B'. $index, $arrayKeys[$keyIndex])
                            ->setCellValue('D' . $index, $rep['Sunkvežimis'])
                            ->setCellValue('E' . $index, $rep['Autogreideris'])
                            ->setCellValue('F' . $index, $rep['Traktorius'])
                            ->setCellValue('G' . $index, $rep['Kiti']);
                        $index ++;
                        $keyIndex++;
                    }
                }

                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $writer->save('files/' . $fileName . '.xlsx');
                return $this->file(('files/' . $fileName . '.xlsx'));
            }
// Rename worksheet

            return $this->render('winter_report/winter_mechanism_report.html.twig', ['form' => $form->createView(), 'winter_mechanism_report' => $report, 'array_keys' => $arrayKeys]);
        } else {
            return $this->render('winter_report/winter_mechanism_report.html.twig', ['form' => $form->createView(), ['winter_mechanism_report' => null]]);
        }
    }

    public function getRegionBySubunitId($SubunitId) {
        $em2 = $this->getDoctrine()->getRepository('App:Region');
        return $em2->findOneBy(['SubunitId' => $SubunitId])->getRegionName();
    }

    public function getSubunitIdByName($name) {
        $em2 = $this->getDoctrine()->getRepository('App:Subunit');
        return $em2->findOneBy(['Name' => $name])->getSubunitId();
    }

    /**
     * @Route("/winter/report/done_jobs", name="winter_done_jobs")
     */
    public function index(LdapUserRepository $ldapUserRepository, Request $request, AuthorizationCheckerInterface $authChecker)
    {
        $username = $this->getUser()->getUserName();

        if (!$ldapUserRepository->findUnitIdByUserName($username)->getSubunit()) {
            $this->addFlash(
                'danger',
                'Jūs nepasirinkęs kelių tarnybos!'
            );
            return $this->redirectToRoute('ldap_user_index');
        }

        $form = $this->createForm(WinterJobsReportType::class);

        $form->handleRequest($request);
        $subunit = $ldapUserRepository->findUnitIdByUserName($this->getUser()->getUserName())->getSubunit();

        if ($form->isSubmitted() && $form->isValid()) {
            $from = $form->get('From')->getData();
            $to = $form->get('To')->getData();

            $dql = '';
            if ($this->isGranted('ADMIN')) {
                $dql = "SELECT d FROM App:WinterJobs d WHERE (d.Date >= '$from' AND d.Date <= '$to') ORDER BY d.Date ASC";
            } elseif ($this->isGranted('ROAD_MASTER')) {
                $dql = "SELECT d FROM App:WinterJobs d WHERE (d.Subunit = '$subunit' AND d.Date >= '$from' AND d.Date <= '$to') ORDER BY d.Date ASC";
            } elseif ($this->isGranted('SUPER_MASTER')) {
                $dql = "SELECT d FROM App:WinterJobs d WHERE (d.Subunit = '$subunit' AND d.Date >= '$from' AND d.Date <= '$to') ORDER BY d.Date ASC";
            } elseif ($this->isGranted('UNIT_VIEWER')) {
                $dql = "SELECT d FROM App:WinterJobs d WHERE (d.Subunit = '$subunit' AND d.Date >= '$from' AND d.Date <= '$to') ORDER BY d.Date ASC";
            } elseif ($this->isGranted('SUPER_VIEWER')) {
                $dql = "SELECT d FROM App:WinterJobs d WHERE (d.Date >= '$from' AND d.Date <= '$to') ORDER BY d.Date ASC";
            } elseif ($this->isGranted('WORKER')) {
                $dql = "SELECT d FROM App:WinterJobs d WHERE (d.Username = '$username' AND d.Date >= '$from' AND d.Date <= '$to') ORDER BY d.Date ASC";
            }

            $em = $this->get('doctrine.orm.entity_manager');
            $query = $em->createQuery($dql);
            $report = $query->execute();

            if ($form->get('GenerateXLS')->isClicked()) {
                $fileName = md5($this->getUser()->getUserName() . microtime());
                $reader = IOFactory::createReader('Xlsx');
                $spreadsheet = $reader->load('winter_done_job.xlsx');

                $spreadsheet->getProperties()->setCreator($this->getUser()->getUserName())
                    ->setLastModifiedBy('VĮ Kelių priežiūra')
                    ->setTitle('Atliktų žiemos medžiagų ataskaita')
                    ->setSubject('Atliktų žiemos medžiagų ataskaita')
                    ->setDescription('Atliktų žiemos medžiagų ataskaita')
                    ->setKeywords('Atliktų žiemos medžiagų ataskaita')
                    ->setCategory('Atliktų žiemos medžiagų ataskaita');

                $index = 3;

                foreach ($report as $item) {
                    foreach ($item->getRoadSections() as $value) {
                        $spreadsheet->getActiveSheet()
                            ->setCellValue('F' . $index, $item->getJobId())
                            ->setCellValue('G' . $index, $item->getJobName())
                            ->setCellValue('B' . $index, $item->getDate()->format('Y-m-d'))
                            ->setCellValue('H' . $index, $item->getJobQuantity())
                            ->setCellValue('D' . $index, $value->getSectionId() . '(' . $value->getSectionBegin() . '-' . $value->getSectionEnd() . ')');
                        $index++;
                    }
                }

                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $writer->save('files/' . $fileName . '.xlsx');
                return $this->file(('files/' . $fileName . '.xlsx'));
            }
            // Rename worksheet

            return $this->render('winter_report/winter_done_job_report.twig', ['form' => $form->createView(), 'report' => $report]);
        } else {
            return $this->render('winter_report/winter_done_job_report.twig', ['form' => $form->createView(), ['report' => null]]);
        }

    }

    /**
     * @Route("/winter/reports/jobs/road/type", name="winter_done_jobs_type")
     */
    public function sumReportsByRoadType(LdapUserRepository $ldapUserRepository, Request $request, AuthorizationCheckerInterface $authChecker)
    {
        $userName = $this->getUser()->getUserName();
        if (!$ldapUserRepository->findUnitIdByUserName($userName)->getSubunit()) {
            $this->addFlash(
                'danger',
                'Jūs nepasirinkęs kelių tarnybos!'
            );
            return $this->redirectToRoute('ldap_user_index');
        } else {
            $username = $this->getUser()->getUserName();
            $subunit = $ldapUserRepository->findUnitIdByUserName($this->getUser()->getUserName())->getSubunit()->getSubunitId();
            $form = $this->createForm(ReportType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $from = $form->get('From')->getData();
                $to = $form->get('To')->getData();
                $dql = '';

                $report = null;

                if ($this->isGranted('ADMIN')) {
                    $report = $this->getTypeSumForAll("SELECT w FROM App:WinterJobs w WHERE (w.Date >='$from' AND w.Date <= '$to')");
                } elseif ($this->isGranted('SUPER_VIEWER')) {
                    $report = $this->getTypeSumForAll("SELECT w FROM App:WinterJobs w WHERE (w.Date >='$from' AND w.Date <= '$to')");
                } elseif ($this->isGranted('UNIT_VIEWER')) {
                    $report = $this->getTypeSumForAll("SELECT w FROM App:WinterJobs w WHERE w.Subunit = '$subunit' AND (w.Date >='$from' AND w.Date <= '$to')");
                } elseif ($this->isGranted('SUPER_MASTER')) {
                    $report = $this->getTypeSumForAll("SELECT w FROM App:WinterJobs w WHERE w.Subunit = '$subunit' AND (w.Date >='$from' AND w.Date <= '$to')");
                } elseif ($this->isGranted('ROAD_MASTER')) {
                    $report = $this->getTypeSumForAll("SELECT w FROM App:WinterJobs w WHERE w.Subunit = '$subunit' AND (w.Date >='$from' AND w.Date <= '$to')");
                } elseif ($this->isGranted('WORKER')) {
                    $report = $this->getTypeSumForAll("SELECT w FROM App:WinterJobs w WHERE w.Subunit = '$subunit' AND (w.Date >='$from' AND w.Date <= '$to')");
                }else{
                    $report = null;
                }
                
                if ($form->get('GenerateXLS')->isClicked()) {
                    $fileName = md5($this->getUser()->getUserName() . microtime());
                    $reader = IOFactory::createReader('Xlsx');
                    $spreadsheet = $reader->load('winter_done_jobs_sum_tmpl.xlsx');

                    $spreadsheet->getProperties()->setCreator($this->getUser()->getUserName())
                        ->setLastModifiedBy('VĮ Kelių priežiūra')
                        ->setTitle('Atliktų žiemos medžiagų ataskaita')
                        ->setSubject('Atliktų žiemos medžiagų ataskaita')
                        ->setDescription('Atliktų žiemos medžiagų ataskaita')
                        ->setKeywords('Atliktų žiemos medžiagų ataskaita')
                        ->setCategory('Atliktų žiemos medžiagų ataskaita');

                    $index = 5;

                    $spreadsheet->getActiveSheet()
                        ->setCellValue('A' . 2, "Data: nuo {$from} iki {$to}" );

                    foreach ($report as $item) {
                        foreach ($item->getDoneJobs() as $x => $x_value) {
                            $spreadsheet->getActiveSheet()
                                ->setCellValue('A' . $index, $item->getType())
                                ->setCellValue('B' . $index, $x)
                                ->setCellValue('C' . $index, $item->getName($x))
                                ->setCellValue('D' . $index, "1000 m2")
                                ->setCellValue('E' . $index, $x_value)
                                ->setCellValue('F' . $index,    "");
                            $index++;
                        }
                    }

                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    $writer->save('files/' . $fileName . '.xlsx');
                    return $this->file(('files/' . $fileName . '.xlsx'));
                }

                return $this->render('winter_report/winter_done_job_report_sum.twig', ['form' => $form->createView(), 'report' => $report]);
            } else {
                return $this->render('winter_report/winter_done_job_report_sum.twig', ['form' => $form->createView(), ['report' => null]]);
            }
        }
    }


    /**
     * @param $start -> Pradine data nuo kada ieskosim
     * @param $end -> Galutine data nuo kada ieskosim
     * @return array -> Grazinamas visu subunit array
     * $this->getDaysMaterials(new \DateTime(),new \DateTime('-500 days'),1);
     */
    private function getDaysMaterials($start,$end)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        //Suformatuojam data kad galetume ja naudoti DQL
        //$start = $start->format('Y-m-d');
        //$end = $end->format('Y-m-d');

        $result = array();

        //Gauname visus KT ID kad galetume padaryti ataskaita visiems KT
        $dql = "SELECT w FROM App:Subunit w ORDER BY w.SubunitId ";
        $subunits = $em->createQuery($dql)->execute();

        //einame pro visus KT
        foreach ($subunits as $subunit) {

            $subunitId = $subunit->getSubunitId();

            //Gauname visus WinterJobs PAGAL KT ID ir datas
            $dql2 = "SELECT w.RoadSections FROM App:WinterJobs w WHERE w.Subunit = '$subunitId' AND (w.Date >='$start' AND w.Date <= '$end')";
            $winterRoadSectionArray = $em->createQuery($dql2)->execute();

            $subunitRoadsFinal = array();
            $subunitRoads = array();

            //Einame pro
            foreach ($winterRoadSectionArray as $roadArray) {

                //Gauname visus WinterJobs Kelius(RoadSections array) ir einame pro juos
                foreach ($roadArray["RoadSections"] as $winterJobRoad) {
                    //Jeigu kelio su winterJobRoad->getSectionId()(Kelio id) KEY nera ji sukuriame
                    //Jeigu jis yra jau sukurtas(jei toks kelias jau buvo priestai) prie to KEY pridedame naujo kelio reiksmes
                    if (!isset($subunitRoads[$winterJobRoad->getSectionId()])) {
                        $subunitRoads[$winterJobRoad->getSectionId()] = new MaterialReportObject($subunit->getName(), $subunitId, $winterJobRoad->getSectionId(), $winterJobRoad->getSaltValue(), $winterJobRoad->getSandValue(), $winterJobRoad->getSolutionValue());
                    } else {
                        $subunitRoads[$winterJobRoad->getSectionId()]->addSalt($winterJobRoad->getSaltValue());
                        $subunitRoads[$winterJobRoad->getSectionId()]->addSand($winterJobRoad->getSandValue());
                        $subunitRoads[$winterJobRoad->getSectionId()]->addSolution($winterJobRoad->getSolutionValue());
                    }
                }
            }

            //I array sudedame visa informacija KEY yra KT ID value yra Masyvas su sumuotais keliais
            $result[$subunitId] = $subunitRoads;
        }
        return $result;
    }

    //Regionas -> tarn -> sum kiekis
    private function getDaysMaterialsForRegion($start,$end)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        //Suformatuojam data kad galetume ja naudoti DQL
        //$start = $start->format('Y-m-d');
        //$end = $end->format('Y-m-d');

        $result = array([]);

        //Gauname visus KT ID kad galetume padaryti ataskaita visiems KT
        $dql = "SELECT w FROM App:Region w ORDER BY w.SubunitId";
        $regions = $em->createQuery($dql)->execute();

        $dql = "SELECT w FROM App:Subunit w ORDER BY w.SubunitId";
        $tempArray = $em->createQuery($dql)->execute();

        $subunits = array();

        foreach ($tempArray as $sub)
        {
            $subunits[$sub->getSubunitId()] = $sub->getName();
        }

        //einame pro visus Regionus
        foreach ($regions as $region) {

            $subunitId = $region->getSubunitId();
            $dql2 = "SELECT w FROM App:WinterJobs w WHERE w.Subunit = '$subunitId' AND (w.Date >='$start' AND w.Date <= '$end')";
            $winterRoads = $em->createQuery($dql2)->execute();

            $subunitValues = new MaterialReportObjectForSubunit($subunits[$subunitId],$region->getRegionName(), $subunitId, 0, 0, 0);

            foreach ($winterRoads as $winterRoad) {
                $subunitValues->addSalt($winterRoad->getRoadSectionsSaltSum());
                $subunitValues->addSand($winterRoad->getRoadSectionsSandSum());
                $subunitValues->addSolution($winterRoad->getRoadSectionsSolutionSum());
            }

            $result[] = $subunitValues;
        }

        unset($result[0]);

        return $result;
    }


    /**
     * @param $start
     * @param $end
     * @param $subunit
     * @return array SVARBU Idedame visa subunit ne tik SubunitId
     *  $this->getDaysMaterialsForSubunit(new \DateTime(),new \DateTime("-100 days"),$ldapUserRepository->findUnitIdByUserName($username)->getSubunit());

     */
    private function getDaysMaterialsForSubunit($start,$end,$subunit)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        //Suformatuojam data kad galetume ja naudoti DQL
        //$start = $start->format('Y-m-d');
        //$end = $end->format('Y-m-d');

        $result = array();

        $subunitId = $subunit->getSubunitId();

        //Gauname visus WinterJobs PAGAL KT ID ir datas
        $dql2 = "SELECT w.RoadSections FROM App:WinterJobs w WHERE w.Subunit = '$subunitId' AND (w.Date >='$start' AND w.Date <= '$end')";
        $winterRoadSectionArray = $em->createQuery($dql2)->execute();

        $subunitRoads = array();

        //Einame pro WinterJobs
        foreach ($winterRoadSectionArray as $roadArray) {

            //Gauname visus WinterJobs Kelius(RoadSections array) ir einame pro juos
            foreach ($roadArray["RoadSections"] as $winterJobRoad) {
                //Jeigu kelio su winterJobRoad->getSectionId()(Kelio id) KEY nera ji sukuriame
                //Jeigu jis yra jau sukurtas(jei toks kelias jau buvo priestai) prie to KEY pridedame naujo kelio reiksmes
                if (!isset($subunitRoads[$winterJobRoad->getSectionId()])) {
                    $subunitRoads[$winterJobRoad->getSectionId()] = new MaterialReportObject($subunit->getName(), $subunit, $winterJobRoad->getSectionId(), $winterJobRoad->getSaltValue(), $winterJobRoad->getSandValue(), $winterJobRoad->getSolutionValue());
                } else {
                    $subunitRoads[$winterJobRoad->getSectionId()]->addSalt($winterJobRoad->getSaltValue());
                    $subunitRoads[$winterJobRoad->getSectionId()]->addSand($winterJobRoad->getSandValue());
                    $subunitRoads[$winterJobRoad->getSectionId()]->addSolution($winterJobRoad->getSolutionValue());
                }
            }
        }

        //I array sudedame visa informacija KEY yra KT ID value yra Masyvas su sumuotais keliais
        $result[$subunitId] = $subunitRoads;

        return $result;
    }

    private function getTypeSumForAll($dql)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $result = array();

        $sectionTypes = array("Krašto","Magistralinis","Rajoninis","Jungiamasis");

        //einame pro visus Type
        foreach ($sectionTypes as $type) {
            // $SectionType

            $winterJobArray = $em->createQuery($dql)->getResult();

            $x = new WinterDoneJobsObjectForType($type);

            foreach ($winterJobArray as $winterJob) {
                foreach ($winterJob->getRoadSections() as $road) {
                    if ($road->getSectionType() == $type) {
                        $x->addJob($winterJob->getJobId(), $road->getQuadrature(),$winterJob->getJobName());
                    }
                }
            }

            $result[$type] = $x;
        }


        return $result;
    }

    /**
     * @param $start
     * @param $end
     * @return array
     * $this->getDaysMechanism(new \DateTime(), new \DateTime("-100 days"));
     */
    private function getDaysMechanism($start,$end)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        //Suformatuojam data kad galetume ja naudoti DQL
        // $start = $start->format('Y-m-d');
        // $end = $end->format('Y-m-d');

        //Surasome kokius mechanizmus norime surasti, visi mechanizmai kurie nebus cia surasyti atsidurs "Kiti" value
        $mechanisms = array("Kiti"=>0,"Sunkvežimis"=>"Sunkvežimis", "Autogreideris"=>"Autogreideris","Traktorius"=>"Traktorius");
        $mechanismSum = array("Sunkvežimis"=>0, "Autogreideris"=>0,"Traktorius"=>0);
        $result = array();

        //Gauname visus KT
        $dql = "SELECT w FROM App:Subunit w ORDER BY w.SubunitId";
        $subunits = $em->createQuery($dql)->getArrayResult();

        //einame pro visus KT
        foreach ($subunits as $subunit) {
            //Kadangi is Query imam tik SubunitId delto reikia patikslinti su ["SubunitId"]
            $subunitId = $subunit["SubunitId"];
            $mechanisms["Kiti"] = 0;
            $mechanismSum = array("Sunkvežimis"=>0, "Autogreideris"=>0,"Traktorius"=>0);

            //Einamepro mechanizmus auksciau isvardintus
            //select('DISTINCT(rec.city) as city')
            $dql = "SELECT DISTINCT w.Mechanism FROM App:WinterJobs w WHERE w.Subunit = '$subunitId' AND (w.Date >='$start' AND w.Date <= '$end')";

            $winterJobArray = $em->createQuery($dql)->getResult();

            foreach ($winterJobArray as $winterJob) {
                $winterJob = $winterJob["Mechanism"];
                foreach ($mechanisms as $x => $x_value) {
                    if (strpos(strtolower($winterJob), strtolower($x_value)) !== false && $x!="Kiti") {
                        $mechanismSum[$x]++;
                    }
                }
            }

            $mechanismSum["Kiti"] = count($winterJobArray)- array_sum($mechanismSum);

            $result[$subunit["Name"]] = $mechanismSum;
        }

        return $result;
    }



    /**
     * @param $start
     * @param $end
     * @return array
     * $this->getDaysMechanism(new \DateTime(), new \DateTime("-100 days"));
     */
    private function getDaysMechanismForSubunit($start,$end,$subunit)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $subunitId = $subunit->getSubunitId();

        //Suformatuojam data kad galetume ja naudoti DQL
        // $start = $start->format('Y-m-d');
        // $end = $end->format('Y-m-d');

        //Surasome kokius mechanizmus norime surasti, visi mechanizmai kurie nebus cia surasyti atsidurs "Kiti" value
        $mechanisms = array("Kiti"=>0,"Sunkvežimis"=>"Sunkvežimis", "Autogreideris"=>"Autogreideris","Traktorius"=>"Traktorius");
        $mechanismSum = array("Sunkvežimis"=>0, "Autogreideris"=>0,"Traktorius"=>0);
        $result = array();

        $mechanisms["Kiti"] = 0;
        $mechanismSum = array("Sunkvežimis"=>0, "Autogreideris"=>0,"Traktorius"=>0);

        //Einamepro mechanizmus auksciau isvardintus
        //select('DISTINCT(rec.city) as city')
        $dql = "SELECT DISTINCT w.Mechanism FROM App:WinterJobs w WHERE w.Subunit = '$subunitId' AND (w.Date >='$start' AND w.Date <= '$end')";

        $winterJobArray = $em->createQuery($dql)->getResult();

        foreach ($winterJobArray as $winterJob) {
            $winterJob = $winterJob["Mechanism"];
            foreach ($mechanisms as $x => $x_value) {
                if (strpos(strtolower($winterJob), strtolower($x_value)) !== false && $x!="Kiti") {
                    $mechanismSum[$x]++;
                }
            }
        }

        $mechanismSum["Kiti"] = count($winterJobArray)- array_sum($mechanismSum);

        $result[$subunit->getName()] = $mechanismSum;


        return $result;
    }

}
