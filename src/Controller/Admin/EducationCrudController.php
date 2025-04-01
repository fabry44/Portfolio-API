<?php

namespace App\Controller\Admin;

use App\Entity\Education;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\DateType;

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
            TextField::new('url')->setLabel('URL')->setRequired(false),
            TextField::new('area')->setLabel('Area')->setRequired(true),
            TextField::new('studyType')->setLabel('Study Type')->setRequired(false),
            DateField::new('startDate')->setLabel('Début')->setRequired(true)->setEmptyData(null),
            DateField::new('endDate')->setLabel('Fin')->setRequired(true)->setEmptyData(null),
            TextField::new('score')->setLabel('Score')->setRequired(false),
            ArrayField::new('courses')->setLabel('Courses')->setRequired(false)
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
            ->setPageTitle('edit', 'Edition education')
            ->setPageTitle('new', 'Ajout d\'une Education')
            ->setPageTitle('detail', 'Détail de l\'éducation');
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setStartDate(NULL);
        parent::persistEntity($entityManager, $entityInstance);
    }
}


