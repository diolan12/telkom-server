<?php

use Spiral\RoadRunner;
use Nyholm\Psr7;
use Nyholm\Psr7\Response;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;

include "vendor/autoload.php";


$app = require __DIR__.'/bootstrap/app.php';

$worker = RoadRunner\Worker::create();
$psrFactory = new Psr7\Factory\Psr17Factory();

$psr7 = new RoadRunner\Http\PSR7Worker($worker, $psrFactory, $psrFactory, $psrFactory);
$psrHttpFactory = new PsrHttpFactory($psrFactory, $psrFactory, $psrFactory, $psrFactory);

$httpFoundationFactory = new HttpFoundationFactory();

while (true) {
    try {
        $request = $psr7->waitRequest();

        if (!($request instanceof \Psr\Http\Message\ServerRequestInterface)) { // Termination request received
            break;
        }
    } catch (\Throwable $e) {
        $psr7->respond(new Response(400)); // Bad Request
        continue;
    }

    try {
        // Application code logic

        // comverting psr7 request to symfony request
        $symfonyRequest = $httpFoundationFactory->createRequest($request);

        // running symfony request to lumen framework returning symfony response
        $symfonyResponse = $app->handle($symfonyRequest);

        // converting symfony response to psr7 response
        $psr7Response = $psrHttpFactory->createResponse($symfonyResponse);

        // responding to the roadrunner with psr7 response
        $psr7->respond($psr7Response);
        // $psr7->respond(new Response(200, [], 'Hello RoadRunner!'));
    } catch (\Throwable $e) {
        $psr7->respond(new Response(500, [], $e));
    }
}