<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * Обработчик перехода на страницу админ панели
     *
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // После перехода на страницу /admin отображаем CRUD для сущности пользователя
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();
        return $this->redirect($routeBuilder->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Blog');
    }

    /**
     * Настройка списка бокового меню в админке
     *
     * @return iterable (элементы списка бокового меню)
     */
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Пользователи', 'fa fa-users');
        yield MenuItem::linkToCrud('Посты', 'fas fa-folder', Post::class);
        yield MenuItem::linkToCrud('Комментарии', 'fas fa-terminal', Comment::class);
    }
}
