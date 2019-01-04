<?php

namespace App\Controller;

use App\Entity\RoadSectionForWinterJobs;
use App\Form\RoadSectionForWinterJobsType;
use App\Repository\RoadSectionForWinterJobsRepository;
use App\Repository\SubunitRepository;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/winter/road/section")
 * @IsGranted("ADMIN")
 */
class WinterRoadSectionController extends AbstractController
{
    /**
     * @Route("/", name="winter_road_section")
     */
    public function index(Request $request ,RoadSectionForWinterJobsRepository $roadSectionForWinterJobsRepository): Response
    {
        if ($request->get('do') === '1') {
            $fileName = sha1(time());
            $html = $this->renderView('road_section_for_winter/index.html.twig', ['road_sections_winter' => $roadSectionForWinterJobsRepository->findAll()]);
            $this->get('knp_snappy.pdf')->generate($html, '/home/administrator/Sites/DAIS/files/'.$fileName.'.pdf');

            $this->addFlash(
                'notice',
                'Your file have been generated and saved to disk!'
            );

            return $this->render('road_section_for_winter/index.html.twig', ['road_sections_winter' => $roadSectionForWinterJobsRepository->findAll()]);
        }
        elseif ($request->get('do') === '2') {
            $html = $this->renderView('road_section_for_winter/index.html.twig', ['road_sections_winter' => $roadSectionForWinterJobsRepository->findAll()]);
            return new PdfResponse(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
                'file.pdf'
            );
        }
        else {
            return $this->render('road_section_for_winter/index.html.twig', ['road_sections_winter' => $roadSectionForWinterJobsRepository->findAll()]);
        }
    }

    /**
     * @Route("/new", name="winter_road_section_new", methods="GET|POST")
     */
    public function new(Request $request, SubunitRepository $subunitRepository): Response
    {
        $roadSection = new RoadSectionForWinterJobs();

        $form = $this->createForm(RoadSectionForWinterJobsType::class, $roadSection, ["subunit_choices"=>$this->subunitChoices($subunitRepository)]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $roadSection->setSectionLength($roadSection->getSectionEnd()-$roadSection->getSectionBegin());

            $em = $this->getDoctrine()->getManager();
            $em->persist($roadSection);
            $em->flush();

            return $this->redirectToRoute('winter_road_section');
        }

        return $this->render('road_section_for_winter/new.html.twig', [
            'road_section' => $roadSection,
            'form' => $form->createView(),
        ]);
    }

    private function subunitChoices(SubunitRepository $subunitRepository){
        $subunitChoices = array();

        foreach ($subunitRepository->findAll() as $subunit)
        {
            $subunitChoices[$subunit->getName()] = $subunit->getSubunitId();
        }

        return $subunitChoices;
    }

    /**
     * @Route("/{id}", name="winter_road_section_show", methods="GET")
     */
    public function show(RoadSectionForWinterJobs $roadSection): Response
    {
        return $this->render('road_section_for_winter/show.html.twig', ['road_section' => $roadSection]);
    }

    /**
     * @Route("/{id}/edit", name="winter_road_section_edit", methods="GET|POST")
     */
    public function edit(Request $request, RoadSectionForWinterJobs $roadSection, SubunitRepository $subunitRepository): Response
    {
        $form = $this->createForm(RoadSectionForWinterJobsType::class, $roadSection, ["subunit_choices"=>$this->subunitChoices($subunitRepository)]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('winter_road_section_edit', ['id' => $roadSection->getId()]);
        }

        return $this->render('road_section_for_winter/edit.html.twig', [
            'road_section' => $roadSection,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="winter_road_section_delete", methods="DELETE")
     */
    public function delete(Request $request, RoadSectionForWinterJobs $roadSection): Response
    {
        if ($this->isCsrfTokenValid('delete'.$roadSection->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($roadSection);
            $em->flush();
        }

        return $this->redirectToRoute('winter_road_section');
    }

}
