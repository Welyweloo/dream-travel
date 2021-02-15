<?php

namespace App\Controller;

use App\Entity\Badge;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\BadgeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, BadgeRepository $badgeRepository): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $user->getPassword()
                    //$form->get('plainPassword')->getData()
                )
            );
            //add default badge
            $badge = $badgeRepository->find(1);
            $user->addBadge($badge);
            $user->setPoints(5);
            //add default avatar
            $user->setAvatar('default-avatar.svg');
            //add default username $firstname(14 CHAR )+'#'.random_int
            $firstname = $form->get('firstname')->getData();
            $name = $form->get('name')->getData();
            $newUsername = substr($firstname, 0, 14) . '#' . random_int(1, 100000);
            $user->setUsername($newUsername);
            $user->setIsActive(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
