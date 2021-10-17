<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Response code.
     *
     * @var int $code
     */
    protected $code;

    /**
     * Response json.
     *
     * @var object $json
     */
    protected $json;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->code = 200;
        $this->json = (object)[];
    }

    /**
     * Returning success response with 2xx http status.
     * 
     * @return JsonResponse
     */
    protected function success($content, int $code = 200)
    {
        $this->code = $code;
        $this->json = [
            'type' => 'SUCCESS',
            'content' => $content
        ];
        return $this->response();
    }

    /**
     * Returning error response with 4xx http status.
     * 
     * @return JsonResponse
     */
    protected function error($content, int $code = 400)
    {
        $this->code = $code;
        $this->json = [
            'type' => 'ERROR',
            'content' => $content
        ];
        return $this->response();
    }

    /**
     * Returning response with predefined http code and body content.
     *
     * @return JsonResponse
     */
    protected function response()
    {
        return response()->json($this->json, $this->code);
    }
}
