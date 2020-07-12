<?php
namespace app\index\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\Env;

use think\facade\View;//composer安装扩展：composer require topthink/think-view；

class Index extends BaseController
{
    public function index()
    {
        return View::fetch();
    }
		//多数据库链接
	public function connection()
	{
			//coonnect()指定数据库配置
		$date1=Db::connect('mysql')->table('user_account')->select();
		$date2=Db::connect('manager')->name('account')->select();
		dump([$date1,$date2]);
			//getLastSql()获取最近一条执行的sql语句
		$sql1=Db::connect('mysql')->getLastSql();
		$sql2=Db::connect('manager')->getLastSql();
		echo $sql1.'<br/>'.$sql2;
	}
	
	public function inquire()
	{
		//find()单条数据查询,成功返回查询数组，失败返回nll
		$query=Db::connect('mysql')->name('account');//$query保存实列
		$result=$query->where('id',1)->find();
		
		//findOrFail()查询到返回数组，没找到抛出异常
			//同一个实列查询第二次会保留第一次查询的结果，removeOption（'where'）清楚保留
		$query->removeOption('where')->select();
		$result1=$query->where('id',2)->findOrFail();
		
		//findOeEmpty()没找到返回空数组
		$query->removeOption('where')->select();
		$result2=$query->where('id',1)->findOrEmpty();
		
		//多条数据查询select（）
		$query->removeOption('where')->select();
		$results1=$query->select();
		
		//selectOrFail()查询到返回数组，没找到抛出异常toArray()将结果转换为数组
		$results2=$query->selectOrFail()->toArray();
		
		//value()查询单个字段值
		$query->removeOption('where')->select();
		$value=$query->where('id',1)->value('user_account');
		
		//column()查询多个字段值
		$query->removeOption('where')->select();
		$values=$query->column('user_account');
		
		//分批处理解内存开销过大
		/*Db::name('account')->chunk(1, function($users) {
		foreach ($users as $user) {
				dump($user);
			}
			echo 1;
		});*/
		
		//游标查询大幅减少内存开销过大
		/*$cursor = Db::name('account')->cursor();
		foreach($cursor as $user){
			dump($user);
		}*/
		//链式查询，只要通过'->'链接调用方法就是链式查询，只要返回对象时Query时就可以一直使用链式查询
		//dump($values) ;
	}
	
	public function insert()
	{
		
	}
    public function hello()
    {
        //$data=Db::connect('test1')->table('test2')->select();
		//$data1=Db::connect('test')->table('test1')->select();
		/*$data=Db::connect('test1')->name('test2')->cursor();
			foreach($data as $data){
				dump($data);
				echo 1;
			}
			return Db::connect('test1')->getLastSql();
		    */
		
		//$data=Db::connect('test')->name('test1')->where('id',1)->value('user_name');
		
		$data=[
			
			'user_name' => 'wonderful5',
			'user_password' => '1234560',
			'user_time' => '2020-11-11'
		];
		/*$userQuery1=Db::connect('mysql')->name('test1');
		$userQuery2=Db::connect('test2')->name('test2');
		
		$userFind2=$userQuery2->where('id',1)->select();
		$userQuery2->removeOption('where')->select();
		$userFind=$userQuery2->select();
		return Db::connect('test2')->getLastSql();
		*/
//		$data=Db::connect('test2')->name('test2')->insert($data);
//		echo $data;
		
		
    }
}
