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
use GuzzleHttp\Client;

/**
 * Class ReserveController
 * @package App\Controller
 * @IsGranted("ADMIN")
 */
class ReserveController extends AbstractController
{

    private $client;

    /**
     * @Route("/reserve", name="reserve")
     */
    public function index()
    {
        dump($this->connectionStatus());
        dump($this->getReserves());

        return $this->render('reserve/index.html.twig', [
            'controller_name' => 'ReserveController',
            'connectionStatus' => $this->connectionStatus(),
            'reserves' => $this->getReserves()
        ]);
    }

    /*
     * Gets the JSON from http://192.168.192.25/ws2/online to verify connection success.
     */
    public function connectionStatus()
    {
        try {
            $client = new Client();
            $response = $client->request('GET', 'http://192.168.192.25/ws2/online');
        } catch (Exception $e) {
            $response = null;
            dump("Something went super wrong");
        }

        return json_decode($response->getBody());
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
        try {
            $this->client = new Client([
                // Base URI is used with relative requests
                'base_uri' => 'http://192.168.192.25/',
                // You can set any number of default request options.
                'timeout' => 30.0,
            ]);

            $response = $this->client->request('POST', 'ws2/likuciai', [
                'multipart' => [
                    [
                        'name' => 'login',
                        'contents' => 'MASTER'
                    ],
                    [
                        'name' => 'pass',
                        'contents' => '92405a71c600c7a655ca3a40684ace58'
                    ],
                    [
                        'name' => 'kodas_is',
                        'contents' => 'AMAMRSKT-015',
                    ],
                    [
                        'name' => 'kodas_os',
                        'contents' => 'VYTSEL',
                    ]
                ]
            ]);
        } catch (Exception $e) {

            $response = null;
            dump("Something went super wrong");
        }

        return json_decode($response->getBody());
    }


}
