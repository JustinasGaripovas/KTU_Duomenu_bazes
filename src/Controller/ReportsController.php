<?php

namespace App\Controller;

use App\Form\ReportType;
use App\Repository\LdapUserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Symfony\Component\Filesystem\Filesystem;


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
                if (true === $authChecker->isGranted('ROLE_ADMIN')) {
                    $dql = "SELECT d FROM App:DoneJobs d WHERE (d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.Date ASC";
                } elseif (true === $authChecker->isGranted('ROLE_ROAD_MASTER')) {
                    $dql = "SELECT d FROM App:DoneJobs d WHERE (d.SubUnitId = '$subUnitId' AND d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.Date ASC";
                } elseif (true === $authChecker->isGranted('ROLE_KT_MASTER')) {
                    $dql = "SELECT d FROM App:DoneJobs d WHERE (d.SubUnitId = '$subUnitId' AND d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.Date ASC";
                } elseif (true === $authChecker->isGranted('ROLE_KT_VIEWER')) {
                    $dql = "SELECT d FROM App:DoneJobs d WHERE (d.SubUnitId = '$subUnitId' AND d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.Date ASC";
                } elseif (true === $authChecker->isGranted('ROLE_SUPER_VIEWER')) {
                    $dql = "SELECT d FROM App:DoneJobs d WHERE (d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.Date ASC";
                } elseif (true === $authChecker->isGranted('ROLE_WORKER')) {
                    $dql = "SELECT d FROM App:DoneJobs d WHERE (d.Username = '$username' AND d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.Date ASC";
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
                    $spreadsheet = $reader->load('job_tmpl_3.xlsx');
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
                        $spreadsheet->getActiveSheet()->insertNewRowBefore($index, 1);
                        $spreadsheet->getActiveSheet()->setCellValue('F' . $index, $rep->getJobId());
                        $spreadsheet->getActiveSheet()->setCellValue('G' . $index, $rep->getJobName());
                        $spreadsheet->getActiveSheet()->setCellValue('H' . $index, $rep->getUnitOf());
                        $spreadsheet->getActiveSheet()->setCellValue('I' . $index, $rep->getQuantity());
                        $spreadsheet->getActiveSheet()->setCellValue('B' . $index, $rep->getDoneJobDate()->format('Y-m-d'));
                        $spreadsheet->getActiveSheet()
                            ->setCellValue('D' . $index, $rep->getSectionId() . '(' . $rep->getRoadSectionBegin() . '-' . $rep->getRoadSectionEnd() . ')');
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
                        $spreadsheet->getActiveSheet()->getStyle('A' . $index)->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()->getStyle('B' . $index)->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()->getStyle('C' . $index)->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()->getStyle('D' . $index)->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()->getStyle('E' . $index)->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()->getStyle('F' . $index)->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()->getStyle('G' . $index)->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()->getStyle('H' . $index)->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()->getStyle('I' . $index)->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()->getStyle('J' . $index)->applyFromArray($styleArray);
                        $index++;
                    }
                    $spreadsheet->getActiveSheet()->removeRow($index, 1);
                    //$spreadsheet->getActiveSheet()->setCellValue('A1', $report[0]);
                    // Set page orientation and size
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

                if (true === $authChecker->isGranted('ROLE_ROAD_MASTER')) {
                    $dql = "SELECT i FROM App:Inspection i WHERE (i.SubUnitId = '$subUnitId' AND i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.Id DESC";
                } elseif (true === $authChecker->isGranted('ROLE_KT_MASTER')) {
                    $dql = "SELECT i FROM App:Inspection i WHERE (i.SubUnitId = '$subUnitId' AND i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.Id ASC";
                } elseif (true === $authChecker->isGranted('ROLE_KT_VIEWER')) {
                    $dql = "SELECT i FROM App:Inspection i WHERE (i.SubUnitId = '$subUnitId'AND i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.Id ASC";
                } elseif (true === $authChecker->isGranted('ROLE_SUPER_VIEWER')) {
                    $dql = "SELECT i FROM App:Inspection i WHERE (i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.id DESC";
                } elseif (true === $authChecker->isGranted('ROLE_ADMIN')) {
                    $dql = "SELECT i FROM App:Inspection i WHERE (i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.id DESC";
                } elseif (true === $authChecker->isGranted('ROLE_WORKER')) {
                    $dql = "SELECT i FROM App:Inspection i WHERE (i.Username = '$userName' AND i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.Id ASC";
                }
                $query = $em->createQuery($dql);
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
                    $spreadsheet = $reader->load('inspection_tmpl_1.xlsx');
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
                    $spreadsheet->getActiveSheet()->setCellValue('A2', $dateNow->format('Y-m-d'));
                    $spreadsheet->getActiveSheet()->setCellValue('A3', 'VĮ "KELIŲ PRIEŽIŪRA" ' . $subUnitName . ' kelių tarnyba');
                    foreach ($report as $rep) {
                        $spreadsheet->getActiveSheet()->insertNewRowBefore($index, 1);
                        $spreadsheet->getActiveSheet()
                            ->setCellValue('A' . $index, $rep->getRoadId() . '(' . $rep->getRoadSectionBegin() . '-' . $rep->getRoadSectionEnd() . ')');
                        $spreadsheet->getActiveSheet()->setCellValue('B' . $index, $rep->getNote());
                        foreach ($rep->getJob() as $job) {
                            $spreadsheet->getActiveSheet()->setCellValue('C' . $index, $job->getDoneJobDate()->format('Y-m-d'));
                        }
                        $spreadsheet->getActiveSheet()
                            ->getRowDimension($index)
                            ->setRowHeight(40);
                        $spreadsheet->getActiveSheet()
                            ->getColumnDimension('B')->setWidth(40);
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
                        $spreadsheet->getActiveSheet()->getStyle('A' . $index)->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()->getStyle('B' . $index)->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()->getStyle('C' . $index)->applyFromArray($styleArray);
                        $index++;
                    }
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

                if (true === $authChecker->isGranted('ROLE_ROAD_MASTER')) {
                    $dql = "SELECT i.RoadLevel,i.JobId, i.JobName, i.UnitOf, SUM(i.Quantity) FROM App:DoneJobs i WHERE (i.SubUnitId = '$subUnitId' AND i.DoneJobDate >= '$from' AND i.DoneJobDate <= '$to') GROUP BY i.RoadLevel, i.JobId, i.JobName, i.UnitOf ORDER BY i.Date ASC";
                } elseif (true === $authChecker->isGranted('ROLE_KT_MASTER')) {
                    $dql = "SELECT i.RoadLevel,i.JobId, i.JobName, i.UnitOf, SUM(i.Quantity) FROM App:DoneJobs i WHERE (i.SubUnitId = '$subUnitId' i.DoneJobDate >= '$from' AND i.DoneJobDate <= '$to') GROUP BY i.RoadLevel, i.JobId, i.JobName, i.UnitOf ORDER BY i.Date ASC";
                } elseif (true === $authChecker->isGranted('ROLE_KT_VIEWER')) {
                    $dql = "SELECT i.RoadLevel,i.JobId, i.JobName, i.UnitOf, SUM(i.Quantity) FROM App:DoneJobs i WHERE (i.SubUnitId = '$subUnitId' i.DoneJobDate >= '$from' AND i.DoneJobDate <= '$to') GROUP BY i.RoadLevel, i.JobId, i.JobName, i.UnitOf ORDER BY i.Date ASC";
                } elseif (true === $authChecker->isGranted('ROLE_SUPER_VIEWER')) {
                    $dql = "SELECT i.RoadLevel,i.JobId, i.JobName, i.UnitOf, SUM(i.Quantity) FROM App:DoneJobs i WHERE (i.DoneJobDate >= '$from' AND i.DoneJobDate <= '$to') GROUP BY i.RoadLevel, i.JobId, i.JobName, i.UnitOf ORDER BY i.Date ASC";
                } elseif (true === $authChecker->isGranted('ROLE_ADMIN')) {
                    $dql = "SELECT i.RoadLevel,i.JobId, i.JobName, i.UnitOf, SUM(i.Quantity) AS SumOfQuantity FROM App:DoneJobs i WHERE (i.DoneJobDate >= '$from' AND i.DoneJobDate <= '$to') GROUP BY i.RoadLevel, i.JobId, i.JobName, i.UnitOf";
                } elseif (true === $authChecker->isGranted('ROLE_WORKER')) {
                    $dql = "SELECT i.RoadLevel,i.JobId, i.JobName, i.UnitOf, SUM(i.Quantity) AS SumOfQuantity FROM App:DoneJobs i WHERE (i.Username = '$username' AND i.DoneJobDate >= '$from' AND i.DoneJobDate <= '$to') GROUP BY i.RoadLevel, i.JobId, i.JobName, i.UnitOf";
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
                    $index = 6;
                    $dateNow = new \DateTime('now');
                    $styleArray = ['font' => ['bold' => false]];
                    $spreadsheet->getActiveSheet()->setCellValue('A4', $dateNow->format('Y-m-d'));
                    $spreadsheet->getActiveSheet()->setCellValue('A1', 'VĮ "KELIŲ PRIEŽIŪRA" ' . $subUnitName . ' KELIŲ TARNYBA');
                    foreach ($report as $rep) {
                        $spreadsheet->getActiveSheet()->insertNewRowBefore($index, 1);
                        $spreadsheet->getActiveSheet()->setCellValue('A' . $index, $rep['RoadLevel']);
                        $spreadsheet->getActiveSheet()->setCellValue('B' . $index, $rep['JobId']);
                        $spreadsheet->getActiveSheet()->setCellValue('C' . $index, $rep['JobName']);
                        $spreadsheet->getActiveSheet()->setCellValue('D' . $index, $rep['UnitOf']);
                        $spreadsheet->getActiveSheet()->setCellValue('E' . $index, $rep['SumOfQuantity']);
                        $spreadsheet->getActiveSheet()
                            ->getRowDimension($index)
                            ->setRowHeight(40);
                        $spreadsheet->getActiveSheet()
                            ->getColumnDimension('C')->setWidth(40);
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
                        $spreadsheet->getActiveSheet()->getStyle('A' . $index)->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()->getStyle('B' . $index)->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()->getStyle('C' . $index)->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()->getStyle('D' . $index)->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()->getStyle('E' . $index)->applyFromArray($styleArray);
                        $index++;
                    }
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
}


