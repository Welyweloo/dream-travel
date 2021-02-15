<?php

namespace App\Controller;

use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search", methods={"GET"})
     */
    public function index()
    {
        $form = $this->createForm(SearchType::class);
        return $this->render('search/index.html.twig', [
            'pageTitle' => 'Search',
            'form' => $form->createView()
        ]);
    }
}
