<?php

namespace Engine\FileManager\Action;

use Engine\FileManager\UseCase\File\SetFilePermissions;
use Engine\FileManager\Value\FileId;
use Engine\FileManager\Value\Permissions;
use Engine\Http\Event\HttpExchangeEvent;
use Engine\Rx\Abstraction\Observer;
use Engine\Rx\Service\ObserverStub;

/**
 * Class SetPermissions.
 *
 * Sets file permissions.
 */
class SetPermissions implements Observer
{
    use ObserverStub;

    /**
     * @var SetFilePermissions
     */
    private $setFilePermissions;

    /**
     * SetPermissions constructor.
     *
     * @param SetFilePermissions $setFilePermissions
     */
    public function __construct(SetFilePermissions $setFilePermissions)
    {
        $this->setFilePermissions = $setFilePermissions;
    }

    /**
     * Sets file permissions.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $id = new FileId($event->getRequest()->getBodyParam('id'));
        $permissions = Permissions::createOfOctalString($event->getRequest()->getBodyParam('permissions'));

        $file = $this->setFilePermissions->execute($id, $permissions);

        $event->getResponse()->setBody($file);
    }
}
