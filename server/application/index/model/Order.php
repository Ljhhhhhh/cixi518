<?php 
namespace app\index\model;
use think\Model;
use traits\model\SoftDelete;

class Order extends Model 
{
	protected $table = "cx_order";

		use SoftDelete;
    protected $deleteTime = 'delete_time';

	//protected $autoWriteTimestamp = true;
	protected $autoWriteTimestamp = 'datetime';   //自动时间

	public function products()
	{
		return $this->hasMany('Orderproduct','order_id','order_id');
	}
	public function tocustomer()
	{
		return $this->belongsTo('Customer','customer_id','id');
	}
}