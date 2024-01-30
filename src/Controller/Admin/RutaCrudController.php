<?php

namespace App\Controller\Admin;

use App\Entity\Ruta;
use DateTime as GlobalDateTime;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;





class RutaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ruta::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nombre'),
            TextEditorField::new('descripcion'),
            TextField::new('puntoInicio'),
            NumberField::new('tamanoMaximo'),
            DateTimeField::new('fechaInicio')
                ->renderAsChoice(),
            DateTimeField::new('fechaFin')
                ->renderAsChoice(),
            ArrayField::new('programacion'),
            ImageField::new('foto')
                ->setBasePath('fotos_ruta/')
                ->setUploadDir('public/fotos_ruta/')
                ->setUploadedFileNamePattern('[uuid].[extension]'),
            AssociationField::new('visitas')
 
        ];
    }
    
}

//CAMBIAR NOMBRE FOTOS AL EDITAR O CREAR
//FOTO PERFIL