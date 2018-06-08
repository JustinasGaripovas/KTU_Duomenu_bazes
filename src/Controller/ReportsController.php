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


class ReportsController extends Controller
{
    /**
     * @Route("/reports", name="reports")
     */

    public function index(LdapUserRepository $ldapUserRepository, Request $request, AuthorizationCheckerInterface $authChecker)
    {
        $username = $this->getUser()->getUserName();
        $form = $this->createForm(ReportType::class);
        $subUnitId = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getId();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->html='';
            $from = $form->get('From')->getData();
            $to = $form->get('To')->getData();
            $username = $this->getUser()->getUserName();
           /* if (false === $authChecker->isGranted('ROLE_ADMIN')) {
                $dql = "SELECT d FROM App:DoneJobs d WHERE (d.Username = '$username' AND d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.Date ASC";
            }

            else {
                $dql = "SELECT d FROM App:DoneJobs d WHERE (d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.Date ASC";
            }*/

            $em = $this->get('doctrine.orm.entity_manager');

            $dql = '';
            if (true === $authChecker->isGranted('ROLE_ADMIN')) {
            $dql = "SELECT d FROM App:DoneJobs d WHERE (d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.Date ASC";
            }
            elseif (true === $authChecker->isGranted('ROLE_ROAD_MASTER')) {
                $dql = "SELECT d FROM App:DoneJobs d WHERE (d.SubUnitId = '$subUnitId' AND d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.Date ASC";
            }
            elseif (true === $authChecker->isGranted('ROLE_KT_MASTER')) {
                $dql = "SELECT d FROM App:DoneJobs d WHERE (d.SubUnitId = '$subUnitId' AND d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.Date ASC";
            }
            elseif (true === $authChecker->isGranted('ROLE_KT_VIEWER')) {
                $dql = "SELECT d FROM App:DoneJobs d WHERE (d.SubUnitId = '$subUnitId' AND d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.Date ASC";
            }
            elseif (true === $authChecker->isGranted('ROLE_SUPER_VIEWER')){
                $dql = "SELECT d FROM App:DoneJobs d WHERE (d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.Date ASC";
            }
            elseif(true === $authChecker->isGranted('ROLE_WORKER') ) {
                $dql = "SELECT d FROM App:DoneJobs d WHERE (d.Username = '$username' AND d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.Date ASC";
            }
            $em = $this->get('doctrine.orm.entity_manager');
            $query = $em->createQuery($dql);
            $report = $query->execute();
            $html = $this->renderView('reports/report.html.twig', ['report' => $report]);

            if($form->get('GeneratePDF')->isClicked()) {
                return new PdfResponse(
                    $this->get('knp_snappy.pdf')->getOutputFromHtml($html, ['orientation' => 'Landscape']),
                    'file.pdf'
                );
            }

            return $this->render('reports/index.html.twig',['form' => $form->createView(), 'report' => $report]);
        }
        else {
            return $this->render('reports/index.html.twig', ['form' => $form->createView(), ['report' => null]]);
        }
    }

    /**
     * @Route("/reports/inspection", name="inspection_reports")
     */

    public function inspectionRepor(LdapUserRepository $ldapUserRepository,Request $request, AuthorizationCheckerInterface $authChecker)
    {
        $username = $this->getUser()->getUserName();
        $subUnitName = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getName();
        $subUnitId = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getId();
        $form = $this->createForm(ReportType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->html='';
            $from = $form->get('From')->getData();
            $to = $form->get('To')->getData();
            $em = $this->get('doctrine.orm.entity_manager');
            $dql = '';

            if (true === $authChecker->isGranted('ROLE_ROAD_MASTER')) {
                $dql = "SELECT i FROM App:Inspection i WHERE (i.SubUnitId = '$subUnitId' AND i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.Date DESC";
            }
            elseif (true === $authChecker->isGranted('ROLE_KT_MASTER')) {
                $dql = "SELECT i FROM App:Inspection i WHERE (i.SubUnitId = '$subUnitId' AND i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.RepairDate ASC";
            }
            elseif (true === $authChecker->isGranted('ROLE_KT_VIEWER')) {
                $dql = "SELECT i FROM App:Inspection i WHERE (i.SubUnitId = '$subUnitId'AND i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.RepairDate ASC";
            }
            elseif (true === $authChecker->isGranted('ROLE_SUPER_VIEWER')){
                $dql = "SELECT i FROM App:Inspection i WHERE (i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.id DESC";
            }
            elseif (true === $authChecker->isGranted('ROLE_ADMIN')) {
                $dql = "SELECT i FROM App:Inspection i WHERE (i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.id DESC";
            }
            elseif(true === $authChecker->isGranted('ROLE_WORKER') ) {
                $dql = "SELECT i FROM App:DoneJobs i WHERE (i.Username = '$username' AND i.RepairDate >= '$from' AND i.RepairDate <= '$to') ORDER BY i.RepairDate ASC";
            }
            $query = $em->createQuery($dql);
            $report = $query->execute();
            $html = $this->renderView('reports/report_inspections.html.twig', [
                'report' => $report,
                'subunit_name' =>$subUnitName
                ]);

            if($form->get('GeneratePDF')->isClicked()) {
                return new PdfResponse(
                    $this->get('knp_snappy.pdf')->getOutputFromHtml($html, ['orientation' => 'Portrait']),
                    'file.pdf'
                );
            }

            return $this->render('reports/index_inspections.html.twig',['form' => $form->createView(), 'report' => $report]);
        }
        else {
            return $this->render('reports/index_inspections.html.twig', ['form' => $form->createView(), ['report' => null]]);
        }
    }

    /**
     * @Route("/reports/jobs/road/level", name="report_done_jobs_by road_level")
     */

    public function sumReportsByRoadLevel(LdapUserRepository $ldapUserRepository, Request $request, AuthorizationCheckerInterface $authChecker){

        $username = $this->getUser()->getUserName();
        $subUnitName = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getName();
        $subUnitId = $ldapUserRepository->findUnitIdByUserName($username)->getSubunit()->getId();
        $form = $this->createForm(ReportType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->html='';
            $from = $form->get('From')->getData();
            $to = $form->get('To')->getData();
            $em = $this->get('doctrine.orm.entity_manager');
            $dql = '';

            if (true === $authChecker->isGranted('ROLE_ROAD_MASTER')) {
                $dql = "SELECT i.RoadLevel,i.JobId, i.JobName, i.UnitOf, SUM(i.Quantity) FROM App:DoneJobs i WHERE (i.SubUnitId = '$subUnitId' AND i.DoneJobDate >= '$from' AND i.DoneJobDate <= '$to') GROUP BY i.RoadLevel, i.JobId, i.JobName, i.UnitOf ORDER BY i.Date ASC";
            }
            elseif (true === $authChecker->isGranted('ROLE_KT_MASTER')) {
                $dql = "SELECT i.RoadLevel,i.JobId, i.JobName, i.UnitOf, SUM(i.Quantity) FROM App:DoneJobs i WHERE (i.SubUnitId = '$subUnitId' i.DoneJobDate >= '$from' AND i.DoneJobDate <= '$to') GROUP BY i.RoadLevel, i.JobId, i.JobName, i.UnitOf ORDER BY i.Date ASC";
            }
            elseif (true === $authChecker->isGranted('ROLE_KT_VIEWER')) {
                $dql = "SELECT i.RoadLevel,i.JobId, i.JobName, i.UnitOf, SUM(i.Quantity) FROM App:DoneJobs i WHERE (i.SubUnitId = '$subUnitId' i.DoneJobDate >= '$from' AND i.DoneJobDate <= '$to') GROUP BY i.RoadLevel, i.JobId, i.JobName, i.UnitOf ORDER BY i.Date ASC";
            }
            elseif (true === $authChecker->isGranted('ROLE_SUPER_VIEWER')){
                $dql = "SELECT i.RoadLevel,i.JobId, i.JobName, i.UnitOf, SUM(i.Quantity) FROM App:DoneJobs i WHERE (i.DoneJobDate >= '$from' AND i.DoneJobDate <= '$to') GROUP BY i.RoadLevel, i.JobId, i.JobName, i.UnitOf ORDER BY i.Date ASC";
            }
            elseif (true === $authChecker->isGranted('ROLE_ADMIN')) {
                $dql = "SELECT i.RoadLevel,i.JobId, i.JobName, i.UnitOf, SUM(i.Quantity) AS SumOfQuantity FROM App:DoneJobs i WHERE (i.DoneJobDate >= '$from' AND i.DoneJobDate <= '$to') GROUP BY i.RoadLevel, i.JobId, i.JobName, i.UnitOf";
            }
            elseif(true === $authChecker->isGranted('ROLE_WORKER') ) {
                $dql = "SELECT i.RoadLevel,i.JobId, i.JobName, i.UnitOf, SUM(i.Quantity) AS SumOfQuantity FROM App:DoneJobs i WHERE (i.Username = '$username' AND i.DoneJobDate >= '$from' AND i.DoneJobDate <= '$to') GROUP BY i.RoadLevel, i.JobId, i.JobName, i.UnitOf";
            }
            $query = $em->createQuery($dql);
            $report = $query->execute();
            $html = $this->renderView('reports/report_sum_level.html.twig', [
                'report' => $report,
                'subunit_name' =>$subUnitName
            ]);

            if($form->get('GeneratePDF')->isClicked()) {
                return new PdfResponse(
                    $this->get('knp_snappy.pdf')->getOutputFromHtml($html, ['orientation' => 'Portrait']),
                    'file.pdf'
                );
            }

            return $this->render('reports/index_sum_level.html.twig',['form' => $form->createView(), 'report' => $report]);
        }
        else {
            return $this->render('reports/index_sum_level.html.twig', ['form' => $form->createView(), ['report' => null]]);
        }
    }
}
