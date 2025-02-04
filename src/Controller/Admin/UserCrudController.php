<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id')->onlyOnIndex(),
            EmailField::new('email')->setLabel('Email')->setRequired(true),
            TextField::new('name')->setLabel('Nom')->setRequired(true),
            TextField::new('password')->setLabel('Mot de passe')->setRequired(true)->setFormTypeOptions([
                'required' => true,
                'mapped' => true,
            ])->onlyWhenUpdating(),
            TextField::new('passwordConfirmation')->setLabel('Confirmation du mot de passe')->setFormTypeOptions([
                'required' => true,
                'mapped' => false,
            ])->onlyWhenUpdating(),
            TextField::new('phone')->setLabel('Téléphone')->setRequired(true),
            TextEditorField::new('address')->setLabel('Adresse')->setRequired(true),
            TextField::new('linkedin')->setLabel('LinkedIn')->setRequired(false),
            TextField::new('github')->setLabel('GitHub')->setRequired(false),
            TextField::new('status')->setLabel('Statut')->setRequired(false),
            TextEditorField::new('bio')->setLabel('Biographie')->setRequired(false),
        ];

        
        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN');
    }

    public function configureCrud(Crud $crud): Crud
    {
        // Configuration du CRUD
        return $crud
            ->setPageTitle('index', 'Gestion des Utilisateurs')
            ->setPageTitle('edit', 'Modifier mon profil');
    }
}
