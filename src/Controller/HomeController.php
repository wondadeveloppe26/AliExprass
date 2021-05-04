<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProductRepository $repoProduct): Response
    {
        $products = $repoProduct->findAll();
        $productBestSeller = $repoProduct->findByIsBestSeller(1);
        $productSpecialOffer = $repoProduct->findByIsSpecialOffer(1);
        $productNewArrival = $repoProduct->findByIsNewArrival(1);
        $productFeatured = $repoProduct->findByIsFeatured(1);
        //dd([$productBestSeller, $productSpecialOffer, $productNewArrival, $productFeatured]);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'products' => $products,
            'productBestSeller' => $productBestSeller,
            'productSpecialOffer' => $productSpecialOffer,
            'productNewArrival' => $productNewArrival,
            'productFeatured' => $productFeatured
        ]);
    }
    /**
     * @Route("/product/{slug}", name="product_details")
     */
    public function show(?Product $product): Response
    {

        if (!$product) {
            return $this->redirectToRoute("home");
        }
        return $this->render("home/single_product.html.twig", [
            'product' => $product
        ]);
    }
}
