<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function index(Request $request): Response
    {
        $Product = new Product();
        $Products = $this->em->getRepository(Product::class)->findAll();

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
            'Products' => $Products
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

    public function editarProductos($id){

        $Product = $this->em->getRepository(Product::class)->find($id);

        if (!$Product) {
            throw $this->createNotFoundException(
                'No product found for id '
            );
        }

        //$Product->SetName('My new update');
        //$Product->setActive('1');
        //$Product->setupdateAt(new \Datetime());

        $this->em->persist($Product);
        $this->em->flush();

        return $this->render('product/editar.html.twig');
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



}
