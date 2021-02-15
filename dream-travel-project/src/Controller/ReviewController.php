<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use App\Service\ImageUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/review")
 */
class ReviewController extends AbstractController
{
    /**
     * @Route("/", name="review_index", methods={"GET"})
     */
    public function index(ReviewRepository $reviewRepository): Response
    {
        return $this->render('review/index.html.twig', [
            'reviews' => $reviewRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="review_new", methods={"GET","POST"})
     */
    public function new(ImageUploader $imageUploader, Request $request): Response
    {
      
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);
        /*  //PAS utilisé
        if ($form->isSubmitted() && $form->isValid()) {
            // Active => 1| Inactive => 0
            $review->setStatus(1);
            $review->setNegativeVote(0);
            $review->setPositiveVote(0);
            // is not reported => 0 | is reported => 1
            $review->setReport(0);
            $review->setRate(0);
            $review->setCreatedAt(new \DateTime());

            $filename = $imageUploader->moveFile($form->get('imageFile')->getData(), 'images/');
            $picture = new Picture();
            $title = $form->get('title')->getData();
            $picture->setTitle($title); 
            $picture->setFilename($filename);
            $picture->setCreatedAt(new \DateTime());
            $picture->setReview($this->review);
            
            $review->addPicture($picture);
  
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($picture);
            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('review_index');
        } */

        return $this->render('review/new.html.twig', [
           // 'review' => $review,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="review_show", methods={"GET"})
     */
    public function show(Review $review): Response
    {
        return $this->render('review/show.html.twig', [
            'review' => $review,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="review_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Review $review): Response
    {
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('review_index');
        }

        return $this->render('review/edit.html.twig', [
            'review' => $review,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="review_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Review $review): Response
    {
        if ($this->isCsrfTokenValid('delete'.$review->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($review);
            $entityManager->flush();
        }

        return $this->redirectToRoute('review_index');
    }

    //TODO: créer reviewReport
}
