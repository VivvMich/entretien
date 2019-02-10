<?php

namespace App\EventListener;

use App\Entity\News;
use App\Entity\Comments;
use App\EventListener\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;


class Commentlistener implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            // le nom de l'event et le nom de la fonction qui sera déclenché
            Events::ADD_COMMENTS => 'onAddComments',
            Events::DELETE_COMMENTS => 'onDeleteComments'
        ];
    }

    public function onAddComments(GenericEvent $event)
    {
        // Après la validation du formulaire on recupère l'objet et on calcul le nombre de commentaires dans le tableau de collection

        $news = $event->getSubject();
        $n= $news->getCommentsNumber();
        $comments_number = $n + 1;

        // On persiste

        $news->setCommentsNumber( $comments_number );
    }

    public function onDeleteComments(GenericEvent $event)
    {
        // Après la validation du formulaire on recupère l'objet et on calcul le nombre de commentaires dans le tableau de collection

        $comment = $event->getSubject();
        $news = $comment->getNews();
        $n= $news->getCommentsNumber();
        $comments_number = $n - 1;

        // On persiste

        $news->setCommentsNumber( $comments_number );
        return $news;
    }


}

?>