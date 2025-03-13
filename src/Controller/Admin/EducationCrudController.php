<?php

namespace App\Controller\Admin;

use App\Entity\Education;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EducationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Education::class;
    }

    public function configureFields(string $pageName): iterable
    {
      

        $fields = [
            IdField::new('id')->setLabel('ID')->onlyOnIndex(),
            TextField::new('institution')->setLabel('Institution')->setRequired(true),
            TextField::new('url')->setLabel('URL')->setRequired(true),
            TextField::new('area')->setLabel('Area')->setRequired(true),
            TextField::new('studyType')->setLabel('Study Type')->setRequired(true),
            DateField::new('startDate')->setLabel('Start Date')->setRequired(true),
            DateField::new('endDate')->setLabel('End Date')->setRequired(true),
            TextField::new('score')->setLabel('Score')->setRequired(true),
            TextEditorField::new('courses')->setLabel('Courses')->setRequired(true)
        ];


        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER);
            // ->setPermission(Action::DETAIL, 'ROLE_ADMIN')
            // ->setPermission(Action::NEW, 'ROLE_ADMIN')
            // ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            // ->setPermission(Action::DELETE, 'ROLE_ADMIN');
    }

    public function configureCrud(Crud $crud): Crud
    {
        // Configuration du CRUD
        return $crud
            ->setPageTitle('index', 'Gestion de mes Educations')
            ->setPageTitle('edit', 'Edition de la Education')
            ->setPageTitle('new', 'Ajout d\'une Education')
            ->setPageTitle('detail', 'Détail de la Education');
    }
}


