<?php

namespace App\Controller\Admin;

use App\Entity\Ruta;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;

class RutaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ruta::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Informacion'),
            TextField::new('nombre'),
            TextEditorField::new('descripcion'),
            TextField::new('puntoInicio'),
            
            FormField::addTab('Archivos'),
            ImageField::new('foto')
                ->setBasePath('fotos_ruta/')
                ->setUploadDir('public/fotos_ruta/')
                ->setUploadedFileNamePattern('[uuid].[extension]')
                ->setRequired($pageName !== Crud::PAGE_EDIT),
            DateTimeField::new('fechaInicio')
                ->renderAsChoice(),
            DateTimeField::new('fechaFin')
                ->renderAsChoice(),
            NumberField::new('tamanoMaximo'),

            FormField::addTab('Items Visitables'),
            AssociationField::new('visitas'),
        ];
    }

    public function configureActions(Actions $actions): Actions
{
    return $actions
        ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
            return $action->linkToRoute('app_crear_ruta', []);
        });

        // ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action, $id) {
        //     // Asegúrate de que $id esté disponible aquí
        //     return $action->linkToRoute('ruta-editar_ruta/$id', ["id" => $id]);
        // });
}

}
