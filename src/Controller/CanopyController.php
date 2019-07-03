<?php
/**
 * Created by PhpStorm.
 * User: jonni
 * Date: 12.06.19
 * Time: 17:18
 */

namespace App\Controller;


use App\Entity\Canopy;
use App\Entity\CanopyImage;
use App\Entity\Month;
use App\Service\CanopyImageUploader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CanopyController extends Controller
{

    /**
     * @Route("/", name="canopies")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Canopy::class);
        $canopyList = $repository->findAll();
        return $this->render('canopies.html.twig', ['canopies' => $canopyList]);
    }

    /**
     * @Route("/view/{canopy}", name="view")
     * @param Canopy $canopy
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewCanopy(Canopy $canopy)
    {
        return $this->render('canopy_view.html.twig', [
            'canopy' => $canopy,
            'files' => $canopy->getImages()
        ]);

    }

    /**
     * @param Request $request
     * @param Canopy|null $canopy
     * @param AuthorizationCheckerInterface $auth
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/canopy/add/{canopy}", name="canopy-add")
     */
    public function add(Request $request, Canopy $canopy=null, AuthorizationCheckerInterface $auth)
    {
        if ($auth->isGranted('ROLE_ADMIN') === false)
            return $this->redirectToRoute('login');
        $message = 'Редактирование системы';
        $edit = true;
        if (!$canopy) {
            $message = 'Добавление системы для аренды';
            $canopy = new Canopy();
            $edit = false;
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
            return $this->redirectToRoute('canopies');
        }
        return $this->render('canopy_add.html.twig', [
            'form' => $form->createView(),
            'message' => $message,
            'edit' => $edit,
            'canopy' => $canopy
        ]);
    }

    /**
     * @Route("/canopy/month", name="month-rents")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function month(Request $request)
    {
        $month = date('n');
        $year = date('Y');
        $monthForm = new Month();
        $form = $this->createFormBuilder($monthForm)
            ->add('year', ChoiceType::class, ['label' => 'Выберите год', 'choices' => Month::getYearList()])
            ->add('month', ChoiceType::class, ['label' => 'Выберите месяц', 'choices' => Month::MONTH_LIST, 'data' => $month])
            ->getForm()
        ;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $year = $data->getYear();
            $month = $data->getMonth();
        }
        $repository = $this->getDoctrine()->getRepository(Canopy::class);
        $results = $repository->findSumByMonth($year, $month);
        $sum = 0;
        if ($results) {
            foreach ($results as $result)
                $sum += $result['sum'];
        }
        return $this->render('canopy_rents.html.twig', ['results' => $results, 'sum' => $sum, 'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param Canopy $canopy
     * @param AuthorizationCheckerInterface $auth
     * @param CanopyImageUploader $fileUploader
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/canopy/file/{canopy}", name="canopy-file")
     */
    public function fileUpload(Request $request, Canopy $canopy, AuthorizationCheckerInterface $auth, CanopyImageUploader $fileUploader)
    {
        if ($auth->isGranted('ROLE_ADMIN') === false)
            return $this->redirectToRoute('login');
        $image = new CanopyImage();
        $imageForm = $this->createFormBuilder()
            ->add('file', FileType::class, ['label' => 'Выберите файл'])
            ->getForm();

        $imageForm->handleRequest($request);

        if ($imageForm->isSubmitted() && $imageForm->isValid()) {
            $data = $imageForm->getData();
            $file = $data['file'];
            $fileName = $fileUploader->upload($file);
            $image->setFile($fileName);
            $image->setCanopy($canopy);
            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush();
        }
        $files = $canopy->getImages();
        $count = count($files);
        return $this->render('canopy_file.html.twig', [
            'canopy' => $canopy,
            'files' => $files,
            'count' => $count,
            'image' => $imageForm->createView()
        ]);
    }
}