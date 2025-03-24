<?php

namespace App\Controller\Admin;

use App\Entity\Location;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LocationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Location::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            // Champ relationnel avec User
            AssociationField::new('user', 'User')
                ->setRequired(true)
                ->autocomplete(), // Permet une recherche rapide dans EasyAdmin

            // Champs de l'adresse
            TextField::new('address', 'Address')->setRequired(true), // Texte multi-ligne
            TextField::new('postalCode', 'Postal Code')->setRequired(true),
            TextField::new('city', 'City')->setRequired(true),
            TextField::new('countryCode', 'Country Code')->setRequired(true),
            TextField::new('region', 'Region')->setRequired(true),
        ];
    }
}
