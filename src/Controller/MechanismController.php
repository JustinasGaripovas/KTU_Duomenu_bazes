<?php

namespace App\Controller;

use App\Entity\Mechanism;
use App\Form\MechanismType;
use App\Repository\LdapUserRepository;
use App\Repository\MechanismRepository;
use App\Repository\SubunitRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/mechanism")
 * @IsGranted("ADMIN")
 */
class MechanismController extends AbstractController
{
    private $mechanismChoises = array("Sunkvežimis"=>"Sunkvežimis","Autogreideris"=>"Autogreideris","Traktorius"=>"Traktorius","Kiti"=>"Kiti");

    /**
     * @Route("/", name="mechanism_index")
     */
    public function index(MechanismRepository $mechanismRepository)
    {
        return $this->render('mechanism/index.html.twig', ['mechanisms' => $mechanismRepository->findAll()]);
    }

    /**
     * @Route("/new", name="mechanism_new", methods="GET|POST")
     */
    public function new(LdapUserRepository $ldapUserRepository, Request $request, SubunitRepository $subunitRepository): Response
    {
        $userName = $this->getUser()->getUserName();

        $mechanism = new Mechanism();

        $this->denyAccessUnlessGranted('EDIT',$mechanism);

        $form = $this->createForm(MechanismType::class, $mechanism, ['mechanism_choices' => $this->mechanismChoises, "subunit_choices"=>$this->subunitChoices($subunitRepository)]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //TODO: make type id usable AKA set type id in accordance with tyoe (choice)
            $mechanism->setTypeId(0);
            $em->persist($mechanism);
            $em->flush();

            return $this->redirectToRoute('mechanism_index');
        }

        return $this->render('mechanism/new.html.twig', [
            'mechanism' => $mechanism,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mechanism_show", methods="GET")
     */
    public function show(Mechanism $mechanism): Response
    {
        $this->denyAccessUnlessGranted('SHOW',$mechanism);

        return $this->render('mechanism/show.html.twig', ['mechanism' => $mechanism]);
    }

    /**
     * @Route("/{id}/edit", name="mechanism_edit", methods="GET|POST")
     */
    public function edit(LdapUserRepository $ldapUserRepository, Request $request, Mechanism $mechanism, SubunitRepository $subunitRepository): Response
    {
        $this->denyAccessUnlessGranted('EDIT',$mechanism);

        $form = $this->createForm(MechanismType::class, $mechanism, ['mechanism_choices' => $this->mechanismChoises, "subunit_choices"=>$this->subunitChoices($subunitRepository)]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //TODO: make type id usable AKA set type id in accordance with tyoe (choice)
            $mechanism->setTypeId(0);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mechanism_edit', ['id' => $mechanism->getId()]);
        }

        return $this->render('mechanism/edit.html.twig', [
            'mechanism' => $mechanism,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mechanism_delete", methods="DELETE")
     */
    public function delete(Request $request, Mechanism $mechanism): Response
    {
        $this->denyAccessUnlessGranted('DELETE',$mechanism);

        if ($this->isCsrfTokenValid('delete'.$mechanism->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($mechanism);
            $em->flush();
        }

        return $this->redirectToRoute('mechanism_index');
    }

    private function subunitChoices(SubunitRepository $subunitRepository){
        $subunitChoices = array();

        foreach ($subunitRepository->findAll() as $subunit)
        {
            $subunitChoices[$subunit->getName()] = $subunit->getSubunitId();
        }

        return $subunitChoices;
    }
}
