<?php

namespace App\Controller;

use App\Form\WinterJobsReportType;
use App\Repository\LdapUserRepository;
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
                $spreadsheet = $reader->load('materials.xlsx');
                $index = 3;
                foreach ($report as $item){
                $spreadsheet->getActiveSheet()
                    ->setCellValue('A' . $index, $item->getSubunit())
                    ->setCellValue('B' . $index, $item->getDate()->format('Y-m-d'))
                    ->setCellValue('C' . $index, $item->getTimeFrom()->format('H:m'))
                    ->setCellValue('D' . $index, $item->getTimeTo()->format('H:m'))
                    ->setCellValue('E' . $index, $item->getMechanism())
                    ->setCellValue('F' . $index, $item->getJob());
                    if (sizeof($item->getRoadSections()) > 1) {
                        foreach ($item->getRoadSections() as $value) {
                            $spreadsheet->getActiveSheet()
                                ->setCellValue('G' . $index, $value->getSectionId())
                                ->setCellValue('H' . $index, $value->getSectionName())
                                ->setCellValue('I' . $index, $value->getSectionBegin())
                                ->setCellValue('J' . $index, $value->getSectionEnd())
                                ->setCellValue('K' . $index, $value->getSaltValue())
                                ->setCellValue('L' . $index, $value->getSandValue());
                            $index++;
                        }
                    }
                    else {
                            foreach ($item->getRoadSections() as $value) {
                                $spreadsheet->getActiveSheet()
                                    ->setCellValue('G' . $index, $value->getSectionId())
                                    ->setCellValue('H' . $index, $value->getSectionName())
                                    ->setCellValue('I' . $index, $value->getSectionBegin())
                                    ->setCellValue('J' . $index, $value->getSectionEnd())
                                    ->setCellValue('K' . $index, $value->getSaltValue())
                                    ->setCellValue('L' . $index, $value->getSandValue());
                                $index++;
                            }
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
     * @Route("/winter/report/materials/subunit", name="winter_report_materials_subunit")
     */

    public function winterMaintenanceReportMaterialSubunit (LdapUserRepository $ldapUserRepository ,Request $request) {

        $form = $this->createForm(WinterJobsReportType::class);
        $form->handleRequest($request);
        $subunit = $ldapUserRepository->findUnitIdByUserName($this->getUser()->getUserName())->getSubunit();
        if ($form->isSubmitted() && $form->isValid()) {
            $from = $form->get('From')->getData();
            $to = $form->get('To')->getData();
            $report = $this->getDaysMaterialsForSubunit($from, $to, $subunit);
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
     * @Route("/winter/report/mechanism", name="winter_report_mechanism")
     */

    public function winterMaintenanceReportMechanism (LdapUserRepository $ldapUserRepository, Request $request) {

        $form = $this->createForm(WinterJobsReportType::class);
        $form->handleRequest($request);
        $subunit = $ldapUserRepository->findUnitIdByUserName($this->getUser()->getUserName())->getSubunit();
        if ($form->isSubmitted() && $form->isValid()) {
            $from = $form->get('From')->getData();
            $to = $form->get('To')->getData();
            $report = $this->getDaysMechanism($from, $to);
            $arrayKeys = array_keys($report);
            if ($form->get('GenerateXLS')->isClicked()) {
                $fileName = md5($this->getUser()->getUserName() . microtime());
                $reader = IOFactory::createReader('Xlsx');
                $spreadsheet = $reader->load('materials.xlsx');
                $index = 3;
                $keyIndex = 0;
                foreach ($report as $rep) {
                    $spreadsheet->getActiveSheet()
                        ->setCellValue('A'. $index, $arrayKeys[$keyIndex])
                        ->setCellValue('B' . $index, $rep['Autogreideris'])
                        ->setCellValue('C' . $index, $rep['Traktorius'])
                        ->setCellValue('D' . $index, $rep['Sunkvežimis'])
                        ->setCellValue('E' . $index, $rep['Kiti']);
                    $index ++;
                    $keyIndex++;
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

}
