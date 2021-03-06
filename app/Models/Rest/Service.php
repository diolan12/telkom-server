<?php

namespace App\Models\Rest;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends BaseModel
{
    use SoftDeletes;

    public function validation()
    {
        return [
            'type' => 'required',
            'name' => 'required'
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
        'type', 'name'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $relations = ['type'];

    public function type()
    {
        return $this->belongsTo('App\Models\Rest\ServiceType', 'type');
    }
}
