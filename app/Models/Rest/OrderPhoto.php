<?php

namespace App\Models\Rest;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPhoto extends BaseModel
{
    use SoftDeletes;

    public function validation()
    {
        return [
            'order' => 'required',
            'file' => '',
            'description' => 'required'
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
        'order', 'file', 'description'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function getFileAttribute($value)
    {
        if ($value != null) {
            return url('/assets/uploads/order-photo/' . $value);
        }
        return $value;
    }
}
