<?php

namespace App\Http\Controllers;

use App\RoadRunnerFileHelper;
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
            $data = $request->all();
            $data['updated_at'] = Carbon::now();
            $data = $this->model->filter($data);

            $this->code = ($this->model->withTrashed()->where('id', $id)->update($data)) ? 200 : 422;
            $this->json = $this->model->where('id', $id)->first();
        }
        return $this->response();
    }
    public function uploadAtColumn(Request $request, $table, $id, $column) {
        if (!$request->hasFile("image")) {
            return $this->error('Blank image file', 422);
        }

        $filename = Carbon::now()->format('h-i-s');
        // $image = $request->file('image')->getPathName(); // file path
        // $target = storage_path('app/assets/uploads/')."$filename.jpg";

        $i = RoadRunnerFileHelper::parse($request->file('image'));
        $isSuccess = $i->move(storage_path("app/assets/uploads/$table"), "$filename.jpg");

        return $this->success(url("/assets/uploads/$table/" . $filename.".jpg"));
    }
}
