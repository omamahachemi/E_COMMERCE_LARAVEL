<?php
namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Les middlewares globaux de l'application.
     *
     * @var array
     */
    protected $middleware = [
        // Middlewares globaux
    ];

    /**
     * Les groupes de middlewares de l'application.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            // Middlewares pour les routes web
        ],

        'api' => [
            // Middlewares pour les routes API
        ],
    ];

    /**
     * Les middlewares de route.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'isAdmin' => \App\Http\Middleware\IsAdmin::class, // Ajoutez votre middleware ici
    ];
}
