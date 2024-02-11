<?php

namespace App\Controller;

use App\Entity\ProfilposteResultat;
use App\Entity\Resultat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;

class LoginsuccesController extends AbstractController
{
    private $security;
    private $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/user", name="loginsucces")
     * @return Response
     */
    public function loginsucces(): Response
    {
        // Fetch the connected user
        $connectedUser = $this->security->getUser();

        // Verify if profilposteResultatUserId or resultatUserId is empty
        $showProfilPosteLink = !$this->entityManager->getRepository(ProfilposteResultat::class)->findOneBy(['user' => $connectedUser]);
        $showCommencerTestLink = !$this->entityManager->getRepository(Resultat::class)->findOneBy(['user' => $connectedUser]);

        return $this->render('index.html.twig', [
            'showProfilPosteLink' => $showProfilPosteLink,
            'showCommencerTestLink' => $showCommencerTestLink,
        ]);
    }
}