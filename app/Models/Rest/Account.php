<?php

namespace App\Models\Rest;

use Illuminate\Auth\Authenticatable;
use App\Models\BaseModel;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;

class Account extends BaseModel implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory, SoftDeletes;

    public function validation()
    {
        return [
            'nik' => 'required|unique:' . $this->getTable(),
            'email' => 'required|unique:' . $this->getTable(),
            'name' => 'required',
            'gender' => 'required',
            'phone' => 'required',
            'whatsapp' => '',
            'password' => '',
            'role' => 'required'
        ];
    }
    public function filter($data)
    {
        unset($data['id']);
        if (!array_key_exists('photo', $data)) { $data['photo'] = 'default.jpg'; }
        if (array_key_exists('name', $data)) $data['name'] = ucwords($data['name']);
        if (array_key_exists('password', $data)) $data['password'] = Hash::make($data['password']);
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

    public function getPhotoAttribute($value){
        return url('/assets/uploads/account/' . $value);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nik', 'email', 'name', 'gender', 'phone', 'whatsapp', 'photo', 'password', 'role'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password'];
}
