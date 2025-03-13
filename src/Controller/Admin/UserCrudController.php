<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

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
            // TextField::new('password')->setLabel('Mot de passe')->setRequired(true)->setFormTypeOptions([
            //     'mapped' => true,
            // ])->onlyWhenUpdating(),
            // TextField::new('passwordConfirmation')->setLabel('Confirmation du mot de passe')->setFormTypeOptions([
            //     'mapped' => false,
            // ])->onlyWhenUpdating(),
            TextField::new('phone')->setLabel('Téléphone')->setRequired(true),
            TextField::new('website')->setLabel('Site web')->setRequired(true),
            TextEditorField::new('summary')->setLabel('Résumé')->setRequired(true),
            TextField::new('status')->setLabel('Statut')->setRequired(true),
            TextField::new('label')->setLabel('Fonction')->setRequired(true),
            TextField::new('url')->setLabel('URL')->setRequired(true),
            TextField::new('photo')->setLabel('Photo')->setRequired(false),
            TextField::new('photoFile')
                ->setLabel('Uploader une photo')
                ->setFormType(VichImageType::class)
                ->setRequired(true)
                ->onlyOnForms(),
            
            ImageField::new('photo')
                ->setLabel('Photo de profil')
                ->setBasePath('/uploads/users')
                ->hideOnForm(),
            DateField::new('birth')->setLabel('Date de naissance')->setRequired(true),
            AssociationField::new('location')->setLabel('Localisation')->setRequired(true),
            AssociationField::new('profiles')->setLabel('Profils'),
        ];

        
        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE);
            // ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            // ->setPermission(Action::DELETE, 'ROLE_ADMIN');
    }

    public function configureCrud(Crud $crud): Crud
    {
        // Configuration du CRUD
        return $crud
            ->setPageTitle('index', 'Gestion des Utilisateurs')
            ->setPageTitle('edit', 'Modifier mon profil');
    }
}
