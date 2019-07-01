<?php
namespace App\Controller;


use App\Entity\Canopy;
use App\Entity\Rent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MainController extends Controller
{

    /**
     * @param Request $request
     * @param AuthorizationCheckerInterface $auth
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/admin", name="home")
     */
    public function index(Request $request, AuthorizationCheckerInterface $auth)
    {
        if ($auth->isGranted('ROLE_ADMIN') === false)
            return $this->redirectToRoute('login');
        $rent = new Rent();
        $repository = $this->getDoctrine()->getRepository(Rent::class);
        $lastRent = $repository->findLastOne();
        $lastDate = $lastRent ? $lastRent->getRentDate() : '';

        $form = $this->createFormBuilder($rent)
        ->add('rent_date', DateType::class, ['label' => 'Дата', 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $lastDate])
        ->add('canopy', EntityType::class, ['label' => 'Система', 'class' => Canopy::class, 'choice_label' => 'name'])
        ->add('count', IntegerType::class, ['label' => 'Число аренд'])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rent = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($rent);
            $em->flush();
        }

        $rentList = $repository->findByLastCount(10);

        return $this->render('main.html.twig', ['form' => $form->createView(), 'rents' => $rentList]);
    }

    /**
     * @param Request $request
     * @param Rent $rent
     * @param AuthorizationCheckerInterface $auth
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/edit/{rent}", name="edit")
     */
    public function edit(Request $request, Rent $rent, AuthorizationCheckerInterface $auth)
    {
        if ($auth->isGranted('ROLE_ADMIN') === false)
            return $this->redirectToRoute('login');
        $form = $this->createFormBuilder($rent)
            ->add('rent_date', DateType::class, ['label' => 'Дата', 'widget' => 'single_text', 'format' => 'yyyy-MM-dd',])
            ->add('canopy', EntityType::class, ['label' => 'Система', 'class' => Canopy::class, 'choice_label' => 'name'])
            ->add('count', IntegerType::class, ['label' => 'Число аренд'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rent = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($rent);
            $em->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('edit_rent.html.twig', ['form' => $form->createView(), 'rent' => $rent]);
    }

    /**
     * @Route("/rents/{page}", name="rents")
     * @param integer $page
     * @return Response
     */
    public function rents($page=1)
    {
        $repository = $this->getDoctrine()->getRepository(Rent::class);
        $rents = $repository->getAllRents($page);
        $countRents = count($repository->findAll());
        $maxPages = ceil($countRents / 20);
        return $this->render('all_rents.html.twig', ['rents' => $rents, 'maxPages' => $maxPages, 'thisPage' => $page]);
    }

}