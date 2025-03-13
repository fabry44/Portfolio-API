<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Entity\ProjectPhoto;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProjectPhotoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProjectPhoto::class;
    }

    
    public function configureFields(string $pageName): iterable
    {   
        yield IdField::new('id')->onlyOnIndex();

        // Sélection d'un projet dans la liste déroulante (affiche le nom du projet)
        yield AssociationField::new('project')
            ->setLabel('Projet associé')
            ->setRequired(true)
            ->setFormTypeOptions([
                'class' => Project::class,
                'choice_label' => 'name', // Affiche le nom du projet
            ]);

        yield TextField::new('imageFile')
            ->setLabel('Uploader une photo')
            ->setFormType(VichImageType::class)
            ->onlyOnForms();
        
        yield ImageField::new('imageName')
            ->setLabel('Photo de projet')
            ->setBasePath('/uploads/projects')
            ->onlyOnIndex();
    }    
}
