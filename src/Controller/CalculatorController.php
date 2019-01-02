<?php

namespace App\Controller;

use App\Entity\RoadSectionForWinterJobs;
use App\Entity\WinterJobs;
use App\Form\ReportType;
use App\Form\ReportTypeForTimeDifferance;
use App\Utils\WinterJobTimeDifferenceObject;
use App\Utils\WinterJobTimeEntity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Tests\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CalculatorController
 * @package App\Controller
 * @IsGranted("ADMIN")
 */
class CalculatorController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
    /**
     * @Route("/calculator", name="calculator")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(ReportTypeForTimeDifferance::class);
        $form->handleRequest($request);

        $result = null;
        $allSubunits= null;
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->get('doctrine.orm.entity_manager');

            $from = $form->get('From')->getData();
            $filterSubunit = $form->get('FilterSubunit')->getData();
            $filterRoad = $form->get('FilterRoad')->getData();

            $dql = "SELECT w FROM App:WinterJobs w WHERE w.Date = '$from' ORDER BY w.Date DESC";
            $query = $em->createQuery($dql);

            $result = array();

            foreach ($query->execute() as $winterJob) {
                if($filterSubunit != null)
                if (strpos(strtolower($winterJob->getSubunitName()), strtolower($filterSubunit)) === false) {
                    continue;
                }

                foreach ($winterJob->getRoadSections() as $road) {

                    if($filterRoad != null)
                        if (strpos(strtolower($road->getSectionId()), strtolower($filterRoad)) === false) {
                      continue;
                    }

                    if($road->getSectionId() )
                    $roadId = $road->getSectionId();
                    if (isset($result[$roadId])) {
                        $result[$roadId]->addRoadSection(new WinterJobTimeEntity($winterJob->getSubunit(), $winterJob->getDate(), $winterJob->getTimeFrom(), $winterJob->getTimeTo()));
                    } else {
                        $result[$roadId] = new WinterJobTimeDifferenceObject($road, new WinterJobTimeEntity($winterJob->getSubunit(), $winterJob->getDate(), $winterJob->getTimeFrom(), $winterJob->getTimeTo()));
                    }
                }
            }



            foreach ($result as $item) {
                $item->sort();
            }


            $dql = "SELECT w FROM App:Subunit w";
            $query = $em->createQuery($dql)->execute();

            $allSubunits = array();

            foreach ($query as $item) {
                $allSubunits[$item->getSubunitId()] = $item->getName();
            }



            //dump($result);

        }

        //dump($result["A1"]);die;
        //dump($result);die;


        return $this->render('calculator/index.html.twig', ['form' => $form->createView(), 'results' => $result, 'subunits' => $allSubunits]);
    }



}
