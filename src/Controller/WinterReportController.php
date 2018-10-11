<?php

namespace App\Controller;

use App\Form\WinterJobsReportType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class WinterReportController extends Controller
{
    /**
     * @Route("/winter/report/jobs", name="winter_report_jobs")
     */

    public function winterMaintenanceReportJobs (Request $request) {

        $form = $this->createForm(WinterJobsReportType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $from = $form->get('From')->getData();
            $to = $form->get('To')->getData();
            $dql = '';

            $dql = "SELECT r FROM App:WinterJobs r WHERE (r.Date >= '$from' AND r.Date <= '$to') ORDER BY r.Date ASC";

            $em = $this->get('doctrine.orm.entity_manager');
            $query = $em->createQuery($dql);
            $report = $query->execute();

            if ($form->get('GenerateXLS')->isClicked()) {
                $fileName = md5($this->getUser()->getUserName() . microtime());
                $reader = IOFactory::createReader('Xlsx');
                $spreadsheet = $reader->load('ziemos_ataskaita_LAKD.xlsx');

                dump($report);
// Set document properties
                }

// Rename worksheet
               // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                //$writer->save('files/' . $fileName . '.xlsx');
                //return $this->file(('files/' . $fileName . '.xlsx'));

            return $this->render('winter_report/index.html.twig', ['form' => $form->createView(), 'winter_jobs_report' => $report]);
        } else {
            return $this->render('winter_report/index.html.twig', ['form' => $form->createView(), ['winter_jobs_report' => null]]);
        }
    }


}
