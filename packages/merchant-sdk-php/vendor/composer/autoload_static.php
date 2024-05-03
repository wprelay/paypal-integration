<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit70124b33682dcd2d4b34b595ef4cc43e
{
    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'PayPal\\Service' => 
            array (
                0 => __DIR__ . '/../..' . '/lib',
            ),
            'PayPal\\PayPalAPI' => 
            array (
                0 => __DIR__ . '/../..' . '/lib',
            ),
            'PayPal\\EnhancedDataTypes' => 
            array (
                0 => __DIR__ . '/../..' . '/lib',
            ),
            'PayPal\\EBLBaseComponents' => 
            array (
                0 => __DIR__ . '/../..' . '/lib',
            ),
            'PayPal\\CoreComponentTypes' => 
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
            $loader->prefixesPsr0 = ComposerStaticInit70124b33682dcd2d4b34b595ef4cc43e::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit70124b33682dcd2d4b34b595ef4cc43e::$classMap;

        }, null, ClassLoader::class);
    }
}
