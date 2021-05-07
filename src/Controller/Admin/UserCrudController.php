<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    /**
     * Отключаем некоторые возможности взаимодействия с сущностью
     *
     * @param Actions $actions (массив действий с сущностью)
     * @return Actions (новый массив действий с сущностью)
     */
    public function configureActions(Actions $actions): Actions
    {
        // Отключаем возможность создания и удаления пользователей
        return $actions
            ->disable(Action::NEW, Action::DELETE);
    }

    /**
     * Отображаем только необходимые поля в админ панели
     *
     * @param string $pageName (название страницы по которой переходим)
     * @return iterable (список элементов для отображения сущности пользователя)
     */
    public function configureFields(string $pageName): iterable
    {
        // Идентификатор пользователя скрываем при добавлении и редактировании
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('email');
        // Идентификатор пользователя скрываем в списке отображения
        yield ArrayField::new('roles')->hideOnIndex();
        // Идентификатор пользователя скрываем при добавлении, редактировании и списке отображения
        yield TextField::new('password')->hideOnIndex()->hideOnForm();
        yield TextField::new('lastName');
        yield TextField::new('firstName');
        yield TextField::new('patronymic');
    }
}
