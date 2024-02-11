<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Entity\ProfilposteResultat;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\QuestionRepository;
use App\Repository\CategoryRepository;
use App\Repository\OptionRepository;
use App\Entity\Resultat;


class ProfileController extends AbstractController
{

    private $security;
    private $entityManager;
    private $questionRepository;
    private $categoryRepository;
    private $optionRepository;

    public function __construct(
        Security $security,
        EntityManagerInterface $entityManager,
        QuestionRepository $questionRepository,
        CategoryRepository $categoryRepository,
        OptionRepository $optionRepository
    ) {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->questionRepository = $questionRepository;
        $this->categoryRepository = $categoryRepository;
        $this->optionRepository = $optionRepository;
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        // Retrieve the questions, categories, and options
        $questions = $this->questionRepository->findAll();
        $categories = $this->categoryRepository->findAll();
        $options = $this->optionRepository->findAll();

        $user = $this->security->getUser();

        // Fetch the connected user
        $connectedUser = $this->security->getUser();

        // Verify if profilposteResultatUserId or resultatUserId is empty
        $showProfilPosteLink = !$this->entityManager->getRepository(ProfilposteResultat::class)->findOneBy(['user' => $connectedUser]);
        $showCommencerTestLink = !$this->entityManager->getRepository(Resultat::class)->findOneBy(['user' => $connectedUser]);

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'showProfilPosteLink' => $showProfilPosteLink,
            'showCommencerTestLink' => $showCommencerTestLink,
        ]);
    }
}
