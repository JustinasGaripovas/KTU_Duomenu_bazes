<?php

namespace App\Controller;

use App\Entity\LdapUser;
use App\Entity\RoadSectionForWinterJobs;
use App\Entity\Structure;
use App\Form\StructureType;
use App\Repository\DoneJobsRepository;
use App\Repository\FloodedRoadsRepository;
use App\Repository\InspectionRepository;
use App\Repository\LdapUserRepository;
use App\Repository\RoadSectionForWinterJobsRepository;
use App\Repository\StructureRepository;
use App\Repository\SubunitRepository;
use App\Repository\WinterJobsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

//TODO: Pries paskutini aktyvu lvl (click)
//TODO: Kolkas vienas output tipas KT (integer)
//TODO: Dropdowns kolkas neaktyvus

/**
 * Class StructureController
 * @package App\Controller
 * @IsGranted("ADMIN")
 */
class StructureController extends Controller
{
    const ITEMS_PER_PAGE = 12;

    private $data;
    private $result = array();

    /**
     * @Route("/structure", name="structure_index")
     */
    public function index(StructureRepository $structureRepository)
    {
        return $this->render('structure/index.html.twig', [
            'structure' => $this->findStructure($structureRepository,"KP"),
        ]);
    }

    /**
     * @Route("/structure/new", name="structure_new", methods="GET|POST")
     */
    public function new(StructureRepository $structureRepository, LdapUserRepository $ldapUserRepository,SubunitRepository $subunitRepository,Request $request): Response
    {
        $dql = "SELECT i.Name FROM App:Structure i WHERE i.InformationType != 0 AND i.InformationType !=2 ORDER BY i.id DESC";
        $em = $this->get('doctrine.orm.entity_manager');
        $query = $em->createQuery($dql);

        $master_choice = array();

        foreach ($query->execute() as $item)
        {
            $master_choice[(string)$item["Name"]] = $item["Name"];
        }

        $information_choice = array();
        $information_choice["Tarnyba"] = 2;
        $information_choice["Regionas"] = 1;
        $information_choice["Tarnyba su mažesnėmis tarnybomis"] = 3;

        $username = $this->getUser()->getUserName();
        if (!$ldapUserRepository->findUnitIdByUserName($username)->getSubunit()) {
            $this->addFlash(
                'danger',
                'Jūs nepasirinkęs kelių tarnybos!'
            );
            return $this->redirectToRoute('ldap_user_index');
        }

        $structure = new Structure();
        $form = $this->createForm(StructureType::class, $structure, ['master_choice' => $master_choice, 'information_choice' =>$information_choice]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->get('doctrine.orm.entity_manager');

            $dql = "SELECT w FROM App:Structure w WHERE w.InformationType = 1 ORDER BY w.StructureId DESC";
            $structure->setStructureId($em->createQuery($dql)->execute()[0]->getStrutureId()+1);

            $em = $this->getDoctrine()->getManager();
            $em->persist($structure);
            $em->flush();
            return $this->redirectToRoute('structure_new');
        }

        return $this->render('structure/new.html.twig', [
            'structure' => $structure,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/structure/{id}/edit", name="structure_edit", methods="GET|POST")
     */
    public function edit(LdapUserRepository $ldapUserRepository, Request $request, Structure $structure, StructureRepository $structureRepository): Response
    {
        $form = $this->createForm(StructureType::class, $structure, ['master_choice' => $structureRepository->findAll()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mechanism_edit', ['id' => $structure->getId()]);
        }

        return $this->render('structure/edit.html.twig', [
            'structure' => $structure,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/structure/{id}", name="structure_delete", methods="DELETE")
     */
    public function delete(Request $request, Structure $structure): Response
    {
        if ($this->isCsrfTokenValid('delete'.$structure->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($structure);
            $em->flush();
        }

        return $this->redirectToRoute('structure_index');
    }

    public function findStructure(StructureRepository $structureRepository, $input)
    {
        $this->data = $structureRepository->findAll();

        $structure = $structureRepository->findByMaster($input);

        foreach ($structure as $entity)
        {
            $this->findBelow($entity);
        }

        $encoders = array(new JsonEncode());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($structure, 'json');

        return $jsonContent;
    }

    /**
     * @Route("/structure/test/{slug}", name="structure_inner")
     */
    public function show(StructureRepository $structureRepository, $slug)
    {
        return $this->render('structure/index.html.twig', [
            'current_name' => $slug,

            'structure' => $this->findStructure($structureRepository,$slug),
        ]);
    }

    /**
     * @Route("/structure/ajax/users", name="structure_ajax_user")
     */
    public function ajaxAction(SubunitRepository $subunitRepository,LdapUserRepository $ldapUserRepository, StructureRepository $structureRepository, Request $request) {

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $data = $request->request->get('name');

            $allUsers =  $ldapUserRepository->findAllById($subunitRepository->findOneByName($data)->getSubunitId());

            $jsonData = array();
            $idx = 0;

            if(empty($allUsers) && $allUsers != null) {
                foreach ($allUsers as $item) {
                    $temp = array(
                        'name' => $item->getName()
                    );
                    $jsonData[$idx++] = $temp;
                }
            }

            return new JsonResponse($jsonData);
        } else {
            return $this->render('structure/content.html.twig');
        }
    }

    /**
     * @Route("/structure/ajax/winter_roads", name="structure_ajax_winter_roads")
     */
    public function ajaxActionRoads(SubunitRepository $subunitRepository,RoadSectionForWinterJobsRepository $roadSectionForWinterJobsRepository, StructureRepository $structureRepository, Request $request) {

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $data = $request->request->get('name');
            $pages = $request->request->get('page');

            $allWinterRoads = $roadSectionForWinterJobsRepository->findWithPages(self::ITEMS_PER_PAGE * $pages, self::ITEMS_PER_PAGE);

            $jsonData = array();
            $idx = 0;
            foreach($allWinterRoads as $item) {
                $temp = array(
                    'section_id' => $item->getSectionId(),
                    'section_begin' => $item->getSectionBegin(),
                    'section_end' => $item->getSectionEnd()
                );
                $jsonData[$idx++] = $temp;
            }

            $jsonArray= array();
            $jsonArray[0] =  floor($roadSectionForWinterJobsRepository->findCount()[0][1]/self::ITEMS_PER_PAGE);
            $jsonArray[1] = $jsonData;

            return new JsonResponse($jsonArray);
        } else {
            return $this->render('structure/content.html.twig');
        }
    }
    /**
     * @Route("/structure/user/{slug}", name="user_show")
     */
    public function userActivityShow(LdapUserRepository $ldapUserRepository, DoneJobsRepository $doneJobsRepository, WinterJobsRepository $winterJobsRepository,
                                     InspectionRepository $inspectionRepository, FloodedRoadsRepository $floodedRoadsRepository, $slug)
    {
        return $this->render('structure/user.html.twig', [
            'user' => $ldapUserRepository->findUnitIdByUserName($slug),
            'inspections' => $inspectionRepository->findByUserName($slug),
            'done_jobs' => $doneJobsRepository->findByUserName($slug),
            'winter_jobs' => $winterJobsRepository->findByUserName($slug),
            'flooded_roads' => $floodedRoadsRepository->findByUserName($slug)

        ]);
    }

    public function findBelow($structure)
    {
        if($structure == null)
            return;

        foreach ($this->data as $slave)
        {
            if($slave->getMaster() == $structure->getName())
            {
                array_push($structure->array,$slave);
                $this->findBelow($slave);
            }
        }
    }

}
