<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * Обработчик для страницы регистрации
     *
     * @Route("/register", name="app_register")
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        UserAuthenticator $authenticator
    ) {
        // Если пользователь уже авторизован, то редиректим на главную страницу
        // Тем самым, ограничиваем доступ к странице регистрации
        if (null !== $this->getUser()) {
            return new RedirectResponse($this->generateUrl('app'));
        }
        // Если пользователь не авторизован, то создаем новый экземпляр сущности
        $user = new User();
        // Создаем форму для регистрации нового пользователя и передаем в него экземпляр класса
        // в который будем помещать данные
        $form = $this->createForm(RegistrationFormType::class, $user);
        // Ожидаем завершение заполнения формы
        $form->handleRequest($request);

        // Когда была нажата кнопка "Зарегистрироваться" мы переходим сюда
        // Проверяем, что данные на форме заполнены и правильные
        if ($form->isSubmitted() && $form->isValid()) {
            // Устанавливаем для пользователя новый пароль
            // Получаем данные из формы и хэшируем пароль
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Вызываем доктрину (ORM)
            $entityManager = $this->getDoctrine()->getManager();
            // Добавляем в сессию БД полученного пользователя
            $entityManager->persist($user);
            // Подтверждаем изменения записывая данные в БД
            $entityManager->flush();
            // После успешного добавления пользователя в БД, автоматически авторизуем пользователя и
            // перенаправляем на главную страницу
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main'
            );
        }

        // В случае, если что-то не так с данными на форме или они некорректные, то мы возвращаемся
        // на страницу регистрации с ранее указанными данными
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
