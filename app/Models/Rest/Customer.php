<?php

namespace App\Models\Rest;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends BaseModel
{
    use SoftDeletes;

    public function validation()
    {
        return [
            'name' => 'required',
            'email' => 'required|unique:' . $this->getTable(),
            'name' => 'required',
            'gender' => 'required',
            'phone' => 'required',
            'address' => 'required'
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
        'no_indihome', 'no_telephone', 'email', 'name', 'gender', 'phone', 'whatsapp', 'address'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
