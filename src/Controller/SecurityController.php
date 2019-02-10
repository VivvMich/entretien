<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class SecurityController extends AbstractController
{

      private $encoder;

      public function __construct(UserPasswordEncoderInterface $encoder)
      {
          $this->encoder = $encoder;
      }


    /**
     * @Route("/login", name="login")
     */

     public function login(AuthenticationUtils $authenticationUtils)
     {
         $lastUserName = $authenticationUtils->getLastUsername();
         $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('security/login.html.twig', [
           'last_username' => $lastUserName,
           'error' => $error
        ]);
     }

    /**
     * @Route("/inscription", name="inscription", methods={"GET","POST"})
     */
    public function signIn(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $user->getPassword();
            $user->setPassword($this->encoder->encodePassword($user, $password));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('security/inscription.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


}
















?>