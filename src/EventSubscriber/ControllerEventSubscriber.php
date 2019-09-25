<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Description of ControllerEventSubscriber
 *
 * @author rafal
 */
class ControllerEventSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents(): array {
        return [KernelEvents::CONTROLLER => 'convertJsonBodyToArray'];
    }

    public function convertJsonBodyToArray(KernelEvent $event) {
        $request = $event->getRequest();
        
        if ($request->getContentType() !== 'json' || empty($content = $request->getContent())){
            return;
        }
        
        $data = json_decode($content, true);
        
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new BadRequestHttpException("Cannot decode request body as json:". json_last_error_msg());
        }
        
        $request->request->replace($data);
    }

}
