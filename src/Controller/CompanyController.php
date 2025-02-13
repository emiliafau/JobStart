<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Annonce;
use App\Form\AnnonceType;


class CompanyController extends AbstractController
{
   public function createAdd(Request $request , EntityManagerInterface $entityManager): Response
   {
    $annonce = new Annonce();
    $form = $this ->createForm(AnnonceType::class, $annonce);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
        $entityManager->persist($annonce);
        $entityManager->flush();

        return $this->redirectToRoute('dashboard_company');
    }
        return $this->render('home/create_add.html.twig', [
        'form' =>$form->createView(),
        ]);
    }
}