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
            'no_indihome' => '',
            'no_telephone' => '',
            'name' => 'required',
            'email' => 'required|unique:' . $this->getTable(),
            'name' => 'required',
            'gender' => 'required',
            'phone' => 'required',
            'whatsapp' => '',
            'address' => 'required'
        ];
    }
    public function filter($data)
    {
        unset($data['id']);
        if (array_key_exists('phone', $data)){
            $data['phone'] = preg_replace('/\D/i', '', $data['phone']);
        }
        if(array_key_exists('whatsapp', $data)){
            if($data['whatsapp'] != null && $data['whatsapp'][0] == '0'){
                $data['whatsapp'] = "62".substr($data['whatsapp'], 1);
            }
            $data['whatsapp'] = preg_replace('/\D/i', '', $data['whatsapp']);
        }
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
