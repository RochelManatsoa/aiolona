<?php

namespace App\Controller\Admin;

use App\Entity\Sector;
use App\Entity\Account;
use App\Entity\AIcores;
use App\Entity\AINote;
use App\Entity\Compagny;
use App\Entity\HonoraryPosting;
use App\Entity\Identity;
use App\Entity\Lang;
use App\Entity\PackName;
use App\Entity\Posting;
use App\Entity\TypePosting;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(AIcoresCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Aiolona');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('AI', 'fas fa-robot', AIcores::class);
        yield MenuItem::linkToCrud('Language', 'fas fa-globe', Lang::class);
        yield MenuItem::linkToCrud('AI Note', 'fas fa-note-sticky', AINote::class);
        yield MenuItem::linkToCrud('Secteur', 'fas fa-list', Sector::class);
        yield MenuItem::linkToCrud('Comptes', 'fas fa-id-card', Account::class);
        yield MenuItem::linkToCrud('Packs', 'fas fa-gift', PackName::class);
        yield MenuItem::linkToCrud('Identité', 'fas fa-fingerprint', Identity::class);
        yield MenuItem::linkToCrud('Company', 'fas fa-users', Compagny::class);
        yield MenuItem::linkToCrud('Annonces', 'fas fa-tag', Posting::class);
        yield MenuItem::linkToCrud('Type d\'annonce', 'fas fa-tag', TypePosting::class);
        yield MenuItem::linkToCrud('Honoraire d\'annonce', 'fas fa-tag', HonoraryPosting::class);
    }
}
