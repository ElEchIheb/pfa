<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Option;
use App\Entity\Category;
use App\Entity\ProfilposteResultat;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use App\Repository\QuestionRepository;
use App\Repository\CategoryRepository;
use App\Repository\OptionRepository;
use App\Entity\Resultat;
use Symfony\Component\Security\Core\Security;

class ProfilposteController extends AbstractController
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

    /**
     * @Route("/profilposte", name="profilposte_result", methods={"POST"})
     */
    public function calculateResult(Request $request, EntityManagerInterface $entityManager): Response
    {


        // Retrieve the questions, categories, and options
        $questions = $this->questionRepository->findAll();
        $categories = $this->categoryRepository->findAll();
        $options = $this->optionRepository->findAll();

        $formData = $request->request->all();
        $categoryName = "Questions profilposte";
        $category = $this->entityManager->getRepository(Category::class)->findOneBy(['name' => $categoryName]);

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $questions = $this->entityManager->getRepository(Question::class)->findBy(['category' => $category]);

        $correctAnswers = 0;
        foreach ($formData as $optionId) {
            $optionId = (int) $optionId; // Ensure $optionId is an integer

            $option = $this->entityManager->getRepository(Option::class)->find($optionId);

            if ($option && $option->getPoint() === 1) {
                $correctAnswers++;
            }
        }

        $totalQuestions = count($questions);
        $score = ($totalQuestions > 0) ? ($correctAnswers / $totalQuestions) * 100 : 0;

        // Create a new Profilposte object and associate it with the user
        $profilposte_resultat = new ProfilposteResultat();
        $profilposte_resultat->setScore($score); // Set the score value

        // Get the authenticated user
        $user = $this->getUser();
        if ($user) {
            $profilposte_resultat->setUser($user);
        } else {
            // Handle the case when the user is not authenticated
        }

        $entityManager->persist($profilposte_resultat);
        $entityManager->flush();

        // Generate the PDF file
        $pdfTitle = 'Fiche d évaluation';

        // Generate the PDF content
        $pdfContent = '<h1 style="text-align: center;">' . $pdfTitle . '</h1>' . '<p>Score: ' . $score . '%</p>';

        $pdfContent .= '<p>Mention: ';

        if ($score === 0) {
            $pdfContent .= 'Pas de connaissance';
        } elseif ($score <= 25) {
            $pdfContent .= 'À travailler';
        } elseif ($score > 25 && $score <= 50) {
            $pdfContent .= 'Moyen';
        } elseif ($score > 50 && $score <= 70) {
            $pdfContent .= 'Bien';
        } elseif ($score > 70 && $score <= 100) {
            $pdfContent .= 'Très bien';
        }

        $pdfContent .= '</p>';
        $pdfContent .= 'Resultat Profil poste :';
        // Add questions, user answers, and points collected for each question
        $pdfContent .= '<table style="border-collapse: collapse; width: 100%;">';
        $pdfContent .= '<tr><th style="border: 1px solid black; padding: 8px;">Question</th><th style="border: 1px solid black; padding: 8px;">Réponse de l utilisateur</th><th style="border: 1px solid black; padding: 8px;">Points collectés</th></tr>';

        foreach ($formData as $optionId) {
            $optionId = (int) $optionId; // Ensure $optionId is an integer

            $option = $this->entityManager->getRepository(Option::class)->find($optionId);

            if ($option && $option->getQuestion()) {
                $question = $option->getQuestion();
                $userAnswer = $option->getOptionText();
                $pointCollected = $option->getPoint();

                $pdfContent .= '<tr>';
                $pdfContent .= '<td style="border: 1px solid black; padding: 8px;">' . $question->getQuestionText() . '</td>';
                $pdfContent .= '<td style="border: 1px solid black; padding: 8px;">' . $userAnswer . '</td>';
                $pdfContent .= '<td style="border: 1px solid black; padding: 8px;">' . $pointCollected . '</td>';
                $pdfContent .= '</tr>';
            }
        }
        $pdfContent .= '</table>';


        // Save the PDF file
        $pdfFileName = 'result_' . $profilposte_resultat->getId() . '.pdf';
        $pdfFilePath = $this->getParameter('profilposte_directory') . '/' . $pdfFileName;
        file_put_contents($pdfFilePath, $pdfContent);

        $profilposte_resultat->setResultat($pdfFileName); // Set the PDF file name in the 'resultat' column
        $entityManager->flush();

        // Generate the URL to the PDF file
        $pdfUrl = $request->getUriForPath($pdfFilePath);

        // Fetch the connected user
        $connectedUser = $this->security->getUser();

        // Verify if profilposteResultatUserId or resultatUserId is empty
        $showProfilPosteLink = !$this->entityManager->getRepository(ProfilposteResultat::class)->findOneBy(['user' => $connectedUser]);
        $showCommencerTestLink = !$this->entityManager->getRepository(Resultat::class)->findOneBy(['user' => $connectedUser]);



        // Return the response with the PDF link
        return $this->render('profilposte/result.html.twig', [

            'score' => $score,
            'pdfFileName' => $pdfFileName,
            'showProfilPosteLink' => $showProfilPosteLink,
            'showCommencerTestLink' => $showCommencerTestLink,

        ]);
    }

    /**
     * @Route("/profilposte/show/{filename}", name="profilposte_show", methods={"GET"})
     */
    public function showPdf(string $filename): Response
    {
        $pdfPath = $this->getParameter('profilposte_directory') . '/' . $filename;

        $response = new Response(file_get_contents($pdfPath));
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $filename);
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}
