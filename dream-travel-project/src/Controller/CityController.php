<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Picture;
use App\Entity\Review;
use App\Entity\User;
use App\Form\CityType;
use App\Form\PictureType;
use App\Form\ReviewType;
use App\Repository\CityRepository;
use App\Repository\ReviewRepository;
use App\Service\ImageUploader;
use App\Service\QueryApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/city")
 */
class CityController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/", name="city_index", methods={"GET"})
     */
    public function index(CityRepository $cityRepository): Response
    {
        return $this->render('city/index.html.twig', [
            'cities' => $cityRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="city_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $city = new City();
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($city);
            $entityManager->flush();

            return $this->redirectToRoute('city_index');
        }

        return $this->render('city/new.html.twig', [
            'city' => $city,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{geonameId}", name="city_show", methods={"GET", "POST"}, requirements={"geonameId"="\d+"})
     */
    public function show(ImageUploader $imageUploader, Request $request, ReviewRepository $reviewRepository, QueryApi $queryApi, $geonameId): Response
    {

        $city = $this->getDoctrine()->getRepository(City::class)->findbyGeonameID($geonameId);

        if (!$city) {
            throw $this->createNotFoundException('Cette page n\'existe pas');
        }

        //form review
        $review = new Review();
        $formReview = $this->createForm(ReviewType::class, $review);
        $formReview->handleRequest($request);

        if ($formReview->isSubmitted() && $formReview->isValid()) {
            $review->setCity($city);
            // Active => 1| Inactive => 0
            $review->setStatus(1);
            $review->setNegativeVote(0);
            $review->setPositiveVote(0);
            // is not reported => 0 | is reported => 1
            $review->setIsReported(0);
            $review->setRate($formReview->get('rate')->getData());

            $review->setCreatedAt(new \DateTime());

            $filename = $imageUploader->moveFile($formReview->get('imageFile')->getData(), 'images/');
            $picture = new Picture();
            $title = $formReview->get('title')->getData();
            $picture->setTitle($title); 
            $picture->setFilename($filename);
            $picture->setCreatedAt(new \DateTime());
            $picture->setReview($review);
            
            $review->addPicture($picture);
  
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($picture);
            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('city_show', [ 'geonameId' =>  $geonameId]);
        }

        $arrayCitiesData = $queryApi->citiesData($geonameId);
        $arrayCitiesDataImages = $queryApi->citiesDataImages($arrayCitiesData[3]);

        return $this->render('city/show.html.twig', [
            'cityScores' => $arrayCitiesData[0],
            'cityUrbanArea' => $arrayCitiesData[1],
            'urlImage' => $arrayCitiesDataImages[0],
            'randomImagesArray' => $arrayCitiesDataImages[1],
            'reviews' => $city->getReviews(), //array à initialiser dans le template
            'formReview' => $formReview->createView(),
            'city' => $city,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="city_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, City $city): Response
    {
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('city_index');
        }

        return $this->render('city/edit.html.twig', [
            'city' => $city,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="city_delete", methods={"DELETE"})
     */
    public function delete(Request $request, City $city): Response
    {
        if ($this->isCsrfTokenValid('delete' . $city->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($city);
            $entityManager->flush();
        }
        return $this->redirectToRoute('city_index');
    }


    /**
     * @Route("/random", name="city_random", methods={"GET"})
     */
    public function getRandomCity(): Response
    {
        $randomCities = [];
        $randomCity = false;
        while (true) {
            $randomCity = $this->getDoctrine()->getRepository(City::class)->find(rand(1035, 2029));
            if ($randomCity) {
                $randomCities[] = $randomCity;
                if (count($randomCities) == 6) {
                    break;
                }
            }
        }

        return $this->render('city/random.html.twig', [
            'randomCities' => $randomCities,
        ]);
    }

  
}
