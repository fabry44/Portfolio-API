<?php

namespace App\Controller\Admin;

use App\Entity\Skill;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class SkillCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Skill::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('name', 'Skill Name')
                ->setRequired(true)
                ->setHelp('Nom de la compétence'),

            ChoiceField::new('level', 'Skill Level')
                ->setChoices([
                    'Débutant' => 'beginner',
                    'Intermédiaire' => 'intermediate',
                    'Avancé' => 'advanced',
                    'Expert' => 'expert',
                ])
                ->setRequired(false)
                ->setHelp('Niveau de compétence'),

            ArrayField::new('keywords', 'Keywords')
                ->setHelp('Liste des mots-clés associés'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Gestion des Compétences')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter une Compétence')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier une Compétence')
            ->setEntityLabelInSingular('Compétence')
            ->setEntityLabelInPlural('Compétences');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
