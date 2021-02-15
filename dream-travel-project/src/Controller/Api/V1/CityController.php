<?php

namespace App\Controller\Api\V1;

use App\Entity\City;
use App\Entity\CityList;
use App\Entity\CityLike;
use App\Form\CityListType;
use App\Form\CityType;
use App\Repository\CityRepository;
use App\Repository\CityLikeRepository;
use App\Service\QueryApi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @Route("/api/v1", name="api_v1_")
 */
class CityController extends AbstractController
{
    /**
     * @Route("/city", name="city_list", methods={"GET"})
     */
    public function list(CityRepository $cityRepository, ObjectNormalizer $objetNormalizer, Request $request)
    {
        //$cities = $cityRepository->findAll();
        //$form->get('plainPassword')->getData()
        $search = $request->query->get("city_name");

        $cities = $this->getDoctrine()->getRepository(City::class)->findByPartialName($search);

        if (empty($cities)) {

            return new Response('Pas de resultats', Response::HTTP_NO_CONTENT);
        }

        $serializer = new Serializer([new DateTimeNormalizer(), $objetNormalizer]);

        $json = $serializer->normalize($cities, null, ['groups' => 'api_v1_city']);

        return $this->json($json);
    }

    /**
     * @Route("/country", name="country_list", methods={"GET"})
     */
    public function listCountries(CityRepository $cityRepository, ObjectNormalizer $objetNormalizer, Request $request)
    {

        $search = $request->query->get('country_name');

        $countries = $this->getDoctrine()->getRepository(City::class)->findByPartialCountryName($search);

        if (empty($countries)) {

            return new Response('Pas de resultats', Response::HTTP_NO_CONTENT);
        }

        $serializer = new Serializer([new DateTimeNormalizer(), $objetNormalizer]);

        $json = $serializer->normalize($countries, null, ['groups' => 'api_v1_city']);

        return $this->json($json);
    }

    /**
     * @Route("/image/{cityName}", name="image_city", methods={"GET"})
     */
    public function cityImage(CityRepository $cityRepository, ObjectNormalizer $objetNormalizer, Request $request, QueryApi $queryApi, $cityName)
    {

        $cityImage = $queryApi->cityDataImage($cityName);


        if (empty($cityImage)) {

            return new Response('Pas de resultats', Response::HTTP_NO_CONTENT);
        }

        /*  $serializer = new Serializer([new DateTimeNormalizer(), $objetNormalizer]);
        $json = $serializer->normalize($cityImage, null, ['groups' => 'api_v1_city']); */

        return $this->json($cityImage);
    }


    /**
     * @Route("/images/{cityName}", name="images_city", methods={"GET"})
     */
    public function cityImages(CityRepository $cityRepository, ObjectNormalizer $objetNormalizer, Request $request, QueryApi $queryApi, $cityName)
    {

        $cityImage = $queryApi->cityDataImages($cityName);


        if (empty($cityImage)) {

            return new Response('Pas de resultats', Response::HTTP_NO_CONTENT);
        }

        /*  $serializer = new Serializer([new DateTimeNormalizer(), $objetNormalizer]);
        $json = $serializer->normalize($cityImage, null, ['groups' => 'api_v1_city']); */

        return $this->json($cityImage);
    }


    /**
     * @Route("/city/{geonameId}", name="description_city", methods={"GET"})
     */
    public function cityDescription(ObjectNormalizer $objetNormalizer, Request $request, QueryApi $queryApi, $geonameId)
    {

        $cityDescription = $queryApi->cityDataDescription($geonameId);


        if (empty($cityDescription)) {

            return new Response('Pas de resultats', Response::HTTP_NO_CONTENT);
        }

        /*  $serializer = new Serializer([new DateTimeNormalizer(), $objetNormalizer]);
        $json = $serializer->normalize($cityImage, null, ['groups' => 'api_v1_city']); */

        return $this->json($cityDescription);
    }



    /**
     * @Route("/save-list", name="save_city_list", methods={"POST"})
     */
    public function citySaveList(Request $request)
    {

        $content = $request->getContent();
        $arrayData = json_decode($content, true);
        //dd($arrayData);
        $cityList = new CityList();
        $form = $this->createForm(CityListType::class, $cityList);
        $form->submit($arrayData);
        //$form->handleRequest($request);
        $cityList->setName($arrayData['titleList']);
        $cityList->setUrl($arrayData['url']);
        $cityList->setCreatedAt(new \DateTime());
        //$cityList->addCity();
        $cityList->addUser($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($cityList);
        $em->flush();

       /*  if ($form->isSubmitted() && $form->isValid()) {
            dd($cityList);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cityList);
            $entityManager->flush(); */

     /*        return $this->redirectToRoute('city_list_index');
        } */

        return new Response('It worked',  201);
    
    }


    /**
     * @Route("/city/{id}/like", name="city_like", requirements={"id"="\d+"}, methods={"POST"})
     */
    public function like(City $city, CityLikeRepository $cityLikeRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {

            return $this->json(403);
        }

        if ($city->isLikedByUser($user)) {
            $like = $cityLikeRepository->findOneBy([
                'city' => $city,
                'user' => $user,
            ]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($like);
            $entityManager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'like removed',
                'likes' => $cityLikeRepository->count(['city' => $city]),

            ], 200);
        }

        $like = new CityLike();
        $like->setCity($city);
        $like->setUser($user);
      

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($like);
        $entityManager->flush();


        return $this->json([
            'code' => 201,
            'message' => 'City liked',
            'likes' => $cityLikeRepository->count(['city' => $city]),
        ], 200);
    }
}
