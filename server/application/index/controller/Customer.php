<?php 
namespace app\index\controller;
use think\Controller;
use think\Cookie;
use app\index\model\Customer as customerModel;
use think\Request;

class Customer extends Controller 
{
	//客户-列表
	public function index($cus_name='',$cus_telphone='')
	{
		$createbyid = request()->param('createbyid');
		$page = request()->param('page',1);
    $pageSize = request()->param('pageSize',5);
		$admin_cixi = array('id'=>1,'name'=>'xidada'); 
		$list = customerModel::field('id,cus_name,cus_telphone,cus_email,cus_address,cus_birthday')
			->where('createbyid',$createbyid)
			->where(function($query) use($cus_name,$cus_telphone){
				if($cus_name){
					$query->where('cus_name','like',"%$cus_name%");
				}
				if($cus_telphone){
					$query->where('cus_telphone','like',"%$cus_telphone%");
				}
			})->limit($pageSize*($page-1),$pageSize)->select();
			return json(['code' => 200,'msg' => '成功','data' => $list]);
	}

	//客户-新建
	public function save(Request $request)
	{
		if (!$request->isPost()) {
			return;
		}
		//操作人  id,名字
		
		$data = input('post.');
		//dump($data);
		$cusobj = new customerModel();
		if($cusobj->allowField(true)->save($data)){
			$msg = '客户[ ' . $cusobj->cus_name . ':' . $cusobj->cus_telphone . ' ]新增成功';
			return json(['code' => 200,'msg' => $msg]);
		} else {
			return $cusobj->getError();
		}
	}

	//客户-详情
	public function read()
	{
		$cus_id = input('param.id');
		$res = customerModel::with('orders')->where('id',$cus_id)->find();
		return json(['code' => 200,'msg' => '获取成功','data' => $res]);
	}

	//客户-修改
	public function update(Request $request) 
	{
		if (!$request->isPut()){
			return;
		}
		

		$cus_id = input('param.id');
		$customerInfo = input('put.');

		$customer = customerModel::get($cus_id);
		if($customer->allowField(true)->save($customerInfo)) {
			return json(['code' => 200,'msg' => '更新成功']);
		}else {
			return json(['code' => 400,'msg' => '更新失败']);
		}

	}
}