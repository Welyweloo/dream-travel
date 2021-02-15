<?php

namespace App\Controller\Api\V1;

use App\Service\Translate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api/v1", name="api_v1_")
 */
class TranslateController extends AbstractController
{
    /**
     * @Route("/translate/", name="city_translate", methods={"POST"})
     */
    public function translateText(ObjectNormalizer $objetNormalizer, Request $request)
    {
        $content = $request->getContent();
        $arrayData = json_decode($content, true);

        //extract($arrayData);

        $translation = new Translate();

        $response = $translation->translateToFrench($arrayData);

        return $response;

    }
}