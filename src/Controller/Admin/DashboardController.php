<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\Categories;
use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        //return parent::index();
        $routeBuilder = $this->get(AdminUrlGenerator::class);
        return $this->redirect($routeBuilder->setController(OneOfYourCrudController::class)->generateur());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('AliExprass');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Product', 'fas fa-shopping-cart', Product::class);
        yield MenuItem::linkToCrud('Cart', 'fas fa-truck', Cart::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-truck', Categories::class);
        yield MenuItem::linkToCrud('carrier', 'fas fa-truck', Carrier::class);
    }
}
