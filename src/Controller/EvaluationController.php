<?php
namespace App\Controller;

use App\Entity\Evaluation;
use App\Form\EvaluationType;
use Doctrine\ORM\EntityManagerInterface; // Ajoutez cette ligne
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Competence;
use Symfony\Component\Security\Core\Security;
use App\Entity\ProfilposteResultat;
use App\Entity\Resultat;


class EvaluationController extends AbstractController
{
    private $security;
    private $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }
/**
 * @Route("/evaluation/new", name="evaluation_new", methods={"GET","POST"})
 */
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $user = $this->getUser();

    $competences = $entityManager->getRepository(Competence::class)->findAll();
    $forms = [];
    $submittedCompetenceId = $request->request->get('competence_id'); // Get the submitted competence ID

    foreach ($competences as $competence) {
        $evaluation = new Evaluation();
        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);

        // Check if the form associated with this competence was submitted
        if ($form->isSubmitted() && $form->isValid() && $competence->getId() == $submittedCompetenceId) {
            $evaluation->setCompetence($competence);

            $evaluation->setFirstname($user->getFirstname());
            $evaluation->setLastname($user->getLastname());


            $entityManager->persist($evaluation);
            $entityManager->flush();
            return $this->redirectToRoute('evaluation_new');
        }
        $forms[] = [
            'competence' => $competence,
            'form' => $form->createView(),
        ];
    }
    $connectedUser = $this->security->getUser();
    $showProfilPosteLink = !$this->entityManager->getRepository(ProfilposteResultat::class)->findOneBy(['user' => $connectedUser]);
    $showCommencerTestLink = !$this->entityManager->getRepository(Resultat::class)->findOneBy(['user' => $connectedUser]);

    return $this->render('evaluation/new.html.twig', [
        'forms' => $forms,
        'showProfilPosteLink' => $showProfilPosteLink,
            'showCommencerTestLink' => $showCommencerTestLink,
    ]);
}

/**
 * @Route("/evaluation/select", name="evaluation_select")
 */
public function selectUser(EntityManagerInterface $entityManager): Response
{
    $query = $entityManager->createQuery(
        'SELECT DISTINCT e.firstname, e.lastname
        FROM App\Entity\Evaluation e'
    );

    $users = $query->getResult();

    return $this->render('evaluation/select.html.twig', [
        'users' => $users,
    ]);
}


/**
 * @Route("/evaluation/display", name="evaluation_display", methods={"POST"})
 */
public function displayResults(Request $request, EntityManagerInterface $entityManager): Response
{
    $user = explode('_', $request->request->get('user'));
    $firstname = $user[0];
    $lastname = $user[1];

    $evaluations = $entityManager->getRepository(Evaluation::class)->findBy([
        'firstname' => $firstname,
        'lastname' => $lastname,
    ]);

    return $this->render('evaluation/display.html.twig', [
        'evaluations' => $evaluations,
    ]);
}
/**
 * @Route("/evaluation/delete", name="evaluation_delete")
 */
public function deleteForm(Request $request, EntityManagerInterface $entityManager): Response
{
    $users = $entityManager->getRepository(Evaluation::class)
        ->createQueryBuilder('e')
        ->select('e.firstname, e.lastname')
        ->distinct()
        ->getQuery()
        ->getResult();

        if ($request->isMethod('POST')) {
            $selectedUser = explode(',', $request->request->get('userSelect'));
        
            $selectedFirstname = $selectedUser[0];
            $selectedLastname = $selectedUser[1];
        
            // Utilisez Doctrine pour trouver les évaluations de l'utilisateur sélectionné
            $evaluations = $entityManager->getRepository(Evaluation::class)
                ->findBy(['firstname' => $selectedFirstname, 'lastname' => $selectedLastname]);
        
            // Supprimez les évaluations trouvées
            foreach ($evaluations as $evaluation) {
                $entityManager->remove($evaluation);
            }
        
            $entityManager->flush();
            $this->addFlash('success', 'Les évaluations ont été supprimées avec succès.');
            // Redirigez ou affichez un message de confirmation
        }
        

    return $this->render('evaluation/delete.html.twig', [
        'users' => $users,
    ]);
}

}
