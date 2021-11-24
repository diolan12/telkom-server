<?php

namespace App\Models\Rest;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Carbon\Carbon;

class Order extends BaseModel
{
    use SoftDeletes;
    protected $table = 'orders';


    public function validation()
    {
        return [
            // 'uid' => 'required|unique:' . $this->getTable(),
            'field' => '',
            'office' => 'required',
            'status' => 'required',
            'customer' => 'required',
            'service' => 'required',
        ];
    }
    public function filter($data)
    {
        unset($data['id']);

        // SC-20212010001
        $carbon = Carbon::now('Asia/Jakarta');
        $month = (strlen($carbon->month) == 1) ? '0'.$carbon->month : $carbon->month;
        $day = (strlen($carbon->day) == 1) ? '0'.$carbon->day : $carbon->day;
        $data['uid'] = (string)IdGenerator::generate([
            'table' => $this->table,
            'field' => 'uid',
            'length' => 14,
            'prefix' => 'SC-'. $carbon->year . $month . $day,
        ]);
        return $data;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'field', 'office', 'status', 'customer', 'service'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $relations = ['field', 'office', 'customer', 'service', 'service.type'];

    public function field()
    {
        return $this->belongsTo('App\Models\Rest\Account', 'field');
    }

    public function office()
    {
        return $this->belongsTo('App\Models\Rest\Account', 'office');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Rest\Customer', 'customer');
    }

    public function service()
    {
        return $this->belongsTo('App\Models\Rest\Service', 'service');
    }
    public function getDocCustomerAttribute($value){
        if ($value != null) {
            return url('/assets/uploads/order/' . $value);
        }
        return $value;
    }

    public function getDocHouseAttribute($value){
        if ($value != null) {
            return url('/assets/uploads/order/' . $value);
        }
        return $value;
    }
}
