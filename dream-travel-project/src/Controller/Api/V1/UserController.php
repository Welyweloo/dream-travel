<?php

namespace App\Controller\Api\V1;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route("/api/v1", name="api_v1_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="user_like", requirements={"id"="\d+"}, methods={"POST"})
     */
    public function userLike($id, UserRepository $userRepository)
    {
        //$id de l'utilisateur aimé
        $userTarget = $this->getDoctrine()->getRepository(User::class)->find($id);

        $userSource = $this->getUser();

        if (!$userSource) {

            return $this->json(403);
        }
        $arrayLikers = [];


        foreach ($userTarget->getusers() as $userLike) {
            if ($userLike == $userSource) {
                $userSource->removeFavoriteUser($userTarget);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($userSource);
                $entityManager->flush();

                return $this->json([
                    'code' => 200,
                    'message' => ' unliked',
                ], 200);
            }
        }

        $userSource->addFavoriteUser($userTarget);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($userSource);
        $entityManager->flush();

        return $this->json([
            'code' => 201,
            'message' => 'liked',

        ], 200);
    }
}
