<?php

namespace App\Controller;
use App\Entity\Eleve;
use App\Entity\Classe;
use App\Repository\ClasseRepository;
use App\Repository\EleveRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EcoleController extends AbstractController
{
    /**
     * @Route("/ecole/{id}", name="ecole")
     */
    public function index(EleveRepository $repo, Classe $classe): Response
    {
        //on demande a doctrine un repository pour l'entité Eleve::class

        $eleves = $repo->findBy([
            'classe' => $classe
        ]);
        return $this->render('ecole/index.html.twig', [
            'controller_name' => 'EcoleController',
            'eleves'          => $eleves //twig: on créé une variable eleve qui contiendra le contenu de la variable $eleves
        ]);
    }
    /**
     * @route("/", name="home")
     */
    public function home(ClasseRepository $repo) {
        return $this->render('ecole/home.html.twig', [
            'classes' => $repo->findAll()
        ]);//appel d'un fichier twig pour l'afficher

    }
    

    /**
     * @route("/eleve/new", name="eleve_create")
     * @route("/eleve/{id}/edit", name="eleve_edit")
     */
    public function form(Eleve $eleve = null , Request $request, EntityManagerInterface $manager){

        if(!$eleve){
        $eleve = new Eleve();
        }



        //on demande simplement de créer un objet formulaire qui est lié a l'entité Eleve
        $form = $this->createFormBuilder($eleve)
            ->add('nom')
            ->add('prenom')
            ->add('dateNaissance', BirthdayType::class)
            ->add('moyenne')
            ->add('appreciation')
            ->add('classe', EntityType::class, [
                'class' => Classe::class,
                'choice_label' => 'name'
            ])
            ->getForm(); //on veut le resultat final

            //analyse de la requete http passée en parametre
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //sert pour ajouter et mettre a jour
        $manager->persist($eleve);

        $manager->flush();
        

        return $this->redirectToRoute('eleve_show', ['id' => $eleve->getId()]);
        }
        return $this->render('ecole/create.html.twig', [
            'formEleve'=> $form->createView(), //on passe a twig cet objet affichable
            'editMode' => $eleve->getId() !== null, 
            'eleve'    => $eleve
        ]);
    }


    /**
     * @route("/eleve/{id}", name="eleve_show")
     */
    //grace au param converter, symfony voit la route qui a un id, il voit que la fn a besoin d'un eleve
    //il va donc chercher k'eleve qui a l'id
    public function show(Eleve $eleve) {
        return $this->render('ecole/show.html.twig', [
            'eleve' => $eleve
        ]);

    }

/**
 * @route("/eleve/delete/{id}", name="eleve_delete")
 */
    public function delete(Eleve $eleve, EntityManagerInterface $manager){
        $manager->remove($eleve);
        $manager->flush();
        return $this->redirectToRoute('home');

    }
}
