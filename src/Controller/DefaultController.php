<?php

namespace App\Controller;

use App\Entity\Employe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default_home')]
    public function home(EntityManagerInterface $entityManager): Response
    {
        # Cette instruction nous permet de récupérer en BDD toutes les lignes de la table "employe".
        # Ceci est possible grâce au Repository, accessible par $entityManager.
        $employes = $entityManager->getRepository(Employe::class)->findAll();

        # Nous devons maitenant passer la varibale $employes (qui contient tous les employés de la BDD) à notre vue Twig, pour pouvoir afficher les différentes données.
        return $this->render('default/home.html.twig', [
            'employes' => $employes
        ]);
    }
}