<?php

namespace App\Controller\Front;

use App\Entity\Category;
use App\Entity\Participation;
use App\Entity\Project;
use App\Entity\Tag;
use App\Form\ParticipationFormType;
use App\Form\ProjectFormType;
use App\Repository\ProjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    private $projects_repository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projects_repository = $projectRepository;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        return $this->render('front/main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        return $this->render('front/main/about.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/infos", name="infos")
     */
    public function infos()
    {
        return $this->render('front/main/infos.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/privacy", name="privacy")
     */
    public function privacy()
    {
        return $this->render('front/main/privacy.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('front/main/contact.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/project/add",name="project_add")
     */
    public function addProject(Request $request) {
        $project = new Project();
        $form = $this->createForm(ProjectFormType::class,$project);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // Associer le projet à l'utilisateur connecté
            $project->setProposePar($this->getUser());
            $em->persist($project);
            $em->flush();
            $this->addFlash('info','Merci ! Votre projet a été proposé au maître Jedi !');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('front/main/add_project.html.twig',[
           'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/projects", name="projects")
     */
    public function projects() {
       $projects= $this->projects_repository->findAll();
       return $this->render('front/main/projects.html.twig',['projects'=>$projects]);
    }

    /**
     *
     * @Route("/project/{id}",name="project_show")
     */
    public function projectShow(Request $request) {
        $categories =
        $project = $this->projects_repository->find($request->get('id'));
        $participation = new Participation();
        $form  = $this->createForm(ParticipationFormType::class,$participation);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // Associer le projet à la participation
            $participation->setProject($project);
            // Associer le user identifié à la participation
            $participation->setUser($this->getUser());

            $em->persist($participation);
            $em->flush();
            $this->addFlash('success',"A toi de jouer petit Padawan !");
            return $this->redirectToRoute('project_show',['id'=>$project->getId()]);
        }

        return $this->render('front/main/project_show.html.twig',
            ['project'=>$project,'form'=>$form->createView()]);
    }


    /**
     * @Route("projects/category/{id}", name="projects_by_category")
     */
    public function viewByCategory(Category $category) {
        // SELECT * FROM projects
//        $projects =   $this->projects_repository->findAll();

        // SELECT * FROM projects AS p WHERE p.category_id = $category_id
        $projects = $this->projects_repository->findByCategory($category);
        return $this->render('front/main/projects.html.twig',
            ['projects'=>$projects,'category'=>$category]);

    }

    /**
     * @Route("projects/tag/{id}", name="projects_by_tag")
     *
     */
    public function viewByTag(Tag $tag) {
        $projects =
            $this->projects_repository
            ->findByTag(array($tag->getId()));

        return $this->render('front/main/projects.html.twig',['projects'=>$projects,'tag'=>$tag]);
    }





    /**
     * Permet de renvoyer la vue "partial" des catégories
     * > méthode non liée à une route
     *
     */
    public function categories() {
        // Récupérer les catégories de notre DB via CategoryRepository findAll()
        $categories = $this->getDoctrine()
            ->getRepository('App\Entity\Category')->findAll();

        return $this->render('partials/categories.html.twig',['categories'=>$categories]);
    }



}
