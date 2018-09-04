<?php 
namespace app\index\controller;
use think\Controller;
use app\index\model\Order as OrderModel;
use app\index\model\Customer as customerModel;
use app\index\model\Orderproduct;
use think\Request;


class Order extends Controller 
{
	//订单-列表
	public function index()
	{
		$page = request()->param('page',1);
    $pageSize = request()->param('pageSize',6);
		$number = input('param.number');
		$customer = input('param.customer');
		$ordertime_start = input('param.ordertime_start');
		$ordertime_end = input('param.ordertime_end');
		$createbyid = request()->param('createbyid');
		$ispay = request()->param('ispay');  //1：已付款  0：未付款
		//$admin_cixi = Cookie::get('admin_cixi'); 
		// $admin_cixi = array('id'=>1,'name'=>'xidada'); 
		$orderobj = new OrderModel();
		$list = $orderobj->with('products,tocustomer')->where('createbyid',$createbyid)
				->where(function($query) use ($number,$customer,$ordertime_start,$ordertime_end,$ispay){
					if($number) {
						$query->where('order_number','like',"%$number%");
					}
					if($customer) {
						$query->where('customer_id',$customer);
					}
					if(isset($ispay)) {
						$query->where('ispay',$ispay);
					}
					if($ordertime_start) {
						$query->where('create_time','>=',$ordertime_start);
					}
					if($ordertime_end) {
						$query->where('create_time','<=',$ordertime_end);
					}
				})->order('create_time','desc')->limit($pageSize*($page-1),$pageSize)->select();
		return json(['code' => 200,'msg' => '成功','data' => $list]);
		// dump($res);
	}

	//订单-新建
	public function save(Request $request) {
		if(!$request->isPost()){
			return;
		}
		$ordata = [];    	//订单信息
		//$prodata = [];		//订单产品信息

		$ordata['customer_id'] = input('param.customer_id');
		$ordata['createbyid'] = input('param.createbyid');
		$prodata = input('param.product_info');
		$prodata = (array)json_decode($prodata,true);
		
		$orderobj = new OrderModel();
		// return json($ordata);
		//生成订单编号
		$rs = $orderobj->max('order_number');
		$ymd = date('Ymd');
		if($ymd != substr($rs,0,8)){
			$ordata['order_number'] = $ymd.'001';
		}else{
			$ordata['order_number'] = $rs+1;
		}
		$res = (object)array();
		if($orderobj->allowField(true)->save($ordata)) {
			$neworder = OrderModel::get($orderobj->order_id);   //新生成的订单信息
			$neworder->products()->saveAll($prodata);
			$code = 200;
			$msg = '订单已生成,订单编号:'.$neworder->order_number;
		}else{
			$res->code = 400;
			$msg = '订单创建不成功';
		}
		return json(['code' => $code,'msg' => $msg]);
	}

	//订单-详情
	public function detail(Request $request)
	{
		// return 1;
		$order_id = input('param.order_id');
		$res = OrderModel::with('products,tocustomer')->where('order_id',$order_id)->find();
		return json(['code' => 200,'msg' => '获取详情成功','data' => $res]);
	}


	//订单-修改
	public function setpay(Request $request)
	{
		if (!$request->isPost()) {
			return;
		}
		$order_id = input('post.order_id');
		$ispay = input('post.ispay');
		$order = OrderModel::get($order_id);
		$res = $order->save(['ispay'=>$ispay]);
		if ($res) {
			return json(['code' => 200,'msg' => '修改成功']);
		} else {
			return json(['code' => 200,'msg' => '修改失败']);
		}
	}

	//订单-加入商品
	public function edit($order_id)
	{
		$info = input('post.');    //
		//按单价和数量计算当前商品的总价
		$info['allpay'] = bcmul($info['price'],$info['number'],2);
		$orderobj = OrderModel::get($order_id);
		$orderobj->products()->save($info);
	}

	//订单-删除
	public function delete($id)
    {
        // 软删除
		return OrderModel::destroy($order_id);
    }
}