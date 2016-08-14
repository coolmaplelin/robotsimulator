<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';
$loader->add('sfThumbnailPlugin_', __DIR__.'/../vendor/sfThumbnailPlugin/lib');
AnnotationRegistry::registerLoader([$loader, 'loadClass']);

return $loader;
