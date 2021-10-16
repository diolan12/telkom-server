<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $c;
    protected $b;

    /**
     * Returning success response.
     * 
     * @return JsonResponse
     */
    protected function success($content, int $code = 200)
    {
        $this->c = $code;
        $this->b = [
            'type' => 'SUCCESS',
            'content' => $content
        ];
        return $this->response();
    }

    /**
     * Returning error response.
     * 
     * @return JsonResponse
     */
    protected function error($content, int $code = 400)
    {
        $this->c = $code;
        $this->b = [
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
        return response()->json($this->b, $this->c);
    }
}
