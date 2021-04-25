<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

//    public function configureFields(string $pageName): iterable
//    {
//        yield IdField::new('id');
//        yield TextEditorField::new('email');
//        yield ArrayField::new('roles');
//        yield TextField::new('password');
//        yield TextEditorField::new('lastName');
//        yield TextEditorField::new('firstName');
//        yield TextEditorField::new('patronymic');
//    }
}
