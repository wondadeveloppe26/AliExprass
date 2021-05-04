<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Services\CartServices;
use App\Services\OrderServices;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\checkout\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class StripeStripeCheckoutSessionController extends AbstractController
{
    /**
     * @Route("/create-checkout-session/{reference}", name="create_checkout_session")
     */
    public function index(
        ?Cart $cart,
        OrderServices $orderServices,
        EntityManagerInterface $manager
    ): Response {
        $user = $this->getUser();
        if (!$cart) {
            return $this->redirectToRoute('home');
        }
        $order = $orderServices->createOrder($cart);
        Stripe::setApiKey('sk_test_51IhbpmB4AmH4KDpi48RBggglU6EoVP5OwIZzFKLBPgr2WM2drc2NfKRjPvgcBrW7UBL9XMJxrLB4OxCcXTsOH2JT00jBnhd1f2');


        $checkout_session = Session::create([
            'customer_email' => $user->getEmail(),

            'payment_method_types' => ['card'],
            'line_items' => $orderServices->getLineItems($cart),
            'mode' => 'payment',
            'success_url' => $_ENV['YOUR_DOMAIN'] . '/stripe_payment_sucess/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $_ENV['YOUR_DOMAIN'] . '/stripe_payment_cancel/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setStripeCheckoutSessionId($checkout_session->id);
        $manager->flush();


        return $this->json(['id' => $checkout_session->id]);
    }
}
