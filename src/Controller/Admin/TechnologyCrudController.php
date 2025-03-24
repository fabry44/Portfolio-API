<?php

namespace App\Controller\Admin;

use App\Entity\Technology;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TechnologyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Technology::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();

        yield TextField::new('name')
            ->setLabel('Nom')
            ->setRequired(true);

        yield TextField::new('icon')
            ->setLabel('Icône')
            ->setHelp('Par exemple, le nom d\'une classe d\'icône pour Astro');

        yield TextField::new('class')
            ->setLabel('Classe CSS')
            ->setHelp('Classe Tailwind CSS pour le style de l\'icône. (ex: bg-[#963419] text-white)');

        yield TextField::new('style')
            ->setLabel('Style')
            ->setHelp('Style additionnel pour Backend. (ex: background-color: #963419; color: white;)');

        // En mode affichage (index, detail), vous pouvez afficher les projets associés
        if (in_array($pageName, [Crud::PAGE_INDEX, Crud::PAGE_DETAIL])) {
            yield AssociationField::new('projects')
                ->setLabel('Projets associés');
        }
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Gestion des technologies')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter une technologie')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier une technologie')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Détail de la technologie');
    }
}
