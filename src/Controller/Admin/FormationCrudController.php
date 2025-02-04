<?php

namespace App\Controller\Admin;

use App\Entity\Formation;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FormationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Formation::class;
    }

    public function configureFields(string $pageName): iterable
    {
      

        $fields = [
            IdField::new('id')->onlyOnIndex(),
            DateField::new('date')->setLabel('Date')->setRequired(true),
            TextField::new('degree')->setLabel('Nom')->setRequired(true),
            TextField::new('institution')->setLabel('Etablissement')->setRequired(true),
            TextEditorField::new('description')->setLabel('Description')->setRequired(false),
        ];


        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ->setPermission(Action::DETAIL, 'ROLE_ADMIN')
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN');
    }

    public function configureCrud(Crud $crud): Crud
    {
        // Configuration du CRUD
        return $crud
            ->setPageTitle('index', 'Gestion de mes formations')
            ->setPageTitle('edit', 'Edition de la formation')
            ->setPageTitle('new', 'Ajout d\'une formation')
            ->setPageTitle('detail', 'Détail de la formation');
    }
}


