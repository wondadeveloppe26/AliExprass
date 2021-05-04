<?php

namespace App\Controller;

use App\Entity\Order;
use App\Services\CartServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeStripeSucessPaymentController extends AbstractController
{
    /**
     * @Route("/stripe-sucess-payment/{StripeCheckoutSessionId}", name="stripe_payment_sucess")
     */
    public function index(
        ?Order $order,
        CartServices $cartServices,
        EntityManagerInterface $manager
    ): Response {
        if (!$order || $order->getUser() !== $this->getUser()) {
            return $this->redirectToRoute("home");
        }

        if (!$order->getIsPaid()) {
            // order Ispaid
            $order->setIsPaid(true);
            $manager->flush();
            $cartServices->deleteCart();
            //the mail to custumer
        }
        return $this->render('stripe_stripe_sucess_payment/index.html.twig', [
            'controller_name' => 'StripeStripeSucessPaymentController',
            'order' => $order
        ]);
    }
}
