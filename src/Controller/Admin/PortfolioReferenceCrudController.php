<?php

namespace App\Controller\Admin;

use App\Entity\PortfolioReference;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class PortfolioReferenceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PortfolioReference::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('name')->setLabel('Nom de la référence')->setRequired(true);
        yield TextField::new('ref')->setLabel('Lien / Référence')->setRequired(true);
        yield TextareaField::new('ref')->setLabel('Détail de la référence')->setRequired(true);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Gestion des Références du Portfolio')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter une Référence')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier une Référence')
            ->setEntityLabelInSingular('Référence')
            ->setEntityLabelInPlural('Références');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
            // ->setPermission(Action::NEW, 'ROLE_ADMIN')
            // ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            // ->setPermission(Action::DELETE, 'ROLE_ADMIN');
    }
}
