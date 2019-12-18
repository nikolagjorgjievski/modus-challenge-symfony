<?php

namespace App\Controller;

use App\Transformer\NHTSATransformer;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Interfaces\NHTSAServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class NHTSAController extends AbstractController
{
    private $service;
    private $transformer;

    public function __construct(NHTSAServiceInterface $service, NHTSATransformer $transformer)
    {
        $this->service = $service;
        $this->transformer = $transformer;
    }

    /**
     * @Route("/vehicles", name="vehicles", methods={"POST"}, name="vehicles_json")
     * @Route("/vehicles/{modelYear}/{manufacturer}/{model}", methods={"GET"}, name="vehicles")
     * @param Request $request
     * @param string $modelYear
     * @param string $manufacturer
     * @param string $model
     * @return JsonResponse
     */
    public function vehicles(Request $request, $modelYear = null, $manufacturer = null, $model = null)
    {
        if ($request->isMethod('post')) {
            $content = json_decode($request->getContent(), true);

            $modelYear = isset($content['modelYear']) ? $content['modelYear'] : null;
            $manufacturer = isset($content['manufacturer']) ? $content['manufacturer'] : null;
            $model = isset($content['model']) ? $content['model'] : null;
        }

        $withRating = $request->query->get('withRating') == 'true';

        $vehicles = $this->service->getVehicles(
            $modelYear, $manufacturer, $model, $withRating
        );

        return new JsonResponse($this->transformer->transform($vehicles, $withRating));
    }
}
