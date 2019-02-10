<?php


namespace App\EventListener;
 
/**
 * This class defines the names of all the events dispatched in
 * our project. It's not mandatory to create a
 * class like this, but it's considered a good practice.
 *
 */
final class Events
{
    /**
     * For the event naming conventions, see:
     * https://symfony.com/doc/current/components/event_dispatcher.html#naming-conventions.
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const ADD_COMMENTS = 'add.comments';
    const DELETE_COMMENTS = 'delete.comments';
}

?>