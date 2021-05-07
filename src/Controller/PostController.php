<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Обработчик запросов работы с сущностью "Пост"
 *
 * @Route("/post")
 */
class PostController extends AbstractController
{
    /**
     * Обработчик запросов на получение всех постов по дате выпуска
     *
     * @return Response (перенаправление на сформированный шаблон ответа на запрос)
     *
     * @Route("/", name="post_index", methods={"GET"})
     *
     * @var Request (запрос, в данном случае необходим для получения номера страницы)
     * @var PaginatorInterface (интерфейс для создания пагинации на странице)
     * @var PostRepository (репозитория постов)
     */
    public function index(PostRepository $postRepository, Request $request, PaginatorInterface $paginator): Response
    {
        // Получаем номер страницы, по которой переходит пользователь
        $pageRequest = $request->query->getInt('page', 1);
        // Если номер страницы указан некорректный (должен быть положительным числом)
        if ($pageRequest <= 0) {
            // Устанавливаем в 1, в случае ошибки
            $pageRequest = 1;
        }

        // Составляем разбивку на страницы с сортировкой постов по дате выпуска
        $pagination = $paginator->paginate(
            $postRepository->findBy([], ['dateOfCreation' => 'DESC']),
            $pageRequest,
            6
        );

        // Формируем страницу ответа
        return $this->render('post/index.html.twig', [
            'title' => 'Все посты',
            'posts' => $pagination,
        ]);
    }

    /**
     * Обработчик запросов на получение самых просматриваемых постов
     *
     * @return Response (перенаправление на сформированный шаблон ответа на запрос)
     *
     * @Route("/popular", name="post_popular", methods={"GET"})
     *
     * @var Request (запрос, в данном случае необходим для получения номера страницы)
     * @var PaginatorInterface (интерфейс для создания пагинации на странице)
     * @var PostRepository (репозиторий постов)
     */
    public function mostViewed(PostRepository $postRepository, Request $request, PaginatorInterface $paginator): Response
    {
        // Получаем номер страницы, по которой переходит пользователь
        $pageRequest = $request->query->getInt('page', 1);
        // Если номер страницы указан некорректный (должен быть положительным числом)
        if ($pageRequest <= 0) {
            // Устанавливаем в 1, в случае ошибки
            $pageRequest = 1;
        }

        // Составляем разбивку на страницы с сортировкой постов по количеству просмотров
        $pagination = $paginator->paginate(
            $postRepository->findBy([], ['countView' => 'DESC']),
            $pageRequest,
            6
        );

        // Формируем страницу ответа
        return $this->render('post/index.html.twig', [
            'title' => 'Самые просматриваемые',
            'posts' => $pagination,
        ]);
    }

    /**
     * Обработчик запросов на получение самых комментируемых постов
     *
     * @return Response (перенаправление на сформированный шаблон ответа на запрос)
     *
     * @Route("/discussed", name="post_discussed", methods={"GET"})
     *
     * @var PaginatorInterface (интерфейс для создания пагинации на странице)
     * @var PostRepository (репозиторий постов)
     * @var Request (запрос, в данном случае необходим для получения номера страницы)
     */
    public function mostDiscussed(PostRepository $postRepository, Request $request, PaginatorInterface $paginator): Response
    {
        // Получаем номер страницы, по которой переходит пользователь
        $pageRequest = $request->query->getInt('page', 1);
        // Если номер страницы указан некорректный (должен быть положительным числом)
        if ($pageRequest <= 0) {
            // Устанавливаем в 1, в случае ошибки
            $pageRequest = 1;
        }

        // Составляем разбивку на страницы с сортировкой постов по количеству комментариев
        $pagination = $paginator->paginate(
            $postRepository->findByCommentCount(),
            $pageRequest,
            6
        );

        // Формируем страницу ответа
        return $this->render('post/index.html.twig', [
            'title' => 'Самые обсуждаемые',
            'posts' => $pagination,
        ]);
    }

    /**
     * Обработчик запросов на получение данных об одном посте
     *
     * @return Response (перенаправление на сформированный шаблон ответа на запрос)
     *
     * @Route("/{id}", name="post_show", methods={"GET", "POST"})
     *
     * @var Post (объект о котором нужно получить подробные данные)
     * @var Security (объект безопасности, используется для получения данных об авторизованном пользователе)
     * @var Request (запрос, в данном случае необходим для получения номера страницы)
     * @var PaginatorInterface (интерфейс для создания пагинации на странице)
     */
    public function show(Post $post, Security $security, Request $request, PaginatorInterface $paginator): Response
    {
        // Получаем текущего авторизованного пользователя
        $user = $security->getUser();

        // Создаем форму для добавления комментария к посту
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        // Если форма добавления комментария заполнена правильными данными
        if ($form->isSubmitted() && $form->isValid()) {
            // Получаем текст комментария
            $commentBody = $form->get('commentBody')->getData();
            // Создаем новую сущность комментария
            $comment = new Comment();

            // Устанавливаем владельца комментария пользователя
            $comment->setOwner($user);
            // Устанавливаем комментарию текст
            $comment->setBody($commentBody);
            // Добавляем в пост созданный комментарий
            $post->addComment($comment);

            // Обновленную сущность поста в БД
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            // Перенаправляем на страницу с этим постом для обновления списка комменатриев
            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }

        // Если текущий пользователь не автор поста, то
        if ($post->getOwner() !== $user) {
            // Увеличиваем счетчик просмотров поста на единицу
            $post->incrementCountView();
            // Обновляем сущность поста в БД
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
        }

        // Разбиваем все комментарии поста на страницы
        $pagination = $paginator->paginate(
            $post->getComments(),
            $request->query->getInt('page', 1),
            6
        );

        // Отдаем страницу с подробным постом
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'comments' => $pagination,
            'comment_form' => $form->createView(),
        ]);
    }
}
