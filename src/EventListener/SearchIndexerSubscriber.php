<?php

namespace Algolia\SearchableBundle\EventListener;


use Algolia\SearchableBundle\IndexManager;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class SearchIndexerSubscriber implements EventSubscriber
{
    protected $indexManager;

    protected $subscribedEvents;

    public function __construct(IndexManager $indexManager, $subscribedEvents)
    {
        $this->indexManager = $indexManager;
        $this->subscribedEvents = $subscribedEvents;
    }

    public function getSubscribedEvents()
    {
        return $this->subscribedEvents;
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    public function index(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        $objectManager = $args->getObjectManager();

        $this->indexManager->index($object, $objectManager);
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        $this->indexManager->remove($object, $args->getObjectManager());
    }
}