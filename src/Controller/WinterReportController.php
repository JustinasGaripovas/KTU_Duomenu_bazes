<?php

namespace App\Controller;

use App\Form\WinterJobsReportType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Utils\MaterialReportObject;
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

            return $this->render('winter_report/winter_jobs_report.html.twig', ['form' => $form->createView(), 'winter_jobs_report' => $report]);
        } else {
            return $this->render('winter_report/winter_jobs_report.html.twig', ['form' => $form->createView(), ['winter_jobs_report' => null]]);
        }
    }


    /**
     * @Route("/winter/report/materials", name="winter_report_materials")
     */

    public function winterMaintenanceReportMaterial (Request $request) {

        $form = $this->createForm(WinterJobsReportType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $from = $form->get('From')->getData();
            $to = $form->get('To')->getData();
            $report = $this->getDaysMaterials($from, $to);
            if ($form->get('GenerateXLS')->isClicked()) {
                $fileName = md5($this->getUser()->getUserName() . microtime());
                $reader = IOFactory::createReader('Xlsx');
                $spreadsheet = $reader->load('materials.xlsx');
                $index = 3;
                foreach ($report as $rep) {
                    if (!empty($rep)){
                        foreach ( $rep as $item){
                            $spreadsheet->getActiveSheet()
                                ->setCellValue('A' . $index, $item->getName())
                                ->setCellValue('B' . $index, $item->getSectionId())
                                ->setCellValue('C' . $index, $item->getSaltValue())
                                ->setCellValue('D' . $index, $item->getSandValue());
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
     * @param $start -> Pradine data nuo kada ieskosim
     * @param $end -> Galutine data nuo kada ieskosim
     * @return array -> Grazinamas visu subunit array
     *         $this->getDaysMaterials(new \DateTime(),new \DateTime('-500 days'),1);

     */
    private function getDaysMaterials($start,$end)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        //Suformatuojam data kad galetume ja naudoti DQL
       // $start = $start->format('Y-m-d');
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

}
