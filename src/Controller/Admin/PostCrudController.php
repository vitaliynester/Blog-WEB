<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    /**
     * Отображаем только необходимые поля в админ панели
     *
     * @param string $pageName (название страницы по которой переходим)
     * @return iterable (список элементов для отображения сущности поста)
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            // Идентификатор поста скрываем при добавлении и редактировании
            IntegerField::new('id')->hideOnForm(),
            IntegerField::new('timeOnRead'),
            TextField::new('title'),
            // Тело поста скрываем в отображаемом списке
            TextEditorField::new('body')->hideOnIndex(),
            IntegerField::new('countView'),
            DateTimeField::new('dateOfCreation'),
        ];
    }
}
