<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestController extends Controller {

    protected $model;
    protected $modelClassName;
    protected $response;
    protected $code;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, $table)
    {
        $this->modelClassName = "";
        $this->response = null;
        $this->code = 200;
        $this->model = $this->init($table);

        if ($this->model == null) {
            $this->response = [
                'type' => 'ERROR',
                'message' => "$this->modelClassName does not exist"
            ];
            $this->code = 400;
        }
    }

    protected function init($table)
    {
        $unprocessed = explode('-', $table);

        // processing kebab-case to PascalCase
        foreach ($unprocessed as $processed) {
            $this->modelClassName = $this->modelClassName . ucwords($processed);
        }

        $model = "App\\Models\\Rest\\" . $this->modelClassName;
        if (class_exists($model)) {
            return new $model();
        } else {
            $this->code = 400;
            return null;
        }
    }

    protected function respond(){
        return response()->json($this->response, $this->code);
    }

}