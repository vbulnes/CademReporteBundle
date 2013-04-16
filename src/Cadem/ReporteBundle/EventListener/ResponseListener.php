<?php

namespace Cadem\ReporteBundle\EventListener;

//use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
//use Symfony\Component\HttpFoundation\RedirectResponse;
//use Symfony\Component\Routing\Router;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResponseListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
 
		if (HttpKernel::MASTER_REQUEST != $event->getRequestType()) {
			// don't do anything if it's not the master request
			return;
		}
         
        // only do something when the client accepts "text/html" as response format
        if (false === strpos($request->headers->get('Accept'), 'text/html')) {
            return;
        }
        
		$routeName = $request->attributes->get('_route');
		
		if($routeName == "fos_user_security_login"){//EL LOGIN NO SE CACHEA NUNCA
			$response = $event->getResponse();
			$response->headers->addCacheControlDirective('must-revalidate', true);
			$response->headers->addCacheControlDirective('no-store', true);
			$response->headers->addCacheControlDirective('no-cache', true);
		}
    }
}