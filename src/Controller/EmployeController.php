<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeController extends AbstractController
{
    /*
    -- 1. Action
    Lorsque vous créez une fonction dans un Controller, cela devient une "action".
    Une "action" commence toujours par un verbe (sauf 'home'). La convention de nommage est le camelCase !

    -- 2. Injection de dépendances
    Dans les parenthèses d'une fonction ("action") vous allez, peut-être, avoir besoin d'outils (objet).
    Pour vous en servir, dans Symfony, on injectera des dépendances. Cela revient à les définir comme 'paramètres'.

    -- 3. Route
    La route, depuis PHP 8, peut s'écrire sous forme d'attribut, cela permet de dissocier des Annotations !
    Cela se traduit par une syntaxe différente. Une route prendra TOUJOURS 3 arguments :
        * a - une URI, qui est un bout d'URL.
        * b - un 'name', qui permet de nommer la route pour s'en servir plus tard.
        * c - une méthode HTTP, qui autorise telle ou telle requête HTTP. Question de sécurité !
    /!\ TOUTES VOS ROUTES DOIVENT ETRE COLLÉES A VOTRE FONCTION /!\
    */
    #[Route('/ajouter-un-employe', name: 'create_employe', methods: ['GET', 'POST'])]
    public function createEmploye(Request $request, EntityManagerInterface $entityManager): Response
    {
        // ---------------- 1ere Méthode : GET -------------------- //
        # Instanciation d'un objet de type Employe.
        $employe = new Employe();

        # Nous créons une variable $form qui contiendra le formulaire crée par la méthode CreateForm()
        # Le mécanisme d'auto-hydratation se afit concrétement par l'ajout d'un second argument dans la méthode createForm(). On passera $employe en argument.
        $form = $this->createForm(EmployeFormType::class, $employe);

        # Pour que le mécanisme de base de Symfony soit respecté, on devra manipuler la requête avec la méthode handleRequest() et l'objet $request
        $form->handleRequest($request);

        // ---------------- 2eme Méthode : POST ------------------- //
        if($form->isSubmitted() && $form->isValid()) {

            # Nous devons renseigner une valeur pour la propriété createdAt car cette valeur ne peut pas être "null" et n'est pas setter par le formulaire.
            $employe->setCreatedAt(new DateTime());

            # Nous insérons en BDD grâce à notre $entityManager et la méthode persist().
            $entityManager->persist($employe);

            # Nous devons "vider" (trad de flush) l'entityManager pour réellement ajouter une ligne en BDD.
            $entityManager->flush();

            # Pour terminer, nous devons rediriger l'utilisateur sur une page html.
            # Nous utilisons la méthode redirectToRoute() pour faire la redirection.
            return $this->redirectToRoute('default_home');
        }

        // ---------------- 1ere Méthode : GET -------------------- //
        # On peut directement return pour rendre la vue (page HTML) du formulaire.
        return $this->render('form/employe.html.twig', [
            'form_employe' => $form->createView()
        ]);
    } // end function create 
} // end class