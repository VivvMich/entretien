<?php

namespace App\Controller;

use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use App\Entity\Comments;
use App\Form\CommentsType;
use App\Repository\CommentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use App\EventListener\Events;

/**
 * @Route("/news")
 */
class NewsController extends AbstractController
{
    /**
     * @Route("/", name="news_index", methods={"GET"})
     */
    public function index(NewsRepository $newsRepository): Response
    {
        return $this->render('news/index.html.twig', [
            'news' => $newsRepository->findAll(),
        ]);
    }


    /**
     * @Route("/new", name="news_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $news = new News();
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $news->setUser($user);
            $entityManager->persist($news);
            $entityManager->flush();

            return $this->redirectToRoute('news_index');
        }

        return $this->render('news/new.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="news_show", methods={"GET"})
     */
    public function show(News $news, CommentsRepository $commentrepository ): Response
    {
        $newsId = $news->getId();
        return $this->render('news/show.html.twig', [
            'news' => $news,
            'comments' => $commentrepository->findByNews($newsId),
            'user' =>  $user = $this->getUser()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="news_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, News $news): Response
    {
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('news_index', [
                'id' => $news->getId(),
            ]);
        }

        return $this->render('news/edit.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/commentaire", name="news_comments", methods={"GET","POST"})
     */
    public function addComments(Request $request, News $news, EventDispatcherInterface $eventDispatcher, CommentsRepository $commentrepository ): Response
    {
        $comment = new Comments();
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);
        $user = $this->getUser();
        $newsId = $news->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setNews($news);
            $comment->setUser($user);

            $event = new GenericEvent($news);
            $eventDispatcher->dispatch(Events::ADD_COMMENTS, $event);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->persist($news);
            $entityManager->flush();


            //return $this->redirectToRoute('news_show', [
            //   'id' => $news->getId(),
            //]);
        }

        return $this->render('news/comment_new.html.twig', [
            'news' => $news,
            'comments' => $commentrepository->findByNews($newsId),
            'form' => $form->createView(),
            'user' =>  $user = $this->getUser()
        ]);
    }

    /**
     * @Route("/{id}", name="news_delete", methods={"DELETE"})
     */
    public function delete(Request $request, News $news): Response
    {
        if ($this->isCsrfTokenValid('delete'.$news->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($news);
            $entityManager->flush();

        }

        return $this->redirectToRoute('news_index');
    }

    /**
     * @Route("/Commentaire/{id}", name="comments_delete", methods={"DELETE"})
     */
    public function commentDelete(Request $request, Comments $comment, EventDispatcherInterface $eventDispatcher): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {

            $event = new GenericEvent($comment);
            $eventDispatcher->dispatch(Events::DELETE_COMMENTS, $event);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('news_index');
    }


}
