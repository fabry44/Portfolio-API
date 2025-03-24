<?php

namespace App\Controller\Admin;

use App\Entity\ContactRequest;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ContactRequestCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ContactRequest::class;
    }

    public function configureFields(string $pageName): iterable
    {
      

        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('firstName')
                ->setLabel('Prénom'),                
            TextField::new('lastName')
                ->setLabel('Nom'),
            TextField::new('email')
                ->setLabel('Email'),

            TextField::new('phone')
                ->setLabel('Phone'),
            TextareaField::new('message')
                ->setLabel('Message'),
            DateTimeField::new('createdAt')
                ->setLabel('Date de création')
                ->hideOnForm()
                ->setFormat('dd/MM/yyyy HH:mm:ss')
                ->setTimezone('Europe/Paris'),
        ];


        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->setPermission(Action::DETAIL, 'ROLE_ADMIN')
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN');
    }

    public function configureCrud(Crud $crud): Crud
    {
        // Configuration du CRUD
        return $crud
            ->setPageTitle('index', 'Gestion des contacts')
            ->setPageTitle('edit', 'Edition du contact')
            ->setPageTitle('detail', 'Détail du contact');
    }
}
