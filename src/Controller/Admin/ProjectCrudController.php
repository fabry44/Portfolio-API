<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('title')->setLabel('Nom')->setRequired(true);
        yield TextEditorField::new('description')->setLabel('Description')->setRequired(true);
        yield TextField::new('link')->setLabel('Link')->setRequired(false);

        // Pour les relations ManyToMany, on utilise AssociationField
        if (in_array($pageName, [Crud::PAGE_NEW, Crud::PAGE_EDIT])) {
            yield AssociationField::new('technology')
                ->setLabel('Technologies')
                ->setFormTypeOptions([
                    // Important pour que les éléments soient ajoutés/supprimés correctement
                    'by_reference' => false,
                ]);
        } else {
            // En mode affichage (index, detail), vous pouvez personnaliser l'affichage
            yield AssociationField::new('technology')
                ->setLabel('Technologies')
                ->setTemplatePath('admin/crud/fields/show/technologies/show.html.twig');
        }
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->setPermission(Action::DETAIL, 'ROLE_ADMIN')
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN');
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Gestion de mes projets')
            ->setPageTitle(Crud::PAGE_EDIT, 'Edition du projet')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajout d\'un projet')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Détail du projet');
    }
}
