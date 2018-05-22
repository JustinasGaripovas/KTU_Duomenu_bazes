<?php

namespace App\Controller;

use App\Form\ReportType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;


class ReportsController extends Controller
{
    /**
     * @Route("/reports", name="reports")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(ReportType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $from = $form->get('From')->getData();
            $to = $form->get('To')->getData();
            $username = $this->getUser()->getUserName();
            $em = $this->get('doctrine.orm.entity_manager');
            $dql = "SELECT d FROM App:DoneJobs d WHERE (d.Username = '$username' AND d.DoneJobDate >= '$from' AND d.DoneJobDate <= '$to') ORDER BY d.Date ASC";
            $query = $em->createQuery($dql);
            $report = $query->execute();

            return $this->render('reports/index.html.twig', ['report' => $report]);
        }
       /*
        if ($request->get('do') === '1') {
            $fileName = sha1(time());
            $html = $this->renderView('reports/index.html.twig', ['report' => $report]);
            $this->get('knp_snappy.pdf')->generateFromHtml($html, ['orientation' => 'Landscape'], '/home/administrator/Sites/DAIS/files/' . $fileName . '.pdf');

            $this->addFlash(
                'notice',
                'Your file have been generated and saved to disk!'
            );

            return $this->render('reports/index.html.twig', ['report' => $report]);
        }
        if ($request->get('do') === '2') {
            $html = $this->renderView('reports/index.html.twig', ['report' => $report]);
            return new PdfResponse(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html, ['orientation' => 'Landscape']),
                'file.pdf'
            );
        }*/
        else {
            return $this->render('reports/form.html.twig', ['form' => $form->createView()]);
        }
    }
}
