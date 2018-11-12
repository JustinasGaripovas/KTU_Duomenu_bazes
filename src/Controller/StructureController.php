<?php

namespace App\Controller;

use App\Entity\LdapUser;
use App\Entity\Structure;
use App\Repository\DoneJobsRepository;
use App\Repository\FloodedRoadsRepository;
use App\Repository\InspectionRepository;
use App\Repository\LdapUserRepository;
use App\Repository\StructureRepository;
use App\Repository\SubunitRepository;
use App\Repository\WinterJobsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    private $data;
    private $result = array();

    /**
     * @Route("/structure", name="structure")
     */
    public function index(StructureRepository $structureRepository)
    {
        return $this->render('structure/index.html.twig', [
            'current_name' => "Base",
            'structure' => $this->findStructure($structureRepository,0),
        ]);
    }

    public function findStructure(StructureRepository $structureRepository, $input)
    {
        $structure = array();

        $this->data = $structureRepository->findAll();

        if(is_numeric($input))
        {
            $structure = $structureRepository->findByLevel($input);
        }else if(is_string($input))
        {
            $structure = $structureRepository->findByMaster($input);
        }

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
     * @Route("/structure/ajax", name="structure_ajax")
     */
    public function ajaxAction(SubunitRepository $subunitRepository,LdapUserRepository $ldapUserRepository, StructureRepository $structureRepository, Request $request) {

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $data = $request->request->get('name');
            $allUsers =  $structureRepository->findByMaster($data);

            $jsonData = array();
            $idx = 0;

           // if(empty($allUsers) && $allUsers != null)
            foreach($allUsers as $item) {
                $temp = array(
                    'name' => $item->getName(),
                    'role' => $item->getMaster(),

                );
                $jsonData[$idx++] = $temp;
            }

            return new JsonResponse($jsonData);
        } else {
            $allUsers =  $structureRepository->findByMaster("20");

            return $this->render('structure/content.html.twig');
        }
    }

    /**
     * @Route("/user/ajax", name="structure_user_ajax")
     */
    public function userAjax(SubunitRepository $subunitRepository,LdapUserRepository $ldapUserRepository, StructureRepository $structureRepository, Request $request) {

        //dump($ldapUserRepository->findUnitIdByUserName("justinas.garipovas"));

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $data = $request->request->get('name');
            $allUsers =  $ldapUserRepository->findUnitIdByUserName("justinas.garipovas");
            $jsonData = array();
            $idx = 0;
/*
            if(empty($allUsers) || $allUsers == null) {
                return new JsonResponse(array());
            }
*/
            $temp = array(
                'role' => $allUsers->getRole(),
                'name' => $allUsers->getRole(),
            );
            $jsonData[$idx++] = $temp;

            return new JsonResponse($temp);
        } else {
            $allUsers =  $structureRepository->findByMaster("20");

            return $this->render('structure/content.html.twig');
        }
    }

    /**
     * @Route("/structure/", name="content_show")
     */
    public function contentShow(StructureRepository $structureRepository, $slug)
    {
        return $this->render('structure/index.html.twig', [
            'content' => $structureRepository->findByMaster($slug),
            'current_name' => $slug,
            'structure' => $this->findStructure($structureRepository,0),
        ]);
    }
    /**
     * @Route("/structure/{slug}", name="user_show")
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
            if($slave->getLevel() == $structure->getLevel()+1 && $slave->getMaster() == $structure->getName())
            {
                array_push($structure->array,$slave);
                $this->findBelow($slave);
            }
        }
    }

}
