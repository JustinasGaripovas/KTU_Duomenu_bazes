<?php

namespace App\Controller;

use phpDocumentor\Reflection\Types\This;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ReserveController
 * @package App\Controller
 * @IsGranted("ADMIN")
 */
class ReserveController extends AbstractController
{
    /**
     * @Route("/reserve", name="reserve")
     */
    public function index()
    {
        return $this->render('reserve/index.html.twig', [
            'controller_name' => 'ReserveController',
            'connectionStatus' => $this->connectionStatus(),
            'reserves' => $this->getReserves(),
        ]);
    }

    /*
     * Gets the JSON from http://192.168.192.25/ws2/online to verify connection success.
     */
    public function connectionStatus()
    {
        try {
            $ctx = stream_context_create(array(
                    'http' => array(
                        'timeout' => 1
                    )
                )
            );

            $jsonContent = file_get_contents("http://192.168.192.25/ws2/online", 0, $ctx);

            if ($jsonContent === false) {
                dump("Something went wrong");

            }
        } catch (Exception $e) {

            $jsonContent = null;
            dump("Something went super wrong");
        }

        return $jsonContent;
    }

    /**
     * @Route("/reserve/connection_ajax", name="connection_ajax")
     */
    public function ajaxAction(Request $request) {

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            return new JsonResponse($this->connectionStatus());
        } else {
            return $this->render('structure/winter_road_content.twig');
        }
    }

    /*
     * Get JSON of reserves from specific variables userID, terminalID, terminalID2, from API http://192.168.192.25/ws2/r_likuciai
     */
    private function getReserves()
    {
        $userId = "darzvi";
        $terminalId = 0;
        $terminalId2 = 0;

        try {
            $ctx = stream_context_create(array(
                    'http' => array(
                        'timeout' => 1
                    )
                )
            );

            dump("http://192.168.192.25/ws2/r_likuciai/{$userId}/{$terminalId}/{$terminalId2}");

            $jsonContent = file_get_contents("http://192.168.192.25/ws2/r_likuciai/{$userId}/{$terminalId}/{$terminalId2}", 0, $ctx);

            if ($jsonContent === false) {
                dump("Something went wrong");

            }
        } catch (Exception $e) {

            $jsonContent = null;
            dump("Something went super wrong");
        }

        return $jsonContent;
    }


}
