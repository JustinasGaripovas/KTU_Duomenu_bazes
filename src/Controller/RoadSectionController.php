<?php

namespace App\Controller;

use App\Entity\RoadSection;
use App\Form\RoadSectionType;
use App\Repository\RoadSectionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;

/**
 * @Route("/road/section")
 * @IsGranted("ADMIN")
 */
class RoadSectionController extends Controller
{
    /**
     * @Route("/", name="road_section_index", methods="GET")
     */
    public function index(Request $request ,RoadSectionRepository $roadSectionRepository): Response
    {
        if ($request->get('do') === '1') {
            $fileName = sha1(time());
            $html = $this->renderView('road_section/index.html.twig', ['road_sections' => $roadSectionRepository->findAll()]);
            $this->get('knp_snappy.pdf')->generate($html, '/home/administrator/Sites/DAIS/files/'.$fileName.'.pdf');

            $this->addFlash(
                'notice',
                'Your file have been generated and saved to disk!'
            );

            return $this->render('road_section/index.html.twig', ['road_sections' => $roadSectionRepository->findAll()]);
        }
        elseif ($request->get('do') === '2') {
            $html = $this->renderView('road_section/index.html.twig', ['road_sections' => $roadSectionRepository->findAll()]);
            return new PdfResponse(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
                'file.pdf'
            );
        }
        else {
            return $this->render('road_section/index.html.twig', ['road_sections' => $roadSectionRepository->findAll()]);
        }

    }

    /**
     * @Route("/new", name="road_section_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $roadSection = new RoadSection();

        $this->denyAccessUnlessGranted('EDIT',$roadSection);

        $form = $this->createForm(RoadSectionType::class, $roadSection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($roadSection);
            $em->flush();

            return $this->redirectToRoute('road_section_index');
        }

        return $this->render('road_section/new.html.twig', [
            'road_section' => $roadSection,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="road_section_show", methods="GET")
     */
    public function show(RoadSection $roadSection): Response
    {
        $this->denyAccessUnlessGranted('SHOW',$roadSection);

        return $this->render('road_section/show.html.twig', ['road_section' => $roadSection]);
    }

    /**
     * @Route("/{id}/edit", name="road_section_edit", methods="GET|POST")
     */
    public function edit(Request $request, RoadSection $roadSection): Response
    {
        $this->denyAccessUnlessGranted('EDIT',$roadSection);

        $form = $this->createForm(RoadSectionType::class, $roadSection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('road_section_edit', ['id' => $roadSection->getId()]);
        }

        return $this->render('road_section/edit.html.twig', [
            'road_section' => $roadSection,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="road_section_delete", methods="DELETE")
     */
    public function delete(Request $request, RoadSection $roadSection): Response
    {
        $this->denyAccessUnlessGranted('DELETE',$roadSection);

        if ($this->isCsrfTokenValid('delete'.$roadSection->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($roadSection);
            $em->flush();
        }

        return $this->redirectToRoute('road_section_index');
    }
}
