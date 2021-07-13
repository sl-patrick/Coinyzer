<?php

namespace App\Controller\Admin;

use App\Entity\Cryptocurrencies;
use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/cnz_admin", name="app_admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Coinyzer');
    }
    
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Coinyzer', 'fa fa-home', 'app_home');

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        yield MenuItem::linkToCrud('Cryptocurrencies', 'fab fa-bitcoin', Cryptocurrencies::class);
        yield MenuItem::linkToCrud('Users', 'fa fa-users', Users::class);
        yield MenuItem::section('Contact');
        yield MenuItem::section('Watchlist');

    }
}
