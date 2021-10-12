<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class RestUpdateController extends RestController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
    }
    public function update(Request $request, $table, $id)
    {
        parent::__construct($request, $table);
        if ($this->model != null) {
            $data = $this->validate($request, $this->model->validation());
            $data['updated_at'] = Carbon::now();
            $data = $this->model->filter($data);

            $this->code = ($this->model->withTrashed()->where('id', $id)->update($data)) ? 200 : 422;
            $this->response = $this->model->where('id', $id)->first();
        }
        return $this->respond();
    }
}
