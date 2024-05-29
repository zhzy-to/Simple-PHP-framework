<?php

namespace App\Tools;

use Twig\Environment;

class Template
{
    private static $template;

    /**
     * @return Environment
     */
    public static function getTemplate()
    {
        if (null === self::$template) {
            $loader = new \Twig\Loader\FilesystemLoader(BASE_PATH . '/resources/views');
            $twig = new Environment($loader, [
                'cache' => BASE_PATH . '/storage/template/cache',
                'debug' => true,
            ]);

            self::$template = $twig;
        }

        return self::$template;
    }
}