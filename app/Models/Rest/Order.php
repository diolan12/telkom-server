<?php

namespace App\Models\Rest;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Carbon\Carbon;

class Order extends BaseModel
{
    use SoftDeletes;

    public static function boot(){
        parent::boot();
        self::creating(function ($model) {
            // SC-2021 11 20 00001
            $carbon = Carbon::now('Asia/Jakarta');
            $month = (strlen($carbon->month) == 1) ? '0'.$carbon->month : $carbon->month;
            $day = (strlen($carbon->day) == 1) ? '0'.$carbon->day : $carbon->day;
            $model->uid = (string)IdGenerator::generate([
                'table' => $this->table,
                'length' => 5,
                'prefix' => 'SC-'. $carbon->year . $month . $day,
            ]);
        });
    }

    public function validation()
    {
        return [
            // 'uid' => 'required|unique:' . $this->getTable(),
            'field' => '',
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
        'field', 'office', 'field', 'status', 'customer', 'service_type'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
