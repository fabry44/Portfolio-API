<?php

namespace App\Controller\Admin;

use App\Entity\Interest;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

class InterestCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Interest::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('name')->setLabel('Nom de l\'intérêt')->setRequired(true);
        yield ArrayField::new('keywords')->setLabel('Mots-clés associés')->setHelp('Liste des mots-clés liés à cet intérêt');
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Gestion des Intérêts')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter un Intérêt')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un Intérêt')
            ->setEntityLabelInSingular('Intérêt')
            ->setEntityLabelInPlural('Intérêts');
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
