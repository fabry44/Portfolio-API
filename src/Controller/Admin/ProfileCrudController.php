<?php

namespace App\Controller\Admin;

use App\Entity\Profile;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class ProfileCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Profile::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('user', 'User')
                ->setRequired(true)
                ->setCrudController(UserCrudController::class) // Permet d’accéder au CRUD de l’utilisateur
                ->autocomplete(), // Active l’autocomplétion pour faciliter la sélection
            TextField::new('network', 'Network')->setRequired(true),
            TextField::new('username', 'Username')->setRequired(false),
            TextField::new('url', 'URL')->setRequired(true),
        ];
    }
}
