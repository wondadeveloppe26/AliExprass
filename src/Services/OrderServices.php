<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Cart;
use App\Entity\CartDetails;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Repository\ProductRepository;

class OrderServices
{
    private $manager;
    private $repoProduct;

    public function __construct(EntityManagerInterface $manager, ProductRepository $repoProduct)
    {
        $this->manager = $manager;
        $this->repoProduct = $repoProduct;
    }


    public function createOrder($cart)
    {
        $order = new Order();
        $order->setReference($cart->getReference())
            ->setCarrierName($cart->getCarrierName())
            ->setCarrierPrice($cart->getCarrierPrice() / 100)
            ->setFullName($cart->getFullName())
            ->setDeliveryAddress($cart->getDeliveryAddress)
            ->setMoreInformations($cart->MoreInformations)
            ->setQuantity($cart->getQuantity())
            ->setSubTotalHT($cart->getSubTotalHT() / 100)
            ->setTaxe($cart->getTaxe() / 100)
            ->setSubTotalTTC($cart->getSubTotalTTC() / 100)
            ->setUser($cart->getUser())
            ->setCreatedAt($cart->getCreatedAt());
        $this->manager->persist($order);


        $products = $cart->getCartDétails()->getValues();

        foreach ($products as $cart_product) {
            $orderDetails = new OrderDetails();
            $orderDetails->setOrders($order)
                ->setProductName($cart_product->getProductName())
                ->setProductPrice($cart_product->getProductPrice())
                ->setQuantity($cart_product->getQuantity())
                ->setSubTotalHT($cart_product->getSubTotalHT())
                ->setSubTotalTTC($cart_product->getSubTotalTTC())
                ->setTaxe($cart_product->getTaxe());
        }

        $this->manager->flush();

        return $order;
    }
    public function getLineItems($cart)
    {
        $cartDetails = $cart->getCartDétails();

        $line_items = [];
        foreach ($cartDetails as $details) {
            # code...
            $product = $this->$repoProduct->findOneByName($details->getProductName());

            $line_items[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getName(),
                        'images' => [$_ENV['YOUR_DOMAIN'] . '/uploads/products/' . $product->getImage()],
                    ],
                ],
                'quantity' => $details->getQuantity(),
            ];
        }
        //Carrier

        $line_items[] = [
            'price_data' => [
                'currency' => 'usd',
                'unit_amount' => $cart->getCarrierPrice(),
                'product_data' => [
                    'name' => 'Carrier (' . $cart->getCarrierName() . ')',
                    'images' => [$_ENV['YOUR_DOMAIN'] . '/uploads/products/'],
                ],
            ],
            'quantity' => 1,
        ];

        // Taxe

        $line_items[] = [
            'price_data' => [
                'currency' => 'usd',
                'unit_amount' => $cart->getTaxe(),
                'product_data' => [
                    'name' => 'TVA (20%)',
                    'images' => [$_ENV['YOUR_DOMAIN'] . '/uploads/products/'],
                ],
            ],
            'quantity' => 1,
        ];


        return $line_items;
    }

    public function saveCart($data, $user)
    {
        /*[
            'product'=>[],
            'data'=>[],
            'checkout'=>[
                'address'=> Objet,
                'carrier'=>Objet
            ]
        ]*/
        $cart = new Cart();
        $reference = $this->generateUuid();
        $address = $data['checkout']['address'];
        $carrier = $data['checkout']['carrier'];
        $informations = $data['checkout']['informations'];

        $cart->setReference($reference)
            ->setCarrierName($carrier->getName())
            ->setCarrierPrice($carrier->getPrice() / 100)
            ->setFullName($carrier->getFullName())
            ->setDeliveryAddress($address)
            ->setMoreInformations($informations)
            ->setQuantity($data['data']['quantity_cart'])
            ->setSubTotalHT($data['data']['subTotalHT'])
            ->setTaxe($data['data']['Taxe'])
            ->setSubTotalTTC(round(($data['data']['subTotalTTC'] + $carrier->getPrice() / 100), 2))
            ->setUser($user)
            ->setCreatedAt(new \DateTime());
        $this->manager->persist($cart);

        $cart_details_array = [];

        foreach ($data['products'] as $products) {
            $cartDetails = new CartDetails();



            $subTotal = $products['quantity'] * $products['produt']->getPrice() / 100;

            $cartDetails->setCarts($cart)
                ->setProductName($products['product']->getName())
                ->setProductPrice($products['product']->getPrice() / 100)
                ->setQuantity($products['quantity'])
                ->setSubTotalHT($subTotal)
                ->setSubTotalTTC($subTotal * 1.2)
                ->setTaxe($subTotal * 0.2);
            $this->manager->persist($cartDetails);
            $cart_details_array[] = $cartDetails;
        }

        $this->manager->flush();

        return $reference;
    }

    public function generateUuid()
    {
        // Initilise le generateur de nombres aléatoires Mersonne Twister
        mt_srand((float)microtime() * 100000);

        //strtoupper : Renvoie une chaine en majuscules
        //uniqid : Génère un identifiant unique
        $charid = strtoupper(md5(uniqid(rand(), true)));

        //Génèrer une chaine d'un octet à partir d'un nombre
        $hyphen = chr(45);

        //substr : Retourne un segment de chaine
        $uuid = ""
            . substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12) . $hyphen;
        return $uuid;
    }
}
