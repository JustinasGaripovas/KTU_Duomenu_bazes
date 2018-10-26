<?php

namespace App\Controller;


use App\Form\FloodedRoadsReportType;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LdapUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class FloodedRoadsReportsController extends Controller
{
    /**
     * @Route("/report/flooded/roads/", name="flooded_roads_reports")
     */

    public function index(Request $request, LdapUserRepository $ldapUserRepository)
    {
        $username = $this->getUser()->getUserName();
        if (!$ldapUserRepository->findUnitIdByUserName($username)->getSubunit()) {
            $this->addFlash(
                'danger',
                'Jūs nepasirinkęs kelių tarnybos!'
            );
            return $this->redirectToRoute('ldap_user_index');
        }
        else {
            $form = $this->createForm(FloodedRoadsReportType::class);
            $subUnitId = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getName();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->html = '';
                $from = $form->get('From')->getData();
                $to = $form->get('To')->getData();
                $dateFrom = $from .' '.'00:00:00';
                $dateTo = $to .' '. '23:59:59';
                $username = $this->getUser()->getUserName();

                $dql = '';

                if ($this->isGranted('ADMIN')) {
                    $dql = "SELECT f FROM App:FloodedRoads f WHERE (f.CreatedAt BETWEEN '$dateFrom' AND '$dateTo') ORDER BY f.CreatedAt ASC";
                }
                elseif ($this->isGranted('SUPER_VIEWER')){
                    $dql = "SELECT f FROM App:FloodedRoads f WHERE (f.CreatedAt BETWEEN '$dateFrom' AND '$dateTo') ORDER BY f.CreatedAt ASC";
                }
                elseif ($this->isGranted('UNIT_VIEWER')) {
                    $dql = "SELECT f FROM App:FloodedRoads f WHERE f.SubunitId = '$subUnitId' AND (f.CreatedAt BETWEEN '$dateFrom' AND '$dateTo') ORDER BY f.CreatedAt DESC";
                }
                elseif ($this->isGranted('SUPER_MASTER')) {
                    $dql = "SELECT f FROM App:FloodedRoads f WHERE f.SubunitId = '$subUnitId' AND (f.CreatedAt BETWEEN '$dateFrom' AND '$dateTo') ORDER BY f.CreatedAt DESC";
                }
                elseif ($this->isGranted('ROAD_MASTER')) {
                    $dql = "SELECT f FROM App:FloodedRoads f WHERE f.SubunitId = '$subUnitId' AND (f.CreatedAt BETWEEN '$dateFrom' AND '$dateTo') ORDER BY f.CreatedAt DESC";
                }
                elseif($this->isGranted('WORKER') ) {
                    $dql = "SELECT f FROM App:FloodedRoads f WHERE f.CreatedBy = '$username' AND (f.CreatedAt BETWEEN '$dateFrom' AND '$dateTo') ORDER BY f.CreatedAt DESC";
                }

                    $em = $this->get('doctrine.orm.entity_manager');
                    $query = $em->createQuery($dql);
                    $report = $query->execute();

                if ($form->get('GenerateXLS')->isClicked()) {
                    $fileName = md5($this->getUser()->getUserName() . microtime());
                    $reader = IOFactory::createReader('Xlsx');
                    $spreadsheet = $reader->load('flooded_report.xlsx');
                    $date = new \DateTime('now');
                    $spreadsheet->getActiveSheet()->setCellValue('A5', $date);
                    $index = 9;
                    foreach ($report as $rep) {
                            $spreadsheet->getActiveSheet()
                                ->setCellValue('B' . $index, $rep->getRoadId())
                                ->setCellValue('C' . $index, $rep->getRoadName())
                                ->setCellValue('D' . $index, $rep->getSectionBegin())
                                ->setCellValue('E' . $index, $rep->getSectionEnd())
                                //->setCellValue('F' . $index, $rep->getSectionBegin())
                                ->setCellValue('G' . $index, $rep->getWaterDeep())
                                ->setCellValue('H' . $index, $rep->getNotes())
                                ->setCellValue('H' . $index, $rep->getStatus());
                            $index++;
                    }
                    $spreadsheet->getActiveSheet()
                        ->getPageSetup()
                        ->setPaperSize(PageSetup::PAPERSIZE_A4);
// Rename worksheet
                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    $writer->save('files/' . $fileName . '.xlsx');
                    return $this->file(('files/' . $fileName . '.xlsx'));
                }
                return $this->render('flooded_roads_reports/index.html.twig', ['form' => $form->createView(), 'report' => $report]);
            } else {
                return $this->render('flooded_roads_reports/index.html.twig', ['form' => $form->createView(), ['report' => null]]);
            }
        }
    }
}
