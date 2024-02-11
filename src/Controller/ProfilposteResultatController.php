<?php

namespace App\Controller;

use App\Entity\ProfilposteResultat;
use App\Form\ProfilposteResultatType;
use App\Repository\ProfilposteResultatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profilposte/resultat')]
class ProfilposteResultatController extends AbstractController
{
    #[Route('/', name: 'app_profilposte_resultat_index', methods: ['GET'])]
    public function index(ProfilposteResultatRepository $profilposteResultatRepository): Response
    {
        return $this->render('profilposte_resultat/index.html.twig', [
            'profilposte_resultats' => $profilposteResultatRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_profilposte_resultat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProfilposteResultatRepository $profilposteResultatRepository): Response
    {
        $profilposteResultat = new ProfilposteResultat();
        $form = $this->createForm(ProfilposteResultatType::class, $profilposteResultat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profilposteResultatRepository->save($profilposteResultat, true);

            return $this->redirectToRoute('app_profilposte_resultat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profilposte_resultat/new.html.twig', [
            'profilposte_resultat' => $profilposteResultat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_profilposte_resultat_show', methods: ['GET'])]
    public function show(ProfilposteResultat $profilposteResultat): Response
    {
        return $this->render('profilposte_resultat/show.html.twig', [
            'profilposte_resultat' => $profilposteResultat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_profilposte_resultat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProfilposteResultat $profilposteResultat, ProfilposteResultatRepository $profilposteResultatRepository): Response
    {
        $form = $this->createForm(ProfilposteResultatType::class, $profilposteResultat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profilposteResultatRepository->save($profilposteResultat, true);

            return $this->redirectToRoute('app_profilposte_resultat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profilposte_resultat/edit.html.twig', [
            'profilposte_resultat' => $profilposteResultat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_profilposte_resultat_delete', methods: ['POST'])]
    public function delete(Request $request, ProfilposteResultat $profilposteResultat, ProfilposteResultatRepository $profilposteResultatRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$profilposteResultat->getId(), $request->request->get('_token'))) {
            $profilposteResultatRepository->remove($profilposteResultat, true);
        }

        return $this->redirectToRoute('app_profilposte_resultat_index', [], Response::HTTP_SEE_OTHER);
    }
}
