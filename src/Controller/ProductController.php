<?php

namespace App\Controller;

use App\Form\SearchProductType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Form\ProductType;


class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/list", name="listProduct")
     */
    public function listProduct(Request $request, PaginatorInterface $paginator)
    {
        //return new Response("La liste des produits");
        $products=$this->getDoctrine()->getRepository(Product::class)->findAll();
        $enabledProduct= $this->getDoctrine()->getRepository(Product::class)->enabledProduct();
        $formSearch= $this->createForm(SearchProductType::class);
        $formSearch->handleRequest($request);
        //pagination
        $allProducts = $paginator->paginate(
            $products, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), 2 // Nombre de résultats par page
        );
        if($formSearch->isSubmitted()){
            $name= $formSearch->getData()->getName();
            $SearchProducts = $this->getDoctrine()->getRepository(Product::class)->search($name);
            return $this->render("product/listProducts.html.twig",array('listProducts'=>$SearchProducts,'searchForm'=>$formSearch->createView()));
        }
        return $this->render("product/listProducts.html.twig",array('enabledProduct'=>$enabledProduct,'listProducts'=>$allProducts,'searchForm'=>$formSearch->createView()));

    }

    /**
     * @Route("/removeProduct/{id}", name="removeProduct")
     */

    public function deleteProduct($id){
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();
        return
         $this->redirectToRoute("listProduct");
    }

    /**
     * @Route("/showProduct/{id}", name="showProduct")
     */

    public function showProduct($id){
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        //var_dump($product).die();
        return $this->render("product/showProduct.html.twig",array('product'=>$product));
   }
    /**
     * @Route("/addProduct", name="addProduct")
     */
   public function addProduct(Request $request){
       $product = new Product();
       $formProduct = $this->createForm(ProductType::class,$product);
       $formProduct->handleRequest($request);
       if($formProduct->isSubmitted() and $formProduct->isValid()){
           $em=$this->getDoctrine()->getManager();
           $em->persist($product);
           $em->flush();
           return $this->redirectToRoute("listProduct");
       }

       return $this->render("product/addProduct.html.twig",array('formProduct'=>$formProduct->createView()));
   }

    /**
     * @Route("/updateProduct/{id}", name="updateProduct")
     */
    public function updateProduct(Request $request, $id){
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $formProduct = $this->createForm(ProductType::class,$product);

        $formProduct->handleRequest($request);

        if($formProduct->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("listProduct");
        }
        return $this->render("product/updateProduct.html.twig",array('formProduct'=>$formProduct->createView()));
    }
    /**
     * @Route("/sortByPrice", name="sortByPrice")
     */
    public function sortByPrice()
    {
        //$products = $this->getDoctrine()->getRepository(Product::class)->sortByPrice();
        $products = $this->getDoctrine()->getRepository(Product::class)->orderByPriceQb();
        return $this->render("product/sortByPrice.html.twig",array('listProducts'=>$products));
    }


}
