<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Tag;
use App\Form\AdvancedSearchType;
use App\Form\SearchType;
use App\Repository\CityRepository;
use App\Service\SearchMatchTag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AdvancedSearchController extends AbstractController
{
    /**
     * @Route("/advanced-search", name="advanced_search", methods={"GET", "POST"})
     */
    public function index(Request $request, CityRepository $cityRepository, SearchMatchTag $searchMatchTag, SessionInterface $session)
    {

        $form = $this->createForm(AdvancedSearchType::class);
        $form->handleRequest($request);

        

        //$formSearch = $this->createForm(SearchType::class);
        if ($form->isSubmitted() && $form->isValid()) {

            //dd($request);
            $em = $this->getDoctrine()->getManager();
            $labels = $form->getData();

            if ($form->get('search')->getData()) {
                $queryCountry = $form->get('search')->getData();
                //create a query builder
                $cities = $em->getRepository(City::class)->findByCountryName($queryCountry);
                if (!$cities) {
                    return $this->redirectToRoute('advanced_search');
                }
            } else {
                //all cities of the db
                $cities = $cityRepository->findAll();
            }

            unset($labels[array_search($form->get('search')->getData(), $labels)]);
            unset($labels[array_search($form->get('startDate')->getData(), $labels)]);
            unset($labels[array_search($form->get('endDate')->getData(), $labels)]);

            $tagsId = $searchMatchTag->match($labels);

            $arrayResults = [];
            $matchCity = [];

            foreach ($cities as $city) {
                foreach ($tagsId as $tagId) {
                    $tag = $em->getRepository(Tag::class)->find($tagId);

                    //matching of the tags
                    if ($city->getTag()->contains($tag)) {
                        $arrayResults[$tag->getLabel()][] = $city;
                        //all the cities that have a match
                        $matchCity[] = $city->getId(); //[1035, 1036, 1035, 1035 ]
                    }
                }
            }

            $matchCity = (array_count_values($matchCity)); // [1035 => 3 , 1036 => 1]      

            /* For each tag, we check if the city is in the tags array.
            We count the number of times a city matches a tag.
            Calculation of the value (Nb of tags selected by the user/Total number of tags)  */
            $arrayMatching = [];
            foreach ($matchCity as $key => $value) {
                $matchValue = $value / count($tagsId) * 100;
                $objectCity = $em->getRepository(City::class)->find($key);
                $arrayMatching[] = [
                    'city' => $objectCity,
                    'value' => $matchValue,
                ];
            }

            $session->set('arrayMatching', $arrayMatching);
            $session->set('startDate', $form->get('startDate')->getData());
            $session->set('endDate', $form->get('endDate')->getData());

            return $this->redirectToRoute('city_list_index',[
                $form->getData()
            ]);
        }

        return $this->render('advanced_search/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
