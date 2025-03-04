<?php

namespace ApiBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Viewer
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function view($result, $status = Response::HTTP_OK)
    {
        $request = $this->container->get('request_stack')->getMasterRequest();
        $isEnvelop = $request->query->get('envelope', false);

        if ($isEnvelop) {
            $result = [
                'status' => $status,
                'headers' => [],
                'response' => $result,
            ];
        }

        $response = $result instanceof Response ? $result : new JsonResponse($result, $status);
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
