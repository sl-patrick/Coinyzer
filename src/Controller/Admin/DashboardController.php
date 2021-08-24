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
     * @Security("is_granted('ROLE_USER')")
     * @Route("/profile", name="app_admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Coinyzer');
            // ->renderSidebarMinimized();
    }
    
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');
        yield MenuItem::linkToCrud('Cryptomonnaies', 'fab fa-bitcoin fa-3x', Cryptocurrencies::class)->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-users', Users::class)->setPermission('ROLE_ADMIN');
        yield MenuItem::linktoRoute('Favoris', 'fas fa-star', 'app_favoris');
        yield MenuItem::linkToRoute('Retourner sur le site', 'fas fa-reply', 'app_home');

    }
    
}
