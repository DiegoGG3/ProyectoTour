<?php

namespace App\Controller\Admin;

use App\Entity\Tour;
use DateTime;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class TourCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tour::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('codRuta')
                ->setLabel("Nombre Ruta"),
            DateTimeField::new('fechaHora'),
            AssociationField::new('guia')
                ->setLabel("Nombre Guia")
                ->setQueryBuilder(function(QueryBuilder $qb){
                    $qb->andWhere("entity.roles like :role")
                    ->setParameter("role", "%ROLE_GUIA%");
                }),
        ];
    }
    
}
