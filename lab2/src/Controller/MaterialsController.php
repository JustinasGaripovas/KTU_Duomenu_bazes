<?php

namespace App\Controller;

use App\Entity\Materials;
use App\Form\MaterialsType;
use App\Repository\MaterialsRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/materials")
 */
class MaterialsController extends AbstractController
{
    /**
     * @Route("/", name="materials_index", methods={"GET"})
     */
    public function index(MaterialsRepository $materialsRepository, EntityManagerInterface $entityManager): Response
    {
        $conn = $entityManager
            ->getConnection();
        $sql = 'SELECT 
                        materials.id as id,
                        materials.amount as amount,
                        materials.material as material,
                        winter_job.started_at as startAt,
                        warehouse.id as warehouseId
                        FROM materials
                        INNER JOIN winter_job ON winter_job.id=materials.fk_winterjob_id
                        INNER JOIN warehouse ON warehouse.id=materials.fk_warehouse_id
                        ORDER BY materials.amount;';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        //dump($stmt->fetchAll());

        return $this->render('materials/index.html.twig', [
            'materials' => $stmt->fetchAll(),
        ]);
    }

    /**
     * @Route("/new", name="materials_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $material = new Materials();
        $form = $this->createForm(MaterialsType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($material);
            $entityManager->flush();

            return $this->redirectToRoute('materials_index');
        }

        return $this->render('materials/new.html.twig', [
            'material' => $material,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="materials_show", methods={"GET"})
     */
    public function show(Materials $material): Response
    {
        return $this->render('materials/show.html.twig', [
            'material' => $material,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="materials_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Materials $material): Response
    {
        $form = $this->createForm(MaterialsType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('materials_index', [
                'id' => $material->getId(),
            ]);
        }

        return $this->render('materials/edit.html.twig', [
            'material' => $material,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="materials_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Materials $material): Response
    {
        if ($this->isCsrfTokenValid('delete'.$material->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($material);
            $entityManager->flush();
        }

        return $this->redirectToRoute('materials_index');
    }
}
