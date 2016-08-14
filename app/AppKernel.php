<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
	public function __construct($environment, $debug)
	{
		date_default_timezone_set('Australia/Canberra');
		parent::__construct($environment, $debug);
	}	
	
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
			

			/*  Sonata Admin Start */
			//new Sonata\CoreBundle\SonataCoreBundle(),
			//new Sonata\BlockBundle\SonataBlockBundle(),
			//new Knp\Bundle\MenuBundle\KnpMenuBundle(),
			// If you haven't already, add the storage bundle
			// This example uses SonataDoctrineORMAdmin but
			// it works the same with the alternatives
			//new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
			// Then add SonataAdminBundle
			//new Sonata\AdminBundle\SonataAdminBundle(),	
			/*  Sonata Admin End */
			
			//Oneup Uploader Bundle
			//new Oneup\UploaderBundle\OneupUploaderBundle(),
			
			//My bundles
            new Mapes\ShopBundle\MapesShopBundle(),
			
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
