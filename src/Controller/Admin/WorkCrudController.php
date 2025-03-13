<?php

namespace App\Controller\Admin;

use App\Entity\Work;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class WorkCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Work::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('company', 'Company')->setRequired(true),
            TextField::new('location', 'Location')->setRequired(false),

            // Utilisation de TextEditorField si HTML autorisé, sinon TextareaField
            
            TextEditorField::new('summary', 'Summary')->setRequired(false),

            TextField::new('position', 'Position')->setRequired(true),
            TextField::new('url', 'URL')->setRequired(false),

            DateField::new('startDate', 'Start Date')->setRequired(false),
            DateField::new('endDate', 'End Date')->setRequired(false),

            // Utilisation de ArrayField pour highlights car c'est un champ JSON
            ArrayField::new('highlights', 'Highlights')->setRequired(false),
        ];
    }
}
