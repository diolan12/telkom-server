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
            $data['updated_at'] = Carbon::now('UTC');
            $data = $this->model->filter($data);

            $this->code = ($this->model->withTrashed()->where('id', $id)->update($data)) ? 200 : 422;
            $this->json = $this->model->where('id', $id)->first();
        }
        return $this->response();
    }
    
    public function uploadAtColumn(Request $request, $table, $id, $column) {
        parent::__construct($request, $table);
        if ($this->model != null) {
            if (!$request->hasFile("file")) {
                return $this->error('Blank file', 422);
            }
            $file = $request->file('file');

            $fn = explode('.', $file->getClientOriginalName()); // file path
            $format = $fn[(count($fn) - 1)];

            $m = $this->model->where('id', $id)->first();

            if ($m->$column != null) {
                try {
                    unlink(storage_path("app/assets/uploads/$table").'/'.$m->$column);
                } catch (\Exception $e) {
                    // return $this->error('Failed to delete old image', 422);
                }
            }
            
            if($request->has('name')) {
                $filename = $request->get('name');
            } else {
                $filename = Carbon::now()->format('Y-m-d_H-i-s');
            }
            $filename = str_replace(" ", "_",$filename) . '.' . $format;

            $m->$column = $filename;
            
            if($request->has('timestamp')) {
                $timestamp = $request->get('timestamp');
                $m->$timestamp = Carbon::now('UTC');
            }
    
            // $target = storage_path('app/assets/uploads/')."$filename.jpg";
    
            $fh = RoadRunnerFileHelper::parse($file);
            $isSuccess = $fh->move(storage_path("app/assets/uploads/$table"), "$filename");

            if ($isSuccess) {
                $m->save();
            }
    
            return $this->success($m);
        }
        return $this->response();
    }
}
