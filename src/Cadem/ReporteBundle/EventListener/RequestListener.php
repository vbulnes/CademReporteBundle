<?php

namespace Cadem\ReporteBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\SecurityContext;

class RequestListener
{
    protected $router;
	protected $security;
 
	public function __construct(Router $router, SecurityContext $security) {
		$this->router = $router;
		$this->security = $security;
	}
        
    public function onKernelRequest(GetResponseEvent $event)
    {
        
		if (HttpKernel::MASTER_REQUEST != $event->getRequestType()) {
				// don't do anything if it's not the master request
				return;
		}

		$request = $event->getRequest();
		$routeName = $request->attributes->get('_route');
		if($routeName == "fos_user_security_login" && $this->security->isGranted('ROLE_USER')){
			$url = $this->router->generate('dashboard_index');
			$event->setResponse(new RedirectResponse($url));
		}
    }
}