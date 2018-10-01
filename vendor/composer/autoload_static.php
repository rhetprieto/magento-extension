<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdf65791de4184f50883db730236a9c29
{
    public static $files = array (
        'd6ece8d2c981218ce86191ed42973395' => __DIR__ . '/../..' . '/registration.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Skuiq\\SyncModule\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Skuiq\\SyncModule\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdf65791de4184f50883db730236a9c29::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdf65791de4184f50883db730236a9c29::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}