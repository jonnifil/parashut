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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{

    /**
     * @param Request $request
     * @return Response
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {
        $rent = new Rent();

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
        }

        $repository = $this->getDoctrine()->getRepository(Rent::class);
        $rentList = $repository->findByLastCount(10);

        return $this->render('main.html.twig', ['form' => $form->createView(), 'rents' => $rentList]);
    }

    /**
     * @param Request $request
     * @param Rent $rent
     * @return Response
     * @Route("/edit/{rent}", name="edit")
     */
    public function edit(Request $request, Rent $rent)
    {
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
}