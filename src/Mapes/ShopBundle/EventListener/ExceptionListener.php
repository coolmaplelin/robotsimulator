<?PHP

namespace Mapes\ShopBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ExceptionListener
{
	private $container;

	public function __construct($container) {
		$this->container = $container;
	}
	
    public function onKernelException(GetResponseForExceptionEvent $event)
    {

        // We get the exception object from the received event
        $exception = $event->getException();
		//var_dump($exception);die();
		$templating = $this->container->get('templating');
		$exceptionClass = get_class($exception);
		$slug = $_SERVER["REQUEST_URI"];
		$isAdminArea = strpos($slug, '/admin') === 0 ? true : false;
		
        if($exceptionClass == 'Symfony\Component\HttpKernel\Exception\NotFoundHttpException' && $exception->getStatusCode() == 404)
        {
			
			if($isAdminArea) {
				$admin_pool = $this->container->get('sonata.admin.pool');
				
				$response = new Response($templating->render('MapesCMSBundle:Default:admin_error404.html.twig', array(
						'admin_pool' => $admin_pool,
						'exception' => $exception,
						'exception_class' => get_class($exception)
				)));
				
			}else{
				
				$response = new Response($templating->render('MapesShopBundle:Default:error404.html.twig', array(
							'exception' => $exception,
							'exception_class' => get_class($exception)
					)));
				
			}

            $event->setResponse($response);

        }elseif($exceptionClass != 'Symfony\Component\Security\Core\Exception\AccessDeniedException'){
			
			if($isAdminArea) {
				$admin_pool = $this->container->get('sonata.admin.pool');
				
				$response = new Response($templating->render('MapesShopBundle:Default:admin_error500.html.twig', array(
						'admin_pool' => $admin_pool,
						'exception' => $exception,
						'show_message' => $exceptionClass == 'Doctrine\DBAL\DBALException' ? false : true
				)));
				
			}else{
				$response = new Response($templating->render('MapesShopBundle:Default:error500.html.twig', array(
						'exception' => $exception,
						'show_message' => $exceptionClass == 'Doctrine\DBAL\DBALException' ? false : true
				)));
				
			}

            $event->setResponse($response);
		}


    }
}