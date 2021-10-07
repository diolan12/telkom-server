<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestReadController extends RestController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
    }

    private function parseParameters(Request $request, $get = '*')
    {
        // $relation = explode('-', $request->get("with"));
        // $relation = ($relation[0] == "") ? [] : $relation;

        // = is, <, >, <is, >is, <>, != !is, LIKE, NOT, BETWEEN,
        $where = explode(';', $request->get('where'));
        $where = ($where[0] == "") ? [] : $where;
        if (count($where) != 0) {
            foreach ($where as $index => $condition) {
                $con = explode('-', $condition);
                $where[$index] = [
                    $con[0],
                    str_replace('is', '=', $con[1]), // replace 'is' with '='
                    $con[2]
                ];
            }
        }
        // WHERE 'field' LIKE 'a%'	    Finds any values that start with "a"
        // WHERE 'field' LIKE '%a'	    Finds any values that end with "a"
        // WHERE 'field' LIKE '%or%'	Finds any values that have "or" in any position
        // WHERE 'field' LIKE '_r%'	    Finds any values that have "r" in the second position
        // WHERE 'field' LIKE 'a_%'	    Finds any values that start with "a" and are at least 2 characters in length
        // WHERE 'field' LIKE 'a__%'	Finds any values that start with "a" and are at least 3 characters in length
        // WHERE 'field' LIKE 'a%o'	    Finds any values that start with "a" and ends with "o"

        $liffset = explode('-', $request->get('limitOffset'));
        $liffset = ($liffset[0] == "") ? [null, null] : $liffset;
        $liffset = (count($liffset) == 1) ? [null, null] : $liffset;
        $liffset = ($liffset[1] == "") ? null : $liffset;

        $orderBy = explode('-', $request->get('orderBy'));
        $orderBy = ($orderBy[0] == "") ? ['id', 'ASC'] : [strtolower($orderBy[0]), strtoupper($orderBy[1])];

        if ($request->has('limitOffset') && $liffset != null) {
            return $this->model->withTrashed()
                // ->with($relation)
                ->where($where)
                ->take($liffset[0])
                ->skip($liffset[1])
                ->orderBy($orderBy[0], $orderBy[1])
                ->get($get);
        } else {
            return $this->model->withTrashed()
                // ->with($relation)
                ->where($where)
                ->orderBy($orderBy[0], $orderBy[1])
                ->get($get);
        }
    }

    public function index(Request $request, $table)
    {
        parent::__construct($request, $table);

        if ($this->model != null) {
            $this->response = $this->parseParameters($request);
        } 
        return $this->respond();
    }

    public function indexAt(Request $request, $table, $id)
    {
        parent::__construct($request, $table);
        
        if ($this->model != null) {
            // $relation = explode('-', $request->get("with"));
            // $relation = ($relation[0] == "") ? [] : $relation;
            $this->response = $this->model->withTrashed()
                // ->with($relation)
                ->where('id', $id)
                ->first();
        }
        return $this->respond();
    }

    public function indexAtColumn(Request $request, $table, $id, $column)
    {
        parent::__construct($request, $table);
        
        if ($this->model != null) {
            // $relation = explode('-', $request->get("with"));
            // $relation = ($relation[0] == "") ? [] : $relation;
            $this->response = $this->model->withTrashed()
                // ->with($relation)
                ->where('id', $id)
                ->first($column);
        }
        return $this->respond();
    }

    public function indexWhere(Request $request, $table, $column, $value) {
        parent::__construct($request, $table);
        if ($this->model != null) {
            // $relation = explode('-', $request->get("with"));
            // $relation = ($relation[0] == "") ? [] : $relation;
            $this->response = $this->model->withTrashed()
                // ->with($relation)
                ->where($column, $value)
                ->get();
        }
        return $this->respond();
    }
}
