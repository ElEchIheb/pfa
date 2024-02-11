<?php
namespace App\Controller;

use App\Entity\Psycho;
use App\Form\PsychoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Psy;
use Symfony\Component\Security\Core\Security;
use App\Entity\ProfilposteResultat;
use App\Entity\Resultat;


class PsychoController extends AbstractController
{
    private $security;
    private $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }
/**
 * @Route("/psycho/new", name="psycho_new", methods={"GET","POST"})
 */
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $user = $this->getUser();

    $psys = $entityManager->getRepository(Psy::class)->findAll();
    $forms = [];
    $submittedPsyId = $request->request->get('psy_id'); // Get the submitted psy ID

    foreach ($psys as $psy) {
        $psycho = new Psycho();
        $form = $this->createForm(PsychoType::class, $psycho);
        $form->handleRequest($request);

        // Check if the form associated with this psy was submitted
        if ($form->isSubmitted() && $form->isValid() && $psy->getId() == $submittedPsyId) {
            $psycho->setPsy($psy);

            $psycho->setFirstname($user->getFirstname());
            $psycho->setLastname($user->getLastname());

            $entityManager->persist($psycho);
            $entityManager->flush();
            return $this->redirectToRoute('psycho_new');
        }
        $forms[] = [
            'psy' => $psy,
            'form' => $form->createView(),
        ];
    }
    $connectedUser = $this->security->getUser();
    $showProfilPosteLink = !$this->entityManager->getRepository(ProfilposteResultat::class)->findOneBy(['user' => $connectedUser]);
    $showCommencerTestLink = !$this->entityManager->getRepository(Resultat::class)->findOneBy(['user' => $connectedUser]);

    return $this->render('psycho/new.html.twig', [
        'forms' => $forms,
        'showProfilPosteLink' => $showProfilPosteLink,
        'showCommencerTestLink' => $showCommencerTestLink,
    ]);
}
/**
 * @Route("/psycho/select", name="psycho_select")
 */
public function selectUser(EntityManagerInterface $entityManager): Response
{
    $query = $entityManager->createQuery(
        'SELECT DISTINCT e.firstname, e.lastname
        FROM App\Entity\Psycho e'
    );

    $users = $query->getResult();

    return $this->render('psycho/select.html.twig', [
        'users' => $users,
    ]);
}

/**
 * @Route("/psycho/display", name="psycho_display", methods={"GET" , "POST"})
 */
public function displayResults(Request $request, EntityManagerInterface $entityManager): Response
{
    $user = explode('_', $request->request->get('user'));
$firstname = $user[0] ?? null;
$lastname = $user[1] ?? null;

if(!$firstname || !$lastname) {
    // Handle the error, perhaps with a redirect or an error message
}


    $psychos = $entityManager->getRepository(Psycho::class)->findBy([
        'firstname' => $firstname,
        'lastname' => $lastname,
    ]);

    return $this->render('psycho/display.html.twig', [
        'psychos' => $psychos,
    ]);
}

/**
 * @Route("/psycho/delete", name="psycho_delete")
 */
    public function deleteForm(Request $request, EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(Psycho::class)
            ->createQueryBuilder('e')
            ->select('e.firstname, e.lastname')
            ->distinct()
            ->getQuery()
            ->getResult();

        if ($request->isMethod('POST')) {
            $selectedUser = explode(',', $request->request->get('userSelect'));
        
            $selectedFirstname = $selectedUser[0];
            $selectedLastname = $selectedUser[1];
        
            // Use Doctrine to find evaluations of the selected user
            $psychos = $entityManager->getRepository(Psycho::class)
                ->findBy(['firstname' => $selectedFirstname, 'lastname' => $selectedLastname]);
        
            // Delete the found evaluations
            foreach ($psychos as $psycho) {
                $entityManager->remove($psycho);
            }
        
            $entityManager->flush();
            $this->addFlash('success', 'Les évaluations ont été supprimées avec succès.');
            // Redirect or display a confirmation message
        }
        
        return $this->render('psycho/delete.html.twig', [
            'users' => $users,
        ]);
    }


}
