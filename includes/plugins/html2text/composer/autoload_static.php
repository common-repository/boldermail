<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit60aaa32692177251ae19ac190a066b9b
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Soundasleep\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Soundasleep\\' => 
        array (
            0 => __DIR__ . '/..' . '/soundasleep/html2text/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit60aaa32692177251ae19ac190a066b9b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit60aaa32692177251ae19ac190a066b9b::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
