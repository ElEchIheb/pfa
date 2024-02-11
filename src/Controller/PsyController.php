<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Psy;
use App\Form\PsyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PsyController extends AbstractController
{
    #[Route('/psy', name: 'app_psy')]
    public function index(): Response
    {
        return $this->render('admin/home.html.twig', [
            'controller_name' => 'PsyController',
        ]);
    }

    /**
     * @Route("/ajouter-psy", name="ajouter_psy")
     */
    public function ajouter(Request $request, EntityManagerInterface $em)
    {
        $psy = new Psy();
        $form = $this->createForm(PsyType::class, $psy);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($psy);
            $em->flush();
            $this->addFlash('success', 'Psy ajouté avec succès!');
            return $this->redirectToRoute('app_psy');
        }
        return $this->render('psy/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
 * @Route("/handle-select-psy", name="handle_select_psy", methods={"POST"})
 */
public function handleSelectPsy(Request $request)
{
    $psyId = $request->request->get('psy');
    if ($psyId) {
        return $this->redirectToRoute('modifier_psy', ['id' => $psyId]);
    }
    return $this->redirectToRoute('select_psy');
}


/**
 * @Route("/select-psy", name="select_psy")
 */
public function selectPsy(EntityManagerInterface $em)
{
    $repository = $em->getRepository(Psy::class);
    $psys = $repository->findAll();

    return $this->render('psy/select.html.twig', [
        'psys' => $psys,
    ]);
}


    /**
     * @Route("/modifier-psy/{id}", name="modifier_psy")
     */
    public function modifier(Request $request, EntityManagerInterface $em, Psy $psy)
    {
        $form = $this->createForm(PsyType::class, $psy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($psy);
            $em->flush();

            $this->addFlash('success', 'Psy modifié avec succès!');
            return $this->redirectToRoute('admin_index'); // Redirect to the index page of Psy after modification
        }

        return $this->render('psy/modifier.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
