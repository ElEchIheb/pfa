<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            /** @var UploadedFile $cvFile */
            $cvFile = $form->get('cv')->getData();

            if ($cvFile) {
                $newFilename = uniqid() . '.' . $cvFile->guessExtension();

                try {
                    $cvFile->move($this->getParameter('cv_directory'), $newFilename);
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }

                $user->setCv($newFilename);
            }

            
            $entityManager->persist($user);

            // Check if the connected user has the role 'ROLE_ADMIN'
            $connectedUser = $this->getUser();
            if ($connectedUser && in_array('ROLE_ADMIN', $connectedUser->getRoles(), true)) {
                $user->setRoles(['ROLE_USER']);
            } else {
                $user->setRoles(['ROLE_ADMIN']);
            }

            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
