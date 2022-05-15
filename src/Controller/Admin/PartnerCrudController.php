<?php

namespace App\Controller\Admin;

use App\Entity\Partner;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PartnerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Partner::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('partner')->setLabel('Partenaire'),
            SlugField::new('slug')->setTargetFieldName('partner'),
            TextEditorField::new('description'),
            ImageField::new('logo')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads/')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(true)
        ];
    }
}
