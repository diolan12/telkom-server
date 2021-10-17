<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestController extends Controller {

    /**
     * The model class name.
     *
     * @var string $modelClassName
     */
    protected $modelClassName;

    /**
     * The model instance.
     *
     * @var \App\Models\BaseModel $model
     */
    protected $model;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, $table)
    {
        parent::__construct();
        $this->modelClassName = "";
        $this->model = $this->init($table);

        if ($this->model == null) {
            $this->error("$this->modelClassName does not exist");
        }
    }

    /**
     * Initialize a new model instance.
     *
     * @return \App\Models\BaseModel|null
     */
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
            return null;
        }
    }
}