<?php

declare(strict_types=1);

namespace OpenApiGenerator\Tests\Examples\Controller;

use OpenApiGenerator\Attributes\Controller;
use OpenApiGenerator\Attributes\GET;
use OpenApiGenerator\Attributes\Info;
use OpenApiGenerator\Attributes\Parameter;
use OpenApiGenerator\Attributes\PathParameter;
use OpenApiGenerator\Attributes\Property;
use OpenApiGenerator\Attributes\Response;
use OpenApiGenerator\Attributes\Security;
use OpenApiGenerator\Attributes\SecurityScheme;
use OpenApiGenerator\Attributes\Server;
use OpenApiGenerator\Type;

#[
    Server('same server1', 'same url1'),
    Info("title", "1.0.0", "The description"),
    Server('same server2', 'same url2'),
    Security(['http']),
    SecurityScheme(type: 'http', scheme: 'Bearer', bearerFormat: 'JWT bearer token'),
    Controller,
]
class SimpleController
{
    #[
        GET("/path/{id}/{otherParameter}", ["Dummy"], "Dummy path"),
        PathParameter("otherParameter", description: "Parameter which is not used as an argument in this method"),
        Property(Type::STRING, "prop1"),
        Response(200),
    ]
    public function get(
        #[Parameter(example: "2")] float $id
    ): void {
        //
    }
}
