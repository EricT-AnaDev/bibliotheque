<?php

namespace App\Controller;

use App\Repository\LivresRepository;
use App\Entity\Livres;
use App\Form\LivresType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class LivreController extends AbstractController
{
    /**
     * @Route("/showLivres", name="livres")
     */
    public function index(ManagerRegistry $doctrine)
    {
        $repo = $doctrine->getRepository(Livres::class);
        $livres = $repo->findAll();
        return $this->render('livre/index.html.twig', [
           "livres" => $livres
        ]);
    }
    
    /**
     * @route("/modifierLivre/{id}", name="modifierLivre")
     * @Route ("/ajoutLivre", name="ajoutLivre")
     */
    public function add(ManagerRegistry $doctrine, Request $request, UserInterface $user, Livres $livres = null)
    {
        if(!$livres)
        {
            $livres = new Livres();
        }
        $form = $this->createForm(LivresType::class, $livres);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $livres->setUser($user);
            $om = $doctrine->getManager();
            $om->persist($livres);
            $om->flush();
            return $this->redirectToRoute('livres');
        }
        $mode = false;
        if($livres->getId() !== null)
        {
            $mode =true;
        }
        return $this->render('livre/add.html.twig', [
            "formulaireLivres" => $form->createView(),
            "mode" => $mode
         ]);
    }

    /**
     * @Route("/supprimerLivre/{id}", name="supprimerLivre")
     */
    public function remove(ManagerRegistry $doctrine, Livres $livres)
    {
        $om = $doctrine->getManager();
        $om->remove($livres);
        $om->flush();
        return $this->redirectToRoute('livres');
    }
}  