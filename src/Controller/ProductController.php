<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProductController extends AbstractController
{   private $em;

    /**
     * @param $em
     */

    public function __construct(EntityManagerInterface $em)
    {
        $this->em =$em;
    }


    /**
     * @Route("/ ", name="app_product")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $Product = new Product();
        $Products = $this->em->getRepository(Product::class)->findAllProduct();

        $pagination = $paginator->paginate(
            $Products, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            2 /*limit per page*/
        );


        $form = $this->createForm(ProductType::class, $Product);
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid()){

            $Product->setCreatedAt(new \Datetime());


            $this->em->persist($Product);
            $this->em->flush();
            return $this->redirectToRoute('app_product');
        }

        return $this->render('product/index.html.twig', [
            'form' => $form->createView(),
            'Products' => $pagination
        ]);
    }


     /**
      * @Route("/insertar", name="insertProduct")
     */

     public function insertProduct(Request $request){

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $product->setCreatedAt(new \Datetime());
        $entityManager->persist($product);
        $entityManager->flush();

        return $this->redirectToRoute('app_product');
        }

        return $this->render('product/insertar.html.twig', [
            'Products' => $product,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}/editar", name="editarProductos")
     */

    public function editarProductos($id,Request $request){

        $Product = new Product();
        $Products = $this->em->getRepository(Product::class)->find($id);

        $form = $this->createForm(ProductType::class, $Products);
        $form->handleRequest($request);

        if (!$form) {
            throw $this->createNotFoundException(
                'No product found for id '
            );
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
        $Product->SetName('My newwww update');
        $Product->setCode('1');
        $Product->setupdateAt(new \Datetime());
            $entityManager->flush();
            return $this->redirectToRoute('app_product');
        }



        return $this->render('product/editar.html.twig',['Product' => $Products]);

    }



    /**
     * @Route("/{id}", name="removeProduct", methods={"GET"})
     */


    public function removeProduct($id){

        $Product = $this->em->getRepository(Product::class)->find($id);

        if (!$Product) {
            throw $this->createNotFoundException(
                'No product found for id '
            );
        }

        $this->em->remove($Product);
        $this->em->flush();

        return $this->render('product/eliminado.html.twig');
    }

    /**
     * @Route("/", name="descargarxls")
     */

    public function DescargarExcel(){

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Hello World !');

        $writer = new Xlsx($spreadsheet);
        $writer->save('hello world.xlsx');

    }



}
