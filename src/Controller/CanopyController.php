<?php
/**
 * Created by PhpStorm.
 * User: jonni
 * Date: 12.06.19
 * Time: 17:18
 */

namespace App\Controller;


use App\Entity\Canopy;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CanopyController extends Controller
{

    /**
     * @Route("/canopy", name="canopy")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Canopy::class);
        $canopyList = $repository->findAll();
        return $this->render('canopies.html.twig', ['canopies' => $canopyList]);
    }

    /**
     * @Route("/canopy/add/{canopy}", name="canopy-add")
     * @param Request $request
     * @param Canopy $canopy
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request, Canopy $canopy=null)
    {
        $message = 'Редактирование системы';
        if (!$canopy) {
            $message = 'Добавление системы для аренды';
            $canopy = new Canopy();
        }

        $form = $this->createFormBuilder($canopy)
            ->add('number', IntegerType::class, ['label' => 'Инв. номер'])
            ->add('main', TextType::class, ['label' => 'Основной'])
            ->add('reserv', TextType::class, ['label' => 'Запасной'])
            ->add('rig', TextType::class, ['label' => 'Ранец'])
            ->add('aad', TextType::class, ['label' => 'Прибор'])
            ->add('pack_date', DateType::class, ['label' => 'Дата укладки ПЗ', 'widget' => 'single_text', 'format' => 'yyyy-MM-dd',])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $canopy = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($canopy);
            $em->flush();
            return $this->redirectToRoute('canopy');
        }
        return $this->render('canopy_add.html.twig', ['form' => $form->createView(), 'message' => $message]);
    }
}