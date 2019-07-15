<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\DoctrineBundle\Repository\EmployerEntityRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Employer;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\EmployerRepository;
use App\Repository\ServiceRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

//use App\DataFixtures\EmployerFixtures;
class MonController extends AbstractController
{
    /**
     * @Route("/mon", name="mon")
     */
    public function index()
    {
        return $this->render('mon/index.html.twig', [
            'controller_name' => 'MonController',
        ]);
    }
    /**
     * @Route("/", name="lister") 
     */
    public function Lister(){
        $repo=$this->getDoctrine()->getRepository(Employer::class);
       $employer=$repo->findAll();
        return $this->render('mon/index.html.twig',[
            'employer'=>$employer]);
    }

    /**
     * @Route("/mon/new", name="mon_form")
     *@Route("/mon/{id}/edit", name="edit_form")

     */
    public function form(Employer $employer=null, Request $requete, ObjectManager $manager){
if(!$employer){
        $employer=new Employer;
}
        $form = $this->createFormBuilder($employer)

        ->add('matricule', TextType::class,[
            'attr'=>[
                'class'=>'form-control'
            ]
        ]
        )
        ->add('nomcomplet', TextType::class,[
            'attr'=>[
                'class'=>'form-control'
            ]
        ]
        )
        ->add('email', TextType::class,[
            'attr'=>[
                'class'=>'form-control'
            ]
        ]
        )
        ->add('datenais', DateType::class,[
            'attr'=>[
                'class'=>'form-control'
            ]
        ]
        )
        ->add('salaire', TextType::class,[
            'attr'=>[
                'class'=>'form-control'
            ]
        ]
        )
        ->add('service',EntityType::class,[
            'class'=>Service::class,
            'choice_label'=>'libelle'
        ]
    )
        ->getForm(); 
        $form->handleRequest($requete);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($employer);
            $manager->flush();
            return $this->redirectToRoute('mon_form',['id' => $employer->getId()]);
        }
        return $this->render('mon/form.html.twig', [
            'formEmployer' => $form->createView(),
            'editMode' => $employer->getId()!== null
        ]);
    }
    /**
     *@Route("/mon/{id}/supprimer", name="sup_objet")

     */
    public function sup(Employer $employer, ObjectManager $manager){
        $manager->remove($employer);
        $manager->flush();
        return $this->redirectToRoute('lister');
    }
}
