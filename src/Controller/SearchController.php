<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\RoadSection;
use App\Entity\RoadSectionForWinterJobs;
use App\Entity\Subunit;
use App\Repository\JobRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\LdapUserRepository;

class SearchController extends Controller
{
    /**
     * @Route("/search", name="search")
     */
    public function searchForJobs(Request $request, SerializerInterface $serializer) {

        if(!$request->isXmlHttpRequest()){
            return $this->render('search/index.html.twig');
        }

        $results = [];
            $searchString = $request->get('term');
            $foundEntities = $this->getDoctrine()
                ->getRepository(Job::class)
                ->findBySearchField($searchString);
            if (!$foundEntities) {
                $results[] = ['value' => "No items there found in database"] ;
            }
            else {
                foreach ($foundEntities as $entity){
                    $results[] = [
                        'value' => $entity->getJobId()." ".$entity->getJobName(),
                        'job_name' =>$entity->getJobName(),
                        'job_id' => $entity->getJobId(),
                        'unit_of' => $entity->getJobQuantity(),
                    ];
                }
            }

            return $this->json($results);
        }

    /**
     * @Route("/search/road", name="search/road")
     */
    public function searchForRoadSection(LdapUserRepository $ldapUserRepository,Request $request, SerializerInterface $serializer) {

        if(!$request->isXmlHttpRequest()){
            return $this->render('search/index.html.twig');
        }
        $username = $ldapUserRepository->findUnitIdByUserName($this->getUser()->getUserName());
        $unit_id = $username->getUnit()->getUnitId();
        $subunit_id = $username->getSubunit()->getSubunitId();

        $results = [];
        $searchString = $request->get('term');
        $foundEntities = $this->getDoctrine()
            ->getRepository(RoadSection::class)
            ->findRoadByNameOrIdField($searchString, $unit_id, $subunit_id);
        if (!$foundEntities) {
            $results[] = ['value' => "No items there found in database"] ;
        }
        else {
            foreach ($foundEntities as $entity){
                $results[] = [
                    'value' => $entity->getSectionId()." ".$entity->getSectionName(),
                    'section_id' =>$entity->getSectionId(),
                    'section_begin' => $entity->getSectionBegin(),
                    'section_end' => $entity->getSectionEnd(),
                    'road_level' => $entity->getLevel(),
                    'road_name' => $entity->getSectionName()
                ];
            }
        }


        return $this->json($results);
    }

    /**
     * @Route("/search/sections", name="search/sections")
     */
    public function searchForAllSections(LdapUserRepository $ldapUserRepository,Request $request, SerializerInterface $serializer) {

        if(!$request->isXmlHttpRequest()){
            return $this->render('search/index.html.twig');
        }

        $results = [];
        $searchString = $request->get('term');

        if($searchString == null)
            throw new \Symfony\Component\Config\Definition\Exception\Exception("Its null");
        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT s FROM App:Subunit s WHERE s.Name LIKE '$searchString%'" ;
        $foundEntities = $em->createQuery($dql)->execute();

        if (!$foundEntities) {
            $results[] = ['value' => "No items there found in database"] ;
        }
        else {
            foreach ($foundEntities as $entity){
                $results[] = [
                    'value' => $entity->getName(),
                ];
            }
        }

        return $this->json($results);
    }

    /**
     * @Route("/search/road2", name="search/road2")
     */
    public function searchForRoadSection2(LdapUserRepository $ldapUserRepository,Request $request, SerializerInterface $serializer) {

        if(!$request->isXmlHttpRequest()){
            return $this->render('search/index.html.twig');
        }
        $username = $ldapUserRepository->findUnitIdByUserName($this->getUser()->getUserName());
        $unit_id = $username->getUnit()->getUnitId();
        $subunit_id = $username->getSubunit()->getSubunitId();

        $results = [];
        $searchString = $request->get('term');
        $foundEntities = $this->getDoctrine()
            ->getRepository(RoadSection::class)
            ->findRoadByNameOrIdField($searchString, $unit_id, $subunit_id);
        if (!$foundEntities) {
            $results[] = ['value' => "No items there found in database"] ;
        }
        else {
            foreach ($foundEntities as $entity){
                $results[] = [
                    'value' => $entity->getSectionId(),
                    'section_id' =>$entity->getSectionId(),
                    'section_begin' => $entity->getSectionBegin(),
                    'section_end' => $entity->getSectionEnd(),
                    'road_name' => $entity->getSectionName()
                ];
            }
        }


        return $this->json($results);
    }

    /**
     * @Route("/search/winterRoad", name="search/road/winter")
     */
    public function searchRoadSectionForWinter(LdapUserRepository $ldapUserRepository,Request $request, SerializerInterface $serializer) {

        if(!$request->isXmlHttpRequest()){
            return $this->render('search/index.html.twig');
        }
        $username = $ldapUserRepository->findUnitIdByUserName($this->getUser()->getUserName());
        $unit_id = $username->getUnit()->getUnitId();
        $subunit_id = $username->getSubunit()->getSubunitId();
        $didFind = false;
        $results = [];
        $searchString = $request->get('term');
        $foundEntities = $this->getDoctrine()
            ->getRepository(RoadSectionForWinterJobs::class)
            ->findRoadByNameOrIdField($searchString, $unit_id, $subunit_id);
        if (!$foundEntities) {
            $results[] = ['value' => "No items there found in database"] ;
        }
        else {
            foreach ($foundEntities as $entity){
                $results[] = [
                    'value' => $entity->getSectionId(),
                    'section_id' => $entity->getSectionId(),
                    'section_begin' => $entity->getSectionBegin(),
                    'section_end' => $entity->getSectionEnd(),
                    'section_type' => $entity->getSectionType(),
                    'quadrature' => $entity->getQuadrature(),
                    'width' => $entity->getAverageWidth()
                ];
            }
        }

        return $this->json($results);
    }

    /**
     * @Route("/search/mechanism", name="search/mechanism")
     */
    public function searchMechanism(LdapUserRepository $ldapUserRepository,Request $request, SerializerInterface $serializer) {

        if(!$request->isXmlHttpRequest()){
            return $this->render('search/index.html.twig');
        }
        $username = $ldapUserRepository->findUnitIdByUserName($this->getUser()->getUserName());
        $unit_id = $username->getUnit()->getUnitId();
        $subunit_id = $username->getSubunit()->getSubunitId();

        $results = [];
        $searchString = $request->get('term');

            $results[] = [
                'section_begin' => "aaaaaaaaaa",
                'section_end' => 1
            ];
            $results[] = [
                'section_begin' => "bbbbbbbbb",
                'section_end' => 2
            ];
            $results[] = [
                'section_begin' => "cccccccccc",
                'section_end' => 3
            ];



        return $this->json($results);
    }

}
