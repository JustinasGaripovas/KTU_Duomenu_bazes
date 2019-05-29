<?php

namespace App\Controller;

use App\Form\ReportWinterJobType;
use App\Repository\MechanismRepository;
use App\Repository\PersonRepository;
use App\Repository\SubunitRepository;
use App\Repository\WarehouseRepository;
use App\Repository\WinterJobRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    /**
     * @Route("/report", name="report")
     */
    public function index()
    {
        return $this->render('report/index.html.twig', [
            'controller_name' => 'ReportController',
        ]);
    }

    /**
     * @Route("/report/winter/jobs", name="report_winter_job")
     */
    public function winterReport(Request $request, EntityManagerInterface $em, WinterJobRepository $winterJobRepository)
    {
        $form = $this->createForm(ReportWinterJobType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->html = '';
            $from = $form->get('from')->getData()->format("Y-m-d");
            $to = $form->get('to')->getData()->format("Y-m-d")
            ;
            //TODO: Not working dates
            $report = $winterJobRepository->findForReport($from,$to);


            for ($i = 0 ;  $i < count($report); $i++)
            {
                $report[$i]["winterJobs"] = array();
                $report[$i]["winterJobs"] = $winterJobRepository->findWinterJobsBySubunit($from,$to,$report[$i]['id']);

            }

            return $this->render('report/winter_report.html.twig', ['form' => $form->createView(), 'report' => $report, 'exists' => true]);
        }

        return $this->render('report/winter_report.html.twig', ['form' => $form->createView(), 'report' => null, 'exists' => false]);

    }

    /**
     * @Route("/report/warehouse", name="report_warehouse")
     */
    public function warehouseReport(Request $request, EntityManagerInterface $em, WarehouseRepository $warehouseRepository, SubunitRepository $subunitRepository)
    {
        $form = $this->createForm(ReportWinterJobType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->html = '';
            $from = $form->get('from')->getData()->format("Y-m-d");
            $to = $form->get('to')->getData()->format("Y-m-d");

            $report = $warehouseRepository->findForReport($from,$to);


            for ($i = 0 ;  $i < count($report); $i++)
            {
                $report[$i]["warehouse"] = array();
                $report[$i]["warehouse"] = $warehouseRepository->findForReportWithSubunit($from,$to,$report[$i]['id']);
            }


            return $this->render('report/warehouse_report.html.twig', ['form' => $form->createView(), 'report' => $report, 'exists' => true]);
        }

        return $this->render('report/warehouse_report.html.twig', ['form' => $form->createView(), 'report' => null, 'exists' => false]);

    }


    /**
     * @Route("/report/mechanism", name="report_mechanism")
     */
    public function mechanismReport(Request $request, EntityManagerInterface $em, MechanismRepository $mechanismRepository)
    {
        $form = $this->createForm(ReportWinterJobType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->html = '';
            $from = $form->get('from')->getData()->format("Y-m-d");
            $to = $form->get('to')->getData()->format("Y-m-d");

            $report = $mechanismRepository->findForReport($from,$to);


            return $this->render('report/mechanism_report.html.twig', ['form' => $form->createView(), 'report' => $report, 'exists' => true]);
        }

        return $this->render('report/mechanism_report.html.twig', ['form' => $form->createView(), 'report' => null, 'exists' => false]);

    }

}
