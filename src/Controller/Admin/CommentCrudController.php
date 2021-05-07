<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    /**
     * Отображаем только необходимые поля в админ панели
     *
     * @param string $pageName (название страницы по которой переходим)
     * @return iterable (список элементов для отображения сущности комментария)
     */
    public function configureFields(string $pageName): iterable
    {
        // Идентификатор комментария скрываем при добавлении и редактировании
        yield IdField::new('id')->hideOnForm();
        yield AssociationField::new('post');
        yield AssociationField::new('owner');
        yield DateTimeField::new('dateOfCreation');
        // Тело комментария скрываем в отображаемом списке
        yield TextField::new('body')->hideOnIndex();
    }
}
