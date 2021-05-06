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
 * @Route("/post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post_index", methods={"GET"})
     */
    public function index(PostRepository $postRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $postRepository->findBy([], ['dateOfCreation' => 'DESC']),
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('post/index.html.twig', [
            'title' => 'Все посты',
            'posts' => $pagination,
        ]);
    }

    /**
     * @Route("/popular", name="post_popular", methods={"GET"})
     */
    public function mostViewed(PostRepository $postRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $postRepository->findBy([], ['countView' => 'DESC']),
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('post/index.html.twig', [
            'title' => 'Самые просматриваемые',
            'posts' => $pagination,
        ]);
    }

    /**
     * @Route("/discussed", name="post_discussed", methods={"GET"})
     */
    public function mostDiscussed(PostRepository $postRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $postRepository->findByCommentCount(),
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('post/index.html.twig', [
            'title' => 'Самые обсуждаемые',
            'posts' => $pagination,
        ]);
    }

    /**
     * @Route("/{id}", name="post_show", methods={"GET", "POST"})
     */
    public function show(Post $post, Security $security, Request $request, PaginatorInterface $paginator): Response
    {
        $user = $security->getUser();

        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentBody = $form->get('commentBody')->getData();
            $comment = new Comment();

            $comment->setOwner($user);
            $comment->setBody($commentBody);
            $comment->setPost($post);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
        }

        if ($post->getOwner() !== $user) {
            $post->incrementCountView();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
        }

        $pagination = $paginator->paginate(
            $post->getComments(),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'comments' => $pagination,
            'comment_form' => $form->createView(),
        ]);
    }
}
