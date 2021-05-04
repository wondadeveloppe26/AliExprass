<?php

namespace App\Controller\Account;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account", name="account")
 */

class AccountController extends AbstractController
{
    /**
     * @Route("/", name="account")
     */
    public function index(OrderRepository $repoOrder): Response
    {
        $orders = $repoOrder->findBy(['isPaid' => true, 'user' => $this->getUser()], ['id' => 'DESC']);
        return $this->render('account/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("/order/{id}", name="account")
     */
    public function show(?Order $order): Response
    {
        if (!$order || $order->getUser() !== $this->getUser()) {
            return $this->redirectToRoute("home");
        }
        if (!$order->getUser()) {
            return $this->redirectToRoute("account");

            return $this->render('account/index.html.twig', [
                'order' => $order,
            ]);
        }
    }
}
