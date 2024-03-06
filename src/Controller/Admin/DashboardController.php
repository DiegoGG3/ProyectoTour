<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Ruta;
use App\Entity\Provincia;
use App\Entity\Localidad;
use App\Entity\Visita;
use App\Entity\Tour;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Free Tour');
    }

    public function configureAssets(): Assets{
        return Assets::new()
            ->addCssFile('css/easyAdmin.css');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Inicio', 'fa fa-home');
        yield MenuItem::section('CRUD');
        yield MenuItem::linkToCrud('Usuarios', 'fa-solid fa-person', User::class);

        yield MenuItem::section('VIAJES');

        yield MenuItem::linkToCrud('Ruta', 'fa-solid fa-route', Ruta::class);
        yield MenuItem::linkToCrud('Visita', 'fa-solid fa-map-pin', Visita::class);
        yield MenuItem::linkToCrud('Tour', 'fa-solid fa-neuter', Tour::class);

        yield MenuItem::section('LUGARES');

        yield MenuItem::linkToCrud('Provincia', 'fa-solid fa-globe', Provincia::class);
        yield MenuItem::linkToCrud('Localidad', 'fa-solid fa-city', Localidad::class);
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
