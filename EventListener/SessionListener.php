<?php

namespace Fp\SwfuploadBundle\EventListener;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SessionListener implements EventSubscriberInterface
{
    /**
     * @var array
     */
    private $listenedUrls;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @param array $listenedUrls
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(array $listenedUrls, RouterInterface $router)
    {
        $this->listenedUrls = $listenedUrls;
        $this->router = $router;
    }

    /**
     * Some clients can't send the session id through cookies (i.e. flash), so
     * we have to disable session.use_only_cookies for them on specific paths
     */
    public function onEarlyKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();
        $requestUrl = $request->getRequestUri();

        foreach($this->listenedUrls as $url) {
            if ('/' !== $url[0]) {
                $url = $this->router->generate($url);
            }
            
            if (true == preg_match('#' . $url . '#', $requestUrl)) {
                $this->allowNonCookieSid($request);
                break;
            }
        }
    }

    private function allowNonCookieSid($request)
    {
        // allow session id to be in GET/POST
        ini_set('session.use_only_cookies', 0);

        // don't allow session fixation
        ini_set('session.use_cookies', 0);

        // Firewall\ContextListener verifies the existence of a session cookie
        // (via Request::hasPreviousSession), so we have to cheat a little

        $session_name = session_name();
        if (isset($_POST[$session_name])) {
            $request->cookies->set($session_name, $_POST[$session_name]);
        }
    }

    static function getSubscribedEvents()
    {
        return array(KernelEvents::REQUEST => array('onEarlyKernelRequest', 10000));
    }
}

