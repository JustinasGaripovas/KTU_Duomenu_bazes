<?php

namespace App\Controller;

use App\Form\ReportType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


class ReportsController extends Controller
{
    /**
     * @Route("/reports", name="reports")
     */

    public function index(Request $request, AuthorizationCheckerInterface $authChecker)
    {
        $form = $this->createForm(ReportType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->html='';
            $from = $form->get('From')->getData();
            $to = $form->get('To')->getData();
            $username = $this->getUser()->getUserName();
            $em = $this->get('doctrine.orm.entity_manager');
            if (false === $authChecker->isGranted('ROLE_ADMIN')) {
                $dql = "SELECT d FROM App:DoneJobs d WHERE (d.Username = '$username' AND d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.Date ASC";
            }

            else {
                $dql = "SELECT d FROM App:DoneJobs d WHERE (d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.Date ASC";
            }
            $query = $em->createQuery($dql);
            $report = $query->execute();
            $html = $this->renderView('reports/report.html.twig', ['report' => $report]);

            if($form->get('GeneratePDF')->isClicked()) {
                return new PdfResponse(
                    $this->get('knp_snappy.pdf')->getOutputFromHtml($html, ['orientation' => 'Landscape']),
                    'file.pdf'
                );
            }

            /*if ($request->get('do') === '1') {
                $fileName = sha1(time());
                $html = $this->renderView('reports/report.html.twig', ['report' => $report]);
                $this->get('knp_snappy.pdf')->generateFromHtml($html, ['orientation' => 'Landscape'], '/home/administrator/Sites/DAIS/files/' . $fileName . '.pdf');

                $this->addFlash(
                    'notice',
                    'Your file have been generated and saved to disk!'
                );
            }*/
            /*if ($request->get('do') === '2') {
                return new PdfResponse(
                    $this->get('knp_snappy.pdf')->getOutputFromHtml($html, ['orientation' => 'Landscape']),
                    'file.pdf'
                );
            }*/

            return $this->render('reports/index.html.twig',['form' => $form->createView(), 'report' => $report]);
        }
        else {
            return $this->render('reports/index.html.twig', ['form' => $form->createView(), ['report' => null]]);
        }
    }

    /**
     * @Route("/reports/inspection", name="inspection_reports")
     */

    public function inspectionRepor(Request $request, AuthorizationCheckerInterface $authChecker)
    {
        $form = $this->createForm(ReportType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->html='';
            $from = $form->get('From')->getData();
            $to = $form->get('To')->getData();
            $username = $this->getUser()->getUserName();
            $em = $this->get('doctrine.orm.entity_manager');
            if (false === $authChecker->isGranted('ROLE_ADMIN')) {
                $dql = "SELECT d FROM App:Inspection d WHERE (d.Username = '$username' AND d.RepairDate >= '$from' AND d.RepairDate <= '$to') ORDER BY d.RepairDate ASC";            }

            else {
                $dql = "SELECT d FROM App:Inspection d WHERE (d.RepairDate >= '$from' AND d.RepairDate <= '$to') ORDER BY d.RepairDate ASC";
            }
            $query = $em->createQuery($dql);
            $report = $query->execute();
            $html = $this->renderView('reports/report_inspections.html.twig', ['report' => $report]);

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
}
