<?php

namespace App\Controller\Admin;

use App\Entity\Visita;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class VisitaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Visita::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('localidad'),
            TextField::new('nombre'),
            TextEditorField::new('descripcion'),

            ImageField::new('foto')
                ->setBasePath('fotos_visita/')
                ->setUploadDir('public/fotos_visita/')
                ->setUploadedFileNamePattern('[uuid].[extension]')
                ->setRequired($pageName !== Crud::PAGE_EDIT),
                

            TextField::new('coordenadas'),

        ];
    }
    
}
