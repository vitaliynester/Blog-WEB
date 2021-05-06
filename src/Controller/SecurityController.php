<?php

namespace App\Controller;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * Обработчик для страницы авторизации
     *
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Если пользователь уже авторизован, то редиректим на главную страницу
        // Тем самым, ограничиваем доступ к странице авторизации
        if (null !== $this->getUser()) {
            return new RedirectResponse($this->generateUrl('app'));
        }

        // Получаем последнюю ошибку, если она имеется
        $error = $authenticationUtils->getLastAuthenticationError();
        // Получаем фамилию пользователя указанную при авторизации
        $lastUsername = $authenticationUtils->getLastUsername();

        // Вызываем шаблон авторизации и передаем в него последнюю ошибку с фамилией пользователя
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Обработчик для страницы выхода с аккаунта
     *
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new LogicException('This method can be blank.');
    }
}
