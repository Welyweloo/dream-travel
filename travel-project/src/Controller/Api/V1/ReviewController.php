<?php

namespace App\Controller\Api\V1;

use App\Entity\Review;
use App\Entity\ReviewLike;
use App\Repository\ReviewLikeRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route("/api/v1", name="api_v1_")
 */
class ReviewController extends AbstractController //api/v1/review/{id}
{
    /**
     * @Route("/review/{id}", name="review_report", requirements={"id"="\d+"}, methods={"POST"})
     */
    public function report($id)
    {

        $review = $this->getDoctrine()->getRepository(Review::class)->find($id);
        $review->setIsReported(true);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($review);
        $entityManager->flush();

        if ($review->GetIsReported() === true) {
            return new Response('Message signalé', Response::HTTP_ACCEPTED);
        }
    }


    /**
     * @Route("/review/{id}/like", name="review_like", requirements={"id"="\d+"}, methods={"POST"})
     */
    public function like(Review $review, ReviewLikeRepository $reviewLikeRepository): Response
    {

        $user = $this->getUser();

        if (!$user) {

            return $this->json(403);
        }
        if ($review->isLikedByUser($user)) {
            $like = $reviewLikeRepository->findOneBy([
                'review' => $review,
                'user' => $user,
            ]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($like);
            $entityManager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'like removed',
                'likes' => $reviewLikeRepository->count(['review' => $review]),

            ], 200);
        }

        $like = new ReviewLike();
        $like->setReview($review);
        $like->setUser($user);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($like);
        $entityManager->flush();


        return $this->json([
            'code' => 201,
            'message' => 'Message liked',
            'likes' => $reviewLikeRepository->count(['review' => $review]),
        ], 200);
    }
}
