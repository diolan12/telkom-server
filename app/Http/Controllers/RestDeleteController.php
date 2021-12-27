<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class RestDeleteController extends RestController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
    }
    public function delete(Request $request, $table, $id)
    {
        parent::__construct($request, $table);
        if ($this->model != null) {
            // $data = $this->validate($request, $this->model->validation());
            $data['deleted_at'] = Carbon::now('UTC');
            // $data = $this->model->filter($data);

            $this->code = ($this->model->where('id', $id)->update($data)) ? 200 : 422;
            $this->json = $this->model->get();
        }
        return $this->response();
    }
}
