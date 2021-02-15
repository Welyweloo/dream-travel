<?php

namespace App\Controller;

use App\Entity\CityList;
use App\Entity\User;
use App\Form\AdvancedSearchType;
use App\Form\CityListType;
use App\Repository\CityListRepository;
use App\Service\QueryApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/city/list")
 */
class CityListController extends AbstractController
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/", name="city_list_index", methods={"GET"})
     */
    public function index(Request $request, CityListRepository $cityListRepository, QueryApi $queryApi): Response
    {
        $arrayMatching = $this->session->get('arrayMatching');
        $searchStartDate = $this->session->get('arrayMatching');
        $searchEndDate = $this->session->get('arrayMatching');


        $cityList = new CityList();
        $form = $this->createForm(CityListType::class, $cityList);
        $form->handleRequest($request);

        return $this->render('city_list/index.html.twig', [
            'arrayMatching' => $arrayMatching,
            'searchStartDate' => $searchStartDate,
            'searchEndDate' => $searchEndDate,
            'city_list' => $cityList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="city_list_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cityList = new CityList();
        $form = $this->createForm(CityListType::class, $cityList);
        //$form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($cityList);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cityList);
            $entityManager->flush();

            return $this->redirectToRoute('city_list_index');
        }

        return $this->render('city_list/new.html.twig', [
            'city_list' => $cityList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="city_list_show", methods={"GET"})
     */
    public function show($id): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if ($user != $this->getUser()) {
            throw $this->createNotFoundException('Cette page n\'existe pas');
        }

        return $this->render('city_list/show.html.twig', []);
    }

    /**
     * @Route("/{id}/edit", name="city_list_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CityList $cityList): Response
    {
        $form = $this->createForm(CityListType::class, $cityList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('city_list_index');
        }

        return $this->render('city_list/edit.html.twig', [
            'city_list' => $cityList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="city_list_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CityList $cityList): Response
    {
        if ($this->isCsrfTokenValid('delete' . $cityList->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cityList);
            $entityManager->flush();
        }

        return $this->redirectToRoute('city_list_index');
    }
}
