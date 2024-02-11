<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Competence;
use App\Form\CompetenceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
class CompetenceController extends AbstractController
{
    #[Route('/competence', name: 'app_competence')]
    public function index(): Response
    {
        return $this->render('competence/index.html.twig', [
            'controller_name' => 'CompetenceController',
        ]);
    }
    /**
     * @Route("/ajouter-competence", name="ajouter_competence")
     */
    public function ajouter(Request $request, EntityManagerInterface $em)
    {
        $competence = new Competence();
        $form = $this->createForm(CompetenceType::class, $competence);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($competence);
            $em->flush();

            $this->addFlash('success', 'Compétence ajoutée avec succès!');
            return $this->redirectToRoute('admin_index'); // Change 'some_route_name' to your desired route
        }

        return $this->render('competence/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
 * @Route("/modifier-competence/{id}", name="modifier_competence")
 */
public function modifierCompetence(Request $request, EntityManagerInterface $em, Competence $competence)
{
    $form = $this->createForm(CompetenceType::class, $competence);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($competence);
        $em->flush();

        $this->addFlash('success', 'Compétence modifiée avec succès!');
        return $this->redirectToRoute('admin_index'); // Redirect to the index page of Competence after modification
    }

    return $this->render('competence/modifierCompetence.html.twig', [
        'form' => $form->createView(),
    ]);
}

    /**
 * @Route("/select-competence", name="select_competence")
 */
public function selectCompetence(EntityManagerInterface $em)
{
    $repository = $em->getRepository(Competence::class);
    $competences = $repository->findAll();

    return $this->render('competence/selectCompetence.html.twig', [
        'competences' => $competences,
    ]);
}

/**
 * @Route("/handle-select-competence", name="handle_select_competence", methods={"POST"})
 */
public function handleSelectCompetence(Request $request)
{
    $competenceId = $request->request->get('competence');
    if ($competenceId) {
        return $this->redirectToRoute('modifier_competence', ['id' => $competenceId]);
    }
    return $this->redirectToRoute('select_competence');
}

}
