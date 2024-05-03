<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit12803dc4e5f297b4cc237ec3c463f006
{
    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'PayPal' => 
            array (
                0 => __DIR__ . '/../..' . '/lib',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit12803dc4e5f297b4cc237ec3c463f006::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit12803dc4e5f297b4cc237ec3c463f006::$classMap;

        }, null, ClassLoader::class);
    }
}