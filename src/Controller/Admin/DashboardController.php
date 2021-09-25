<?php

namespace App\Controller\Admin;

use App\Entity\Cryptocurrencies;
use App\Form\ChangePasswordFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    private $requestStack;
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, RequestStack $requestStack) {
        $this->requestStack = $requestStack;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/profile", name="app_admin")
     */
    public function index(): Response
    {
        // return parent::index();
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($this->requestStack->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $encodedPassword = $this->passwordEncoder->encodePassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPassword($encodedPassword);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('easyadmin/admin.html.twig', [
            'modifyForm' => $form->createView(),
        ]);

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Coinyzer');
            // ->renderSidebarMinimized();
    }
    
    public function configureMenuItems(): iterable
    {        
        yield MenuItem::linkToCrud('Cryptomonnaies', 'fab fa-bitcoin fa-3x', Cryptocurrencies::class)->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToRoute('Retourner sur le site', 'fas fa-reply', 'app_home');    
    }
    
}
