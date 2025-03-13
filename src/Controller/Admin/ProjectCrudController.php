<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('name')->setLabel('Nom du projet')->setRequired(true);
        yield TextEditorField::new('description')->setLabel('Description du projet')->setRequired(true);
        yield TextField::new('link')->setLabel('Lien du site')->setRequired(false);
        yield TextField::new('github')->setLabel('Lien GitHub')->setRequired(false);
        yield DateField::new('date')->setLabel('Date')->setRequired(false);

        yield AssociationField::new('technology')
            ->setLabel('Technologies utilisées')
            ->setFormTypeOptions([
                'by_reference' => false,
                'multiple' => true,
            ])
            ->setRequired(true);

        yield AssociationField::new('photos')
            ->setLabel('Photos associées')
            ->setTemplatePath('admin/fields/project_photos.html.twig')
            ->hideOnForm();

        
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Gestion des projets')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter un projet')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un projet')
            ->setEntityLabelInSingular('Projet')
            ->setEntityLabelInPlural('Projets');
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
