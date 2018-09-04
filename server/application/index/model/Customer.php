<?php 
namespace app\index\model;
use think\Model;

class Customer extends Model 
{
	protected $table = "cx_customer";

	protected $autoWriteTimestamp = 'datetime';   //自动时间

	public function orders()
	{
		return $this->hasMany('Order','customer_id','id');
	}
}