<?php

namespace App\Models\Rest;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends BaseModel
{
    use SoftDeletes;

    public function validation()
    {
        return [
            'uid' => 'required|unique:' . $this->getTable(),
            'office' => 'required',
            'status' => 'required',
            'customer' => 'required',
            'service_type' => 'required',
        ];
    }
    public function filter($data)
    {
        unset($data['id']);
        return $data;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid', 'office', 'field', 'status', 'customer', 'service_type'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
