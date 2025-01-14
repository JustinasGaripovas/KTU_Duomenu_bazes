<?php

namespace App\Controller;

use App\Entity\WinterJobs;
use App\Entity\WinterMaintenance;
use App\Form\LAKDReportType;
use App\Form\ReportType;
use App\Repository\LdapUserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;


/**
 * @IsGranted("REPORTS")
 */
class ReportsController extends Controller
{
    /**
     * @Route("/reports", name="reports")
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
        } else {

            $form = $this->createForm(ReportType::class);
            $subUnitId = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getId();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->html = '';
                $from = $form->get('From')->getData();
                $to = $form->get('To')->getData();
                $username = $this->getUser()->getUserName();

                $dql = '';
                if ($this->isGranted('ADMIN')) {
                    $dql = "SELECT d FROM App:DoneJobs d WHERE (d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.DoneJobDate ASC";
                } elseif ($this->isGranted('ROAD_MASTER')) {
                    $dql = "SELECT d FROM App:DoneJobs d WHERE (d.SubUnitId = '$subUnitId' AND d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.DoneJobDate ASC";
                } elseif ($this->isGranted('SUPER_MASTER')) {
                    $dql = "SELECT d FROM App:DoneJobs d WHERE (d.SubUnitId = '$subUnitId' AND d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.DoneJobDate ASC";
                } elseif ($this->isGranted('UNIT_VIEWER')) {
                    $dql = "SELECT d FROM App:DoneJobs d WHERE (d.SubUnitId = '$subUnitId' AND d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.DoneJobDate ASC";
                } elseif ($this->isGranted('SUPER_VIEWER')){
                    $dql = "SELECT d FROM App:DoneJobs d WHERE (d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.DoneJobDate ASC";
                } elseif ($this->isGranted('WORKER')){
                    $dql = "SELECT d FROM App:DoneJobs d WHERE (d.Username = '$username' AND d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.DoneJobDate ASC";
                }

                $em = $this->get('doctrine.orm.entity_manager');
                $query = $em->createQuery($dql);
                $report = $query->execute();
                $html = $this->renderView('reports/report.html.twig', ['report' => $report]);

                if ($form->get('GeneratePDF')->isClicked()) {
                    return new PdfResponse(
                        $this->get('knp_snappy.pdf')->getOutputFromHtml($html, ['orientation' => 'Landscape']),
                        'file.pdf'
                    );
                }
                if ($form->get('GenerateXLS')->isClicked()) {
                    $fileName = md5($this->getUser()->getUserName() . microtime());
                    $reader = IOFactory::createReader('Xlsx');
                    $spreadsheet = $reader->load('job_tmpl.xlsx');
                    // Set document properties
                    $spreadsheet->getProperties()->setCreator($this->getUser()->getUserName())
                        ->setLastModifiedBy('VĮ Kelių priežiūra')
                        ->setTitle('Atliktų darbų ataskaita')
                        ->setSubject('Atliktų darbų ataskaita')
                        ->setDescription('Atliktų darbų ataskaita')
                        ->setKeywords('Atliktų darbų ataskaita')
                        ->setCategory('Atliktų darbų ataskaita');
                    $index = 3;
                    $styleArray = ['font' => ['bold' => false]];
                    foreach ($report as $rep) {
                        $spreadsheet->getActiveSheet()
                            ->setCellValue('F' . $index, $rep->getJobId())
                            ->setCellValue('G' . $index, $rep->getJobName())
                            ->setCellValue('H' . $index, $rep->getUnitOf())
                            ->setCellValue('I' . $index, $rep->getQuantity())
                            ->setCellValue('B' . $index, $rep->getDoneJobDate()->format('Y-m-d'))
                            ->setCellValue('D' . $index, $rep->getSectionId() . '(' . $rep->getRoadSectionBegin() . '-' . $rep->getRoadSectionEnd() . ')');
                        $index++;
                    }
                    $spreadsheet->getActiveSheet()
                        ->getPageSetup()
                        ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $spreadsheet->getActiveSheet()
                        ->getPageSetup()
                        ->setPaperSize(PageSetup::PAPERSIZE_A4);
                    // Rename worksheet
                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    $writer->save('files/' . $fileName . '.xlsx');
                    return $this->file(('files/' . $fileName . '.xlsx'));
                }

                return $this->render('reports/index.html.twig', ['form' => $form->createView(), 'report' => $report]);
            } else {
                return $this->render('reports/index.html.twig', ['form' => $form->createView(), ['report' => null]]);
            }
        }
    }

    /**
     * @Route("/reports/inspection", name="inspection_reports")
     */
    public function inspectionRepor(LdapUserRepository $ldapUserRepository, Request $request, AuthorizationCheckerInterface $authChecker)
    {
        $userName = $this->getUser()->getUserName();
        if (!$ldapUserRepository->findUnitIdByUserName($userName)->getSubunit()) {
            $this->addFlash(
                'danger',
                'Jūs nepasirinkęs kelių tarnybos!'
            );
            return $this->redirectToRoute('ldap_user_index');
        } else {
            $subUnitName = $ldapUserRepository->findUnitIdByUserName($userName)->getSubunit()->getName();
            $subUnitId = $ldapUserRepository->findUnitIdByUserName($userName)->getSubunit()->getId();
            $form = $this->createForm(ReportType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->html = '';
                $from = $form->get('From')->getData();
                $to = $form->get('To')->getData();
                $em = $this->get('doctrine.orm.entity_manager');
                $dql = '';

                if ($this->isGranted('ADMIN')) {
                    $dql = "SELECT i FROM App:Inspection i WHERE (i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.id DESC";
                }
                elseif ($this->isGranted('SUPER_VIEWER')){
                    $dql = "SELECT i FROM App:Inspection i WHERE (i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.id DESC";
                }
                elseif ($this->isGranted('UNIT_VIEWER')) {
                    $dql = "SELECT i FROM App:Inspection i WHERE (i.SubUnitId = '$subUnitId'AND i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.id ASC";
                }
                elseif ($this->isGranted('SUPER_MASTER')) {
                    $dql = "SELECT i FROM App:Inspection i WHERE (i.SubUnitId = '$subUnitId' AND i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.id ASC";
                }
                elseif ($this->isGranted('ROAD_MASTER')) {
                    $dql = "SELECT i FROM App:Inspection i WHERE (i.SubUnitId = '$subUnitId' AND i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.id DESC";
                }
                elseif($this->isGranted('WORKER') ) {
                    $dql = "SELECT i FROM App:Inspection i WHERE (i.Username = '$userName' AND i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.id ASC";
                }

                $query = $em->createQuery($dql);

                //PROBLEMA CIA
                $report = $query->execute();

                $html = $this->renderView('reports/report_inspections.html.twig', [
                    'report' => $report,
                    'subunit_name' => $subUnitName
                ]);

                if ($form->get('GeneratePDF')->isClicked()) {
                    return new PdfResponse(
                        $this->get('knp_snappy.pdf')->getOutputFromHtml($html, ['orientation' => 'Portrait']),
                        'file.pdf'
                    );
                }

                if ($form->get('GenerateXLS')->isClicked()) {
                    $fileName = md5($this->getUser()->getUserName() . microtime());
                    $reader = IOFactory::createReader('Xlsx');
                    $spreadsheet = $reader->load('inspection_tmpl.xlsx');
// Set document properties
                    $spreadsheet->getProperties()->setCreator($this->getUser()->getUserName())
                        ->setLastModifiedBy('VĮ Kelių priežiūra')
                        ->setTitle('Atliktų darbų ataskaita')
                        ->setSubject('Atliktų darbų ataskaita')
                        ->setDescription('Atliktų darbų ataskaita')
                        ->setKeywords('Atliktų darbų ataskaita')
                        ->setCategory('Atliktų darbų ataskaita');
                    $index = 6;
                    $dateNow = new \DateTime('now');
                    $styleArray = ['font' => ['bold' => false]];
                    $spreadsheet->getActiveSheet()
                        ->setCellValue('A2', $dateNow->format('Y-m-d'))
                        ->setCellValue('A3', 'VĮ "KELIŲ PRIEŽIŪRA" ' . $subUnitName . ' kelių tarnyba');
                    foreach ($report as $rep) {
                        $spreadsheet->getActiveSheet()
                            ->setCellValue('A' . $index, $rep->getRoadId() . '(' . $rep->getRoadSectionBegin() . '-' . $rep->getRoadSectionEnd() . ')');
                        if($rep->getIsAdditional() === true){
                            $spreadsheet->getActiveSheet()->setCellValue('B' . $index, $rep->getNote() . '( Kelio būklė: '. $rep->getRoadCondition() .', '. 'Bangos dydis: ' .  $rep->getWaveSize(). 'cm. Vieta: ' . $rep->getplace() .'km.'. ')' );
                        }
                        else {
                            $spreadsheet->getActiveSheet()->setCellValue('B' . $index, $rep->getNote());
                        }
                        foreach ($rep->getJob() as $job) {
                            $spreadsheet->getActiveSheet()->setCellValue('C' . $index, $job->getDoneJobDate()->format('Y-m-d'));
                        }
                        $index++;
                    }
                    $spreadsheet->getActiveSheet()
                        ->getPageSetup()
                        ->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
                    $spreadsheet->getActiveSheet()
                        ->getPageSetup()
                        ->setPaperSize(PageSetup::PAPERSIZE_A4);
// Rename worksheet
                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    $writer->save('files/' . $fileName . '.xlsx');
                    return $this->file(('files/' . $fileName . '.xlsx'));
                }


                return $this->render('reports/index_inspections.html.twig', ['form' => $form->createView(), 'report' => $report]);
            } else {
                return $this->render('reports/index_inspections.html.twig', ['form' => $form->createView(), ['report' => null]]);
            }
        }
    }

    /**
     * @Route("/reports/jobs/road/level", name="report_done_jobs_by road_level")
     */
    public function sumReportsByRoadLevel(LdapUserRepository $ldapUserRepository, Request $request, AuthorizationCheckerInterface $authChecker)
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
            $subUnitName = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getName();
            $subUnitId = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getId();
            $form = $this->createForm(ReportType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->html = '';
                $from = $form->get('From')->getData();
                $to = $form->get('To')->getData();
                $em = $this->get('doctrine.orm.entity_manager');
                $dql = '';

                if ($this->isGranted('ADMIN')) {
                    $dql = "SELECT i.RoadLevel,i.JobId, i.JobName, i.UnitOf, ROUND(SUM(i.Quantity), 2) AS SumOfQuantity FROM App:DoneJobs i WHERE (i.DoneJobDate >= '$from' AND i.DoneJobDate <= '$to') GROUP BY i.RoadLevel, i.JobId, i.JobName, i.UnitOf";
                }
                elseif ($this->isGranted('SUPER_VIEWER')){
                    $dql = "SELECT i.RoadLevel,i.JobId, i.JobName, i.UnitOf, ROUND(SUM(i.Quantity), 2) AS SumOfQuantity FROM App:DoneJobs i WHERE (i.DoneJobDate >= '$from' AND i.DoneJobDate <= '$to') GROUP BY i.RoadLevel, i.JobId, i.JobName, i.UnitOf";
                }
                elseif ($this->isGranted('UNIT_VIEWER')) {
                    $dql = "SELECT i.RoadLevel,i.JobId, i.JobName, i.UnitOf, ROUND(SUM(i.Quantity), 2) AS SumOfQuantity FROM App:DoneJobs i WHERE (i.SubUnitId = '$subUnitId' AND i.DoneJobDate >= '$from' AND i.DoneJobDate <= '$to') GROUP BY i.RoadLevel, i.JobId, i.JobName, i.UnitOf";
                }
                elseif ($this->isGranted('SUPER_MASTER')) {
                    $dql = "SELECT i.RoadLevel,i.JobId, i.JobName, i.UnitOf, ROUND(SUM(i.Quantity), 2) AS SumOfQuantity FROM App:DoneJobs i WHERE (i.SubUnitId = '$subUnitId' AND i.DoneJobDate >= '$from' AND i.DoneJobDate <= '$to') GROUP BY i.RoadLevel, i.JobId, i.JobName, i.UnitOf";
                }
                elseif ($this->isGranted('ROAD_MASTER')) {
                    $dql = "SELECT i.RoadLevel,i.JobId, i.JobName, i.UnitOf, ROUND(SUM(i.Quantity), 2) AS SumOfQuantity FROM App:DoneJobs i WHERE (i.SubUnitId = '$subUnitId' AND i.DoneJobDate >= '$from' AND i.DoneJobDate <= '$to') GROUP BY i.RoadLevel, i.JobId, i.JobName, i.UnitOf";
                }
                elseif($this->isGranted('WORKER') ) {
                    $dql = "SELECT i.RoadLevel,i.JobId, i.JobName, i.UnitOf, ROUND(SUM(i.Quantity), 2) AS SumOfQuantity FROM App:DoneJobs i WHERE (i.Username = '$username' AND i.DoneJobDate >= '$from' AND i.DoneJobDate <= '$to') GROUP BY i.RoadLevel, i.JobId, i.JobName, i.UnitOf";
                }

                $query = $em->createQuery($dql);
                $report = $query->execute();
                $html = $this->renderView('reports/report_sum_level.html.twig', [
                    'report' => $report,
                    'subunit_name' => $subUnitName
                ]);

                if ($form->get('GeneratePDF')->isClicked()) {
                    return new PdfResponse(
                        $this->get('knp_snappy.pdf')->getOutputFromHtml($html, ['orientation' => 'Portrait']),
                        'file.pdf'
                    );
                }
                if ($form->get('GenerateXLS')->isClicked()) {
                    $fileName = md5($this->getUser()->getUserName() . microtime());
                    $reader = IOFactory::createReader('Xlsx');
                    $spreadsheet = $reader->load('sum_tmpl_1.xlsx');
// Set document properties
                    $spreadsheet->getProperties()->setCreator($this->getUser()->getUserName())
                        ->setLastModifiedBy('VĮ Kelių priežiūra')
                        ->setTitle('Suminė darbų ataskaita')
                        ->setSubject('Suminė darbų ataskaita')
                        ->setDescription('Suminė darbų ataskaita')
                        ->setKeywords('Suminė darbų ataskaita')
                        ->setCategory('Suminė darbų ataskaita');
                    $index = 7;
                    $dateNow = new \DateTime('now');
                    $styleArray = ['font' => ['bold' => false]];
                    $spreadsheet->getActiveSheet()->setCellValue('A4', $dateNow->format('Y-m-d'));
                    $spreadsheet->getActiveSheet()->setCellValue('A1', 'VĮ "KELIŲ PRIEŽIŪRA" ' . strtoupper($subUnitName) . ' KELIŲ TARNYBA');
                    foreach ($report as $rep) {
                        $spreadsheet->getActiveSheet()->insertNewRowBefore($index, 1);
                        $spreadsheet->getActiveSheet()
                            ->setCellValue('A' . $index, $rep['RoadLevel'])
                            ->setCellValue('B' . $index, $rep['JobId'])
                            ->setCellValue('C' . $index, $rep['JobName'])
                            ->setCellValue('D' . $index, $rep['UnitOf'])
                            ->setCellValue('E' . $index, $rep['SumOfQuantity']);
                        $spreadsheet->getActiveSheet()
                            ->getStyle($index)
                            ->getAlignment()
                            ->setWrapText(true);
                        $spreadsheet->getActiveSheet()->getStyle('A' . $index)->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()->getStyle('B' . $index)->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()->getStyle('C' . $index)->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()->getStyle('D' . $index)->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()->getStyle('E' . $index)->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()
                            ->getRowDimension($index)
                            ->setRowHeight(40);
                        $spreadsheet->getActiveSheet()
                            ->getColumnDimension('C')->setWidth(40);
                        $index++;
                    }
                    $spreadsheet->getActiveSheet()
                        ->getStyle($index)
                        ->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $spreadsheet->getActiveSheet()
                        ->getStyle($index)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $spreadsheet->getActiveSheet()->removeRow($index, 1);
                    //$spreadsheet->getActiveSheet()->setCellValue('A1', $report[0]);
                    // Set page orientation and size
                    $spreadsheet->getActiveSheet()
                        ->getPageSetup()
                        ->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
                    $spreadsheet->getActiveSheet()
                        ->getPageSetup()
                        ->setPaperSize(PageSetup::PAPERSIZE_A4);
// Rename worksheet
                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    $writer->save('files/' . $fileName . '.xlsx');
                    return $this->file(('files/' . $fileName . '.xlsx'));
                }

                return $this->render('reports/index_sum_level.html.twig', ['form' => $form->createView(), 'report' => $report]);
            } else {
                return $this->render('reports/index_sum_level.html.twig', ['form' => $form->createView(), ['report' => null]]);
            }
        }
    }


    /**
     * @Route("/reports/insured/events", name="insured_events_report")
     */

    public function reportInsuredEvent(LdapUserRepository $ldapUserRepository, Request $request, AuthorizationCheckerInterface $authChecker)
    {
        $username = $this->getUser()->getUserName();
        if (!$ldapUserRepository->findUnitIdByUserName($username)->getSubunit()) {
            $this->addFlash(
                'danger',
                'Jūs nepasirinkęs kelių tarnybos!'
            );
            return $this->redirectToRoute('ldap_user_index');
        } else {

            $form = $this->createForm(ReportType::class);
            $subUnitId = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getId();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $from = $form->get('From')->getData();
                $to = $form->get('To')->getData();
                $username = $this->getUser()->getUserName();

                $dql = '';

                $dql = "SELECT ie FROM App:InsuredEvent ie WHERE (ie.DamageData >= '$from' AND ie.DamageData <= '$to') ORDER BY ie.DamageData ASC";

                $em = $this->get('doctrine.orm.entity_manager');
                $query = $em->createQuery($dql);
                $report = $query->execute();

                if ($form->get('GeneratePDF')->isClicked()) {
                    $html = $this->renderView('reports/report.html.twig', ['report' => $report]);
                    return new PdfResponse(
                        $this->get('knp_snappy.pdf')->getOutputFromHtml($html, ['orientation' => 'Landscape']),
                        'file.pdf'
                    );
                }
                if ($form->get('GenerateXLS')->isClicked()) {
                    $fileName = md5($this->getUser()->getUserName() . microtime());
                    $reader = IOFactory::createReader('Xlsx');
                    $spreadsheet = $reader->load('insured_event_tmpl1.xlsx');
// Set document properties
                    $index = 6;
                    foreach ($report as $rep) {
                        $spreadsheet->getActiveSheet()
                            ->setCellValue('B' . $index, $rep->getSubunit())
                            ->setCellValue('C' . $index, $rep->getRoadId())
                            ->setCellValue('D' . $index, $rep->getRoadName())
                            ->setCellValue('E' . $index, '(' . $rep->getSectionBegin(). ' - '. $rep->getSectionEnd() .')' )
                            ->setCellValue('F' . $index, $rep->getDamagedStuff())
                            ->setCellValue('G' . $index, $rep->getDocuments())
                            ->setCellValue('H' . $index, $rep->getEstimateToCompany())
                            ->setCellValue('I' . $index, $rep->getInsurensCompany())
                            ->setCellValue('J' . $index, $rep->getNumberOfDamage())
                            ->setCellValue('K' . $index, $rep->getDamageData()-> format('Y-m-d'))
                            ->setCellValue('M' . $index, $rep->getPayoutAmount());
                        if ($rep->getPayoutDate() != null) {
                            $spreadsheet->getActiveSheet()->setCellValue('L' . $index, $rep->getPayoutDate() -> format('Y-m-d'));
                        }
                        else {
                            $spreadsheet->getActiveSheet()->setCellValue('L' . $index, '');
                        }
                        $index++;
                    }
                    $spreadsheet->getActiveSheet()
                        ->getPageSetup()
                        ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $spreadsheet->getActiveSheet()
                        ->getPageSetup()
                        ->setPaperSize(PageSetup::PAPERSIZE_A4);
// Rename worksheet
                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    $writer->save('files/' . $fileName . '.xlsx');
                    return $this->file(('files/' . $fileName . '.xlsx'));
                }

                return $this->render('reports/index_insured_event.html.twig', ['form' => $form->createView(), 'report' => $report]);
            } else {
                return $this->render('reports/index_insured_event.html.twig', ['form' => $form->createView(), ['report' => null]]);
            }
        }
    }


    /**
     * @Route("/reports/filter", name="reports_filter")
     */

    public function reportWithFilter(LdapUserRepository $ldapUserRepository, Request $request, AuthorizationCheckerInterface $authChecker)
    {
        $username = $this->getUser()->getUserName();
        if (!$ldapUserRepository->findUnitIdByUserName($username)->getSubunit()) {
            $this->addFlash(
                'danger',
                'Jūs nepasirinkęs kelių tarnybos!'
            );
            return $this->redirectToRoute('ldap_user_index');
        } else {

            $form = $this->createForm(ReportType::class);
            $subUnitId = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getId();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $from = $form->get('From')->getData();
                $to = $form->get('To')->getData();
                $username = $this->getUser()->getUserName();

                $dql = '';

                if ($this->isGranted('ADMIN')) {
                    $dql = "SELECT d FROM App:DoneJobs d WHERE (d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.DoneJobDate ASC";
                }
                elseif ($this->isGranted('SUPER_VIEWER')){
                    $dql = "SELECT d FROM App:DoneJobs d WHERE (d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.DoneJobDate ASC";
                }
                elseif ($this->isGranted('UNIT_VIEWER')) {
                    $dql = "SELECT d FROM App:DoneJobs d WHERE (d.SubUnitId = '$subUnitId' AND d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.DoneJobDate ASC";
                }
                elseif ($this->isGranted('SUPER_MASTER')) {
                    $dql = "SELECT d FROM App:DoneJobs d WHERE (d.SubUnitId = '$subUnitId' AND d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.DoneJobDate ASC";
                }
                elseif ($this->isGranted('ROAD_MASTER')) {
                    $dql = "SELECT d FROM App:DoneJobs d WHERE (d.SubUnitId = '$subUnitId' AND d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.DoneJobDate ASC";
                }
                elseif($this->isGranted('WORKER') ) {
                    $dql = "SELECT d FROM App:DoneJobs d WHERE (d.Username = '$username' AND d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.DoneJobDate ASC";
                }

                $em = $this->get('doctrine.orm.entity_manager');
                $query = $em->createQuery($dql);
                $report = $query->execute();
                $html = $this->renderView('reports/report.html.twig', ['report' => $report]);

                if ($form->get('GeneratePDF')->isClicked()) {
                    return new PdfResponse(
                        $this->get('knp_snappy.pdf')->getOutputFromHtml($html, ['orientation' => 'Landscape']),
                        'file.pdf'
                    );
                }
                if ($form->get('GenerateXLS')->isClicked()) {
                    $fileName = md5($this->getUser()->getUserName() . microtime());
                    $reader = IOFactory::createReader('Xlsx');
                    $spreadsheet = $reader->load('job_tmpl_filter.xlsx');
// Set document properties
                    $index = 3;
                    foreach ($report as $rep) {
                        $spreadsheet->getActiveSheet()
                            ->setCellValue('B' . $index, $rep->getDoneJobDate()->format('Y-m-d'))
                            ->setCellValue('C' . $index, $rep->getSectionId())
                            ->setCellValue('D' . $index, $rep->getRoadSectionBegin())
                            ->setCellValue('E' . $index, $rep->getRoadSectionEnd())
                            ->setCellValue('F' . $index, $rep->getJobId())
                            ->setCellValue('G' . $index, $rep->getJobName())
                            ->setCellValue('H' . $index, $rep->getUnitOf())
                            ->setCellValue('I' . $index, $rep->getQuantity())
                            ->setCellValue('J' . $index, $this->getSubunitNameById($rep->getSubUnitId()));
                        $index++;
                    }
                    $spreadsheet->getActiveSheet()->setAutoFilter('A2:J'. $index);
                    $spreadsheet->getActiveSheet()
                        ->getPageSetup()
                        ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $spreadsheet->getActiveSheet()
                        ->getPageSetup()
                        ->setPaperSize(PageSetup::PAPERSIZE_A4);
// Rename worksheet
                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    $writer->save('files/' . $fileName . '.xlsx');
                    return $this->file(('files/' . $fileName . '.xlsx'));
                }

                return $this->render('reports/index.html.twig', ['form' => $form->createView(), 'report' => $report]);
            } else {
                return $this->render('reports/index.html.twig', ['form' => $form->createView(), ['report' => null]]);
            }
        }
    }



    /**
     * @Route("/reports/filter/inspection", name="reports_filter_inspection")
     */
    public function reportWithFilterInspection(LdapUserRepository $ldapUserRepository, Request $request, AuthorizationCheckerInterface $authChecker)
    {
        $username = $this->getUser()->getUserName();
        if (!$ldapUserRepository->findUnitIdByUserName($username)->getSubunit()) {
            $this->addFlash(
                'danger',
                'Jūs nepasirinkęs kelių tarnybos!'
            );
            return $this->redirectToRoute('ldap_user_index');
        } else {

            $form = $this->createForm(ReportType::class);
            $subUnitId = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getId();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $from = $form->get('From')->getData();
                $to = $form->get('To')->getData();
                $userName = $this->getUser()->getUserName();

                $dql = '';

                if ($this->isGranted('ADMIN')) {
                    $dql = "SELECT i FROM App:Inspection i WHERE (i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.id DESC";
                }
                elseif ($this->isGranted('SUPER_VIEWER')){
                    $dql = "SELECT i FROM App:Inspection i WHERE (i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.id DESC";
                }
                elseif ($this->isGranted('UNIT_VIEWER')) {
                    $dql = "SELECT i FROM App:Inspection i WHERE (i.SubUnitId = '$subUnitId'AND i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.id ASC";
                }
                elseif ($this->isGranted('SUPER_MASTER')) {
                    $dql = "SELECT i FROM App:Inspection i WHERE (i.SubUnitId = '$subUnitId' AND i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.id ASC";
                }
                elseif ($this->isGranted('ROAD_MASTER')) {
                    $dql = "SELECT i FROM App:Inspection i WHERE (i.SubUnitId = '$subUnitId' AND i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.id DESC";
                }
                elseif($this->isGranted('WORKER') ) {
                    $dql = "SELECT i FROM App:Inspection i WHERE (i.Username = '$userName' AND i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.id ASC";
                }

                $em = $this->get('doctrine.orm.entity_manager');
                $query = $em->createQuery($dql);
                $report = $query->execute();
                $html = $this->renderView('reports/report_inspections_filter.html.twig', ['report' => $report]);

                if ($form->get('GeneratePDF')->isClicked()) {
                    return new PdfResponse(
                        $this->get('knp_snappy.pdf')->getOutputFromHtml($html, ['orientation' => 'Landscape']),
                        'file.pdf'
                    );
                }


                if ($form->get('GenerateXLS')->isClicked()) {
                    $fileName = md5($this->getUser()->getUserName() . microtime());
                    $reader = IOFactory::createReader('Xlsx');
                    $spreadsheet = $reader->load('inspection_tmpl_filter_2.xlsx');
// Set document properties
                    $spreadsheet->getProperties()->setCreator($this->getUser()->getUserName())
                        ->setLastModifiedBy('VĮ Kelių priežiūra')
                        ->setTitle('Atliktų darbų ataskaita')
                        ->setSubject('Atliktų darbų ataskaita')
                        ->setDescription('Atliktų darbų ataskaita')
                        ->setKeywords('Atliktų darbų ataskaita')
                        ->setCategory('Atliktų darbų ataskaita');
                    $index = 2;
                    $dateNow = new \DateTime('now');
                    $styleArray = ['font' => ['bold' => false]];
                    foreach ($report as $rep) {
                        $spreadsheet->getActiveSheet()
                            ->setCellValue('A' . $index, $rep->getRoadId())
                            ->setCellValue('B' . $index, $rep->getRoadSectionBegin())
                            ->setCellValue('C' . $index, $rep->getRoadSectionEnd())
                            ->setCellValue('D' . $index, $rep->getNote());

                        if($rep->getIsAdditional()===true){
                            $spreadsheet->getActiveSheet()
                                ->setCellValue('E' . $index, 'Papildoma');
                        }
                        else{
                            $spreadsheet->getActiveSheet()
                                ->setCellValue('E' . $index, 'Patrulinė');
                        }
                        $spreadsheet->getActiveSheet()
                            ->setCellValue('F' . $index, $rep->getRoadCondition())
                            ->setCellValue('G' . $index, $rep->getWaveSize())
                            ->setCellValue('H' . $index, $rep->getplace())
                            ->setCellValue('I' . $index, $rep->getRepairDate()->format('Y-m-d'))
                            ->setCellValue('J' . $index, $this->getSubunitNameById($rep->getSubUnitId()));
                        $index++;
                    }
                    $spreadsheet->getActiveSheet()->setAutoFilter('A1:J'. $index);
                    $spreadsheet->getActiveSheet()
                        ->getPageSetup()
                        ->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
                    $spreadsheet->getActiveSheet()
                        ->getPageSetup()
                        ->setPaperSize(PageSetup::PAPERSIZE_A4);
// Rename worksheet
                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    $writer->save('files/' . $fileName . '.xlsx');
                    return $this->file(('files/' . $fileName . '.xlsx'));
                }


                return $this->render('reports/index_inspections_filter.html.twig', ['form' => $form->createView(), 'report' => $report]);
            } else {
                return $this->render('reports/index_inspections_filter.html.twig', ['form' => $form->createView(), ['report' => null]]);
            }
        }
    }



    /**
     * @Route("/reports/restriction", name="reports_restrictions")
     */
    public function reportRestrictions(LdapUserRepository $ldapUserRepository, Request $request, AuthorizationCheckerInterface $authChecker)
    {
        $username = $this->getUser()->getUserName();
        if (!$ldapUserRepository->findUnitIdByUserName($username)->getSubunit()) {
            $this->addFlash(
                'danger',
                'Jūs nepasirinkęs kelių tarnybos!'
            );
            return $this->redirectToRoute('ldap_user_index');
        } else {

            $form = $this->createForm(ReportType::class);
            $subUnitId = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getId();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $from = $form->get('From')->getData();
                $to = $form->get('To')->getData();
                $username = $this->getUser()->getUserName();

                $dql = '';
                $dql = "SELECT r FROM App:Restriction r WHERE (r.DateTo >= '$from') ORDER BY r.DateFrom ASC";

                $em = $this->get('doctrine.orm.entity_manager');
                $query = $em->createQuery($dql);
                $report = $query->execute();

                if ($form->get('GeneratePDF')->isClicked()) {
                    $html = $this->renderView('reports/report.html.twig', ['report' => $report]);
                    return new PdfResponse(
                        $this->get('knp_snappy.pdf')->getOutputFromHtml($html, ['orientation' => 'Landscape']),
                        'file.pdf'
                    );
                }
                if ($form->get('GenerateXLS')->isClicked()) {
                    $fileName = md5($this->getUser()->getUserName() . microtime());
                    $reader = IOFactory::createReader('Xlsx');
                    $spreadsheet = $reader->load('restriction_tmpl.xlsx');
// Set document properties
                    $index = 4;
                    $dateNow = new \DateTime('now');
                    foreach ($report as $rep) {
                        $spreadsheet->getActiveSheet()
                            ->setCellValue('A' . $index,  $dateNow->format('Y-m-d'))
                            ->setCellValue('B' . $index, $rep->getRoadId())
                            ->setCellValue('C' . $index, $rep->getRoadName())
                            ->setCellValue('D' . $index, $rep->getSectionBegin())
                            ->setCellValue('E' . $index, $rep->getSectionEnd())
                            ->setCellValue('F' . $index, $rep->getPlace())
                            ->setCellValue('G' . $index, $rep->getJobs())
                            ->setCellValue('H' . $index, $rep->getRestrictions())
                            ->setCellValue('I' . $index, $rep->getDateFrom()->format('Y-m-d'))
                            ->setCellValue('J' . $index, $rep->getDateTo()->format('Y-m-d'))
                            ->setCellValue('K' . $index, $rep->getSubunit())
                            ->setCellValue('L' . $index, $rep->getContractor())
                            ->setCellValue('M' . $index, $rep->getNotes())
                        ;
                        $spreadsheet->getActiveSheet()
                            ->getStyle($index)
                            ->getAlignment()
                            ->setWrapText(true);
                        $index++;
                    }
// Rename worksheet
                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    $writer->save('files/' . $fileName . '.xlsx');
                    return $this->file(('files/' . $fileName . '.xlsx'));
                }

                return $this->render('reports/index_restrictions.html.twig', ['form' => $form->createView(), 'report' => $report]);
            } else {
                return $this->render('reports/index_restrictions.html.twig', ['form' => $form->createView(), ['report' => null]]);
            }
        }
    }

    function deleteElement($element, &$array){
        $index = array_search($element, $array);
        if($index !== false){
            unset($array[$index]);
        }
    }

    /**
     * @Route("/reports/wintermaintenance/LAKD", name="wintermaintenance_LAKD")
     */
    public function wintermaintenanceReportToLAKD (LdapUserRepository $ldapUserRepository, Request $request) {

        $username = $this->getUser()->getUserName();
        $subunit = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getSubunitId();
        $shouldCreateEmptySubunits = false;

        if (!$ldapUserRepository->findUnitIdByUserName($username)->getSubunit()) {
            $this->addFlash(
                'danger',
                'Jūs nepasirinkęs kelių tarnybos!'
            );
            return $this->redirectToRoute('ldap_user_index');
        }

        $form = $this->createForm(LAKDReportType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $date = $form->get('Date')->getData();
            $reportFor = $form->get('reportFor')->getData();
            $dql = '';

            $em = $this->get('doctrine.orm.entity_manager');
            $dql = "SELECT s FROM App:Subunit s";
            $subunits = array();
            foreach ($em->createQuery($dql)->execute() as $item)
            {
                $subunits[$item->getSubunitId()] = $item->getName();
            }

            //$dql = "SELECT r FROM App:WinterMaintenance r WHERE AND (r.CreatedAt = '$date' AND r.ReportFor = '$reportFor') ORDER BY r.Subunit";

            $dql = '';

            if ($this->isGranted('ADMIN')) {
                $dql = "SELECT w FROM App:WinterMaintenance w WHERE (w.CreatedAt = '$date' AND w.ReportFor = '$reportFor') ORDER BY w.CreatedAt DESC";
                $shouldCreateEmptySubunits = true;
            }
            elseif ($this->isGranted('SUPER_VIEWER')){
                $dql = "SELECT w FROM App:WinterMaintenance w WHERE (w.CreatedAt = '$date' AND w.ReportFor = '$reportFor') ORDER BY w.CreatedAt DESC";
                $shouldCreateEmptySubunits = true;
            }
            elseif ($this->isGranted('UNIT_VIEWER')) {
                $dql = "SELECT w FROM App:WinterMaintenance w WHERE w.Subunit = '$subunit' AND (w.CreatedAt = '$date' AND w.ReportFor = '$reportFor') ORDER BY w.CreatedAt DESC";
                $shouldCreateEmptySubunits = false;
            }
            elseif ($this->isGranted('SUPER_MASTER')) {
                $dql = "SELECT w FROM App:WinterMaintenance w WHERE w.Subunit = '$subunit' AND (w.CreatedAt = '$date' AND w.ReportFor = '$reportFor') ORDER BY w.CreatedAt DESC";
                $shouldCreateEmptySubunits = false;
            }
            elseif ($this->isGranted('ROAD_MASTER')) {
                $dql = "SELECT w FROM App:WinterMaintenance w WHERE w.Subunit = '$subunit' AND (w.CreatedAt = '$date' AND w.ReportFor = '$reportFor') ORDER BY w.CreatedAt DESC";
                $shouldCreateEmptySubunits = false;
            }
            elseif($this->isGranted('WORKER') ) {
                $dql = "SELECT w FROM App:WinterMaintenance w WHERE w.Subunit = '$subunit' AND (w.CreatedAt = '$date' AND w.ReportFor = '$reportFor') ORDER BY w.CreatedAt DESC";
                $shouldCreateEmptySubunits = false;
            }

            $query = $em->createQuery($dql);
            $report = $query->execute();

            if($shouldCreateEmptySubunits) {
                $dql = "SELECT w FROM App:Subunit w";
                $array = array();
                $subunitArray = $em->createQuery($dql)->execute();

                unset($subunitArray[count($subunitArray) - 1]);

                foreach ($subunitArray as $subunit) {
                    //Sukuriame WInterMaintanence obijekta, kad galetume sujunti array su report array
                    $obj = new WinterMaintenance();
                    $obj->setSubunit($subunit->getSubunitId());
                    $obj->setRoadConditionHighway(array());
                    $obj->setRoadConditionHighway2(array());
                    $obj->setRoadConditionHighway3(array());
                    $obj->setRoadConditionDistrict(array());
                    $obj->setRoadConditionDistrict2(array());
                    $obj->setRoadConditionDistrict3(array());
                    $obj->setRoadConditionLocal(array());
                    $obj->setRoadConditionLocal2(array());
                    $obj->setRoadConditionLocal3(array());
                    $obj->setWeather(array());

                    $array[] = $obj;
                }

                //Einame pro abu array
                foreach ($array as $empty) {
                    foreach ($report as $r) {
                        //Jei sutampa Subunit id tada mes is tuscio WinterMaintenance array istrinam reiksme kuri yra report array
                        if ($empty->getSubunit() == $r->getSubunit()) {
                            $this->deleteElement($empty, $array);
                        }
                    }
                }

                //Sujungiame
                $report = array_merge($report, $array);

                //Sortinam
                usort($report, function ($a, $b) {
                    return $a->getSubunit() > $b->getSubunit();
                });
            }

            if ($form->get('GenerateXLS')->isClicked()) {
                $fileName = md5($this->getUser()->getUserName() . microtime());
                $reader = IOFactory::createReader('Xlsx');
                $spreadsheet = $reader->load('ziemos_ataskaita_LAKD.xlsx');
                // Set document properties
                $index = 6;

                $dateNow = new \DateTime('now');

                foreach ($report as $rep) {

                    $weather = '';
                    $spreadsheet->getActiveSheet()->setCellValue('H1', 'Data: '. $dateNow->format('Y-m-d').' '. 'Laikas: ' . $rep->getReportFor().' val.' );
                    $spreadsheet->getActiveSheet()->setCellValue('A' . $index, $this->getRegionBySubunitId($rep->getSubunit()));
                    $spreadsheet->getActiveSheet()->setCellValue('B' . $index, $this->getSubunitName($rep->getSubunit()));
                    $highway = join(',', $rep->getRoadConditionHighway());
                    $highway2 = join(',', $rep->getRoadConditionHighway2());
                    $highway3 = join(',', $rep->getRoadConditionHighway3());
                    $local = join(',', $rep->getRoadConditionLocal());
                    $local2 = join(',', $rep->getRoadConditionLocal2());
                    $local3 = join(',', $rep->getRoadConditionLocal3());
                    $district = join(',', $rep->getRoadConditionDistrict());
                    $district2 = join(',', $rep->getRoadConditionDistrict2());
                    $district3 = join(',', $rep->getRoadConditionDistrict3());

                    $spreadsheet->getActiveSheet()->setCellValue('C'.$index,
                        'M1('.$highway.')'. 'M2('.$highway2.')'.'M3('.$highway3.')'.
                        'K1(' . $local. ')'. 'K2(' . $local2. ')'. 'K3(' . $local3. ')'.
                        'R1(' . $district. ')' . 'R2(' . $district2. ')'. 'R3(' . $district3. ')'
                    );
                    foreach ($rep->getWeather() as $item) {
                        $spreadsheet->getActiveSheet()->setCellValue('D'. $index, $weather = $weather . $item. ', ');
                    }
                    $spreadsheet->getActiveSheet()
                        ->setCellValue('E' . $index, $rep->getTrafficChanges())
                        ->setCellValue('F' . $index, $rep->getBlockedRoads())
                        ->setCellValue('G' . $index, $rep->getOtherEvents())
                        ->setCellValue('H' . $index, $rep->getMechanism())
                        ->setCellValue('I' . $index, $rep->getRoadConditionScore());
                    $index++;
                }

// Rename worksheet
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $writer->save('files/' . $fileName . '.xlsx');
                return $this->file(('files/' . $fileName . '.xlsx'));
            }

            return $this->render('reports/index_LAKD.html.twig', ['form' => $form->createView(), 'winter_maintenances' => $report,"subunits" => $subunits]);
        } else {
            return $this->render('reports/index_LAKD.html.twig', ['form' => $form->createView(), ['winter_maintenances' => null]]);
        }
    }


    public function getSubunitNameById($subUnitId){

        $em = $this->getDoctrine()->getRepository('App:Subunit');
        return $em->find($subUnitId)->getName();
    }

    public function getSubunitName($subUnitId){

        $em = $this->getDoctrine()->getRepository('App:Subunit');
        return $em->findOneBy(['SubunitId'=> $subUnitId])->getName();
    }

    public function getRegionId($subUnitId){

        $em = $this->getDoctrine()->getRepository('App:Subunit');
        return $em->find($subUnitId)->getUnitId();
    }

    public function getRegionBySubunitId($SubunitId) {

        $em2 = $this->getDoctrine()->getRepository('App:Region');
        return $em2->findOneBy(['SubunitId' => $SubunitId])->getRegionName();
    }
}
