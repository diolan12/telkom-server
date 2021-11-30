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

    protected $orderByColumn;
    protected $orderByValue;

    private function parseParameters(Request $request, $get = '*')
    {
        // $relation = explode('-', $request->get("with"));
        // $relation = ($relation[0] == "") ? [] : $relation;

        // spliting where parameters with ';'
        // where parameters valid operator
        // [= is], [<], [>], [<= <is], [>= >is], [<>], [!= !is], [LIKE], [NOT], [BETWEEN]
        $where = explode(';', $request->get('where')); // "column-is-value;column-like-value" => [thing-stuff, thing-stuff]
        // if where index 0 is blank, then make the array empty instead of 1 element with blank string
        $where = ($where[0] == "") ? [] : $where; // check if blank = [] length 0
        if (count($where) != 0) { // run when array is not 0
            foreach ($where as $index => $condition) { // [0:thing-stuff, 1:thing-stuff]
                // parse each element of where array split with '.'
                // get column name from the first index, operator from the second index, and value from the third index
                $con = explode('.', $condition);
                // if the where element is not 3, then it is invalid
                if (count($con) > 2 && $con[2] != "") {
                    $where[$index] = [
                        // column name
                        $con[0],
                        // operator
                        str_replace('is', '=', $con[1]), // replace 'is' with '='
                        // value
                        $con[2]
                    ];
                } 
                // unset the original condition
                else unset($where[$index]);
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
        $this->orderByColumn = $orderBy[0];
        if (count($orderBy) == 2) {
            $orderBy = ($orderBy[0] == "") ? ['id', 'ASC'] : [strtolower($orderBy[0]), strtoupper($orderBy[1])];
            $this->orderByValue = $orderBy[1];
        } else $orderBy = ['id', 'ASC'];

        // putting it all together
        if ($request->has('limitOffset') && $liffset != null) {
            // TODO this should be supporting the clean, dirty, and relation params
            return $this->model->withTrashed()
                // ->with($relation)
                ->where($where)
                ->take($liffset[0])
                ->skip($liffset[1])
                ->orderBy($orderBy[0], $orderBy[1])
                ->get($get);
        } else {
            // trash bin not implemented yet
            // accesing trash bin with where condition "?where=deleted_at.!is.null"

            // if request has clean, then get clean model
            if ($request->has('clean')) {
                // if request has 'relation', then get model with their respective relations
                if ($request->has('relation')) {
                    return $this->model
                        ->with($this->model->getRelations())
                        ->where($where)
                        ->orderBy($orderBy[0], $orderBy[1])
                        ->get($get);
                }
                // else get model without the relations
                else {
                    return $this->model
                        ->where($where)
                        ->orderBy($orderBy[0], $orderBy[1])
                        ->get($get);
                }
            }
            // else, get dirty model
            else {
                // if request has 'relation', then get model with their respective relations
                if ($request->has('relation')) {
                    return $this->model->withTrashed()
                        ->with($this->model->getRelations())
                        ->where($where)
                        ->orderBy($orderBy[0], $orderBy[1])
                        ->get($get);
                }
                // else get model without the relations
                else {
                    return $this->model->withTrashed()
                        ->where($where)
                        ->orderBy($orderBy[0], $orderBy[1])
                        ->get($get);
                }
            }
        }
    }

    public function get(Request $request, $table)
    {
        parent::__construct($request, $table);

        if ($this->model != null) {
            try {
                // $this->json = $this->model->where('uid', 'SC-20211124016')->first();
                $this->json = $this->parseParameters($request);
            } catch (\Throwable $th) {
                if ($th->getCode() == '42S22') {
                    // error if column not exist
                    $this->error("Column $this->orderByColumn not found");
                } else if ($request->has('orderBy') && ($this->orderByValue != 'asc' || $this->orderByValue != 'desc')) {
                    // error when order value not ASC or DESC
                    $this->error("Value $this->orderByValue is forbidden, order must be ASC or DESC.");
                } else $this->error($th->getMessage());
            }
        }
        return $this->response();
    }

    public function getAt(Request $request, $table, $id)
    {
        parent::__construct($request, $table);

        if ($this->model != null) {
            if ($request->has('relation')) {
                $this->json = $this->model->withTrashed()
                    ->with($this->model->getRelations())
                    ->where('id', $id)
                    ->first();
            } else {
                $this->json = $this->model->withTrashed()
                    ->where('id', $id)
                    ->first();
            }
            // $relation = explode('-', $request->get("with"));
            // $relation = ($relation[0] == "") ? [] : $relation;

        }
        return $this->response();
    }

    public function getAtColumn(Request $request, $table, $id, $column)
    {
        parent::__construct($request, $table);

        if ($this->model != null) {
            // $relation = explode('-', $request->get("with"));
            // $relation = ($relation[0] == "") ? [] : $relation;
            $this->json = $this->model->withTrashed()
                // ->with($relation)
                ->where('id', $id)
                ->first($column);
        }
        return $this->response();
    }

    public function index(Request $request, $table)
    {
        parent::__construct($request, $table);

        if ($this->model != null) {
            return $this->success($this->model->count());
        }
        return $this->error('Model not found');
    }
}
