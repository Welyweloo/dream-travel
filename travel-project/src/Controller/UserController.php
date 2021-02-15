<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\ImageUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(ImageUploader $imageUploader, Request $request, User $user): Response
    {

        //teste le droit (edit) sur l'objet($user)
        //retourne un 403 si l'utilisateur ne rentre pas dans les conditions du voter
        //$this->denyAccessUnlessGranted('edit', $question);
        $this->denyAccessUnlessGranted('edit', $user);
        /*  if (!$this->isGranted('edit', $user, 'User tried to access a page without having ROLE_ADMIN')) {
            $this->addFlash('danger', 'Vous n\'avez pas le droits de editer cette question');

            return $this->redirectToRoute('question_show', ['id' => $user->getId()]);
        } */


        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('avatar')->getData()) {
                $fileName = $imageUploader->moveFile($form->get('avatar')->getData(), 'images/avatars');
                /* dd($fileName); */
                $user->setAvatar($fileName);
            }


            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }


    /**
     * @Route("/{id}/likes", name="user_likes", methods={"GET"})
     */
    public function favoritesUsers(User $user)
    {
        return $this->render('user/likes.html.twig', [
            'user' => $user,
        ]);
    }


      /**
     * @Route("/{id}/cities-likes", name="user_cities_likes", methods={"GET"})
     */
    public function favoritesCities(User $user)
    {
        return $this->render('user/cities_likes.html.twig', [
            'user' => $user,
        ]);
    }
}
