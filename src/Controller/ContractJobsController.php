<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ContractJobsController
 * @package App\Controller
 * @IsGranted("ADMIN")
 */
class ContractJobsController extends AbstractController
{
    /**
     * @Route("/contract/jobs", name="contract_jobs")
     */
    public function index()
    {
        return $this->render('contract_jobs/index.html.twig', [
            'controller_name' => 'ContractJobsController',
        ]);
    }
}
