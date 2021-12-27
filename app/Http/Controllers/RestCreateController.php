<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class RestCreateController extends RestController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
    }
    
    public function insert(Request $request, $table)
    {
        parent::__construct($request, $table);
        if ($this->model != null) {
            $data = $this->validate($request, $this->model->validation());
            $data['created_at'] = Carbon::now('UTC');
            $data['updated_at'] = Carbon::now('UTC');
            $data = $this->model->filter($data);

            $this->code = (!($id = $this->model->insertGetId($data))) ? 422 : 201;
            $this->json = $this->model->where('id', $id)->first();
        }
        return $this->response();
    }
}
