<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/admin/utilisateurs', name: 'admin_users_')]
class UtilisateursController extends AbstractController
{
    private $userRepository;
    private $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $users = $this->userRepository->findBy([], ['firstname' => 'asc']);
        return $this->render('admin/utilisateurs/index.html.twig', compact('users'));
    }

    #[Route('/ajouter', name: 'ajouter')]
    public function ajouter(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);

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

            $user->setPassword($hashedPassword);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'User added successfully.');
            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('admin/utilisateurs/ajouter.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/modifier/{id}', name: 'modifier')]
    public function modifier($id, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();

            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }
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

            $this->entityManager->flush();
            $this->addFlash('success', 'User updated successfully.');
            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('admin/utilisateurs/modifier.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    #[Route('/supprimer/{id}', methods: ['GET', 'POST', 'DELETE'], name: 'supprimer')]
    public function supprimer($id): Response
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_users_index');
    }
    
}