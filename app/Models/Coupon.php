<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable=['code','type','value','min_purchase_amount','status'];

    public static function findByCode($code){
        return self::where('code',$code)->first();
    }
    public function discount($total){
        if($this->type=="fixed"){
            return $this->value;
        }
        elseif($this->type=="percent"){
            return ($this->value /100)*$total;
        }
        else{
            return 0;
        }
    }

    public function isEligible($cartTotal)
    {
        return $cartTotal < $this->min_purchase_amount ? false : true;
    }
}
