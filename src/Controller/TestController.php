<?php

namespace App\Controller;

use App\Repository\QuestionRepository;
use App\Repository\CategoryRepository;
use App\Repository\OptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ProfilposteResultat;
use App\Entity\Resultat;

use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;


class TestController extends AbstractController
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


    #[Route('/profilposte', name: 'app_profilposte')]
    public function profilposte(): Response
    {

        $questions = $this->questionRepository->findAll();
        $categories = $this->categoryRepository->findAll();
        $options = $this->optionRepository->findAll();

        // Fetch the connected user
        $connectedUser = $this->security->getUser();



        // Verify if profilposteResultatUserId or resultatUserId is empty
        $showProfilPosteLink = !$this->entityManager->getRepository(ProfilposteResultat::class)->findOneBy(['user' => $connectedUser]);
        $showCommencerTestLink = !$this->entityManager->getRepository(Resultat::class)->findOneBy(['user' => $connectedUser]);
        // Retrieve the questions, categories, and options
        // Render information for the 'profilposte' page
        return $this->render('test/profilposte.html.twig', [
            'controller_name' => 'TestController',
            'questions' => $questions,
            'categories' => $categories,
            'options' => $options,
            'showProfilPosteLink' => $showProfilPosteLink,
            'showCommencerTestLink' => $showCommencerTestLink,
        ]);

    }

    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {

        // Retrieve the questions, categories, and options
        $questions = $this->questionRepository->findAll();
        $categories = $this->categoryRepository->findAll();
        $options = $this->optionRepository->findAll();

        // Fetch the connected user
        $connectedUser = $this->security->getUser();



        // Verify if profilposteResultatUserId or resultatUserId is empty
        $showProfilPosteLink = !$this->entityManager->getRepository(ProfilposteResultat::class)->findOneBy(['user' => $connectedUser]);
        $showCommencerTestLink = !$this->entityManager->getRepository(Resultat::class)->findOneBy(['user' => $connectedUser]);

        // Render information for the 'profilposte' page
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'questions' => $questions,
            'categories' => $categories,
            'options' => $options,
            'showProfilPosteLink' => $showProfilPosteLink,
            'showCommencerTestLink' => $showCommencerTestLink,
        ]);

    }
}
