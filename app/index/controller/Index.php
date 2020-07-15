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
		//insert()数据新增
		$data=[
			'user_account' => '123459',
			'user_name'	   => 'wonderful5',
			'user_password'=> '1951681585',
			'user_sex'     => '女',
			'create_time'  => '2020-09-11 00:00:44',
			'user_status' => '2',
			'suer_id'	  => '5',
			
		];
		//单条新增
		$query=Db::connect('mysql')->name('account');
		/*$result=$query->insert($data);
		dump($result); */
		//strict(false)抛弃数据表不纯在字段
		//$query->removeOption(where)->select();
		//$result=$query->strict(false)->insert($data);
		
		return $query->strict(false)->insertGetId($data);
		
		
	}
	
	public function insertAll()
	{
		$data=[
			[
				'user_account' => '123459',
				'user_name'	   => 'wonderful5',
				'user_password'=> '1951681585',
				'user_sex'     => '女',
				'create_time'  => '2020-09-11 00:00:44',
				'user_status' => '2',
				'suer_id'	  => '5',
			],
			[
				'user_account' => '55555',
				'user_name'	   => 'wonderful5',
				'user_password'=> '1951681585',
				'user_sex'     => '男',
				'create_time'  => '2020-09-11 00:00:44',
				'user_status' => '1',
				'suer_id'	  => '5',
			],
		];
		$query =Db::connect('mysql')->name('account');
		$result=$query ->strict(false)->insertAll($data);
		return $result;
	}
	
	public function save()
	{
		//save()只允许一条数据插入
		$data=[
			
				//'id'		  =>  '5',
				'user_account' => '123459',
				'user_name'	   => 'wonderful5',
				'user_password'=> '1951681585',
				'user_sex'     => '男',
				'create_time'  => '2020-09-11 00:00:44',
				'user_status' => '2',	
		];
		//save()添加数据如果主键存在则修改 ，否则添加
		$query=Db::connect('mysql')->name('account');
		$result =$query -> save($data);
		return $result;
	}
	
	public function update()
	{
		$data=[
			'id'=>'20',
			'user_name'=>'beadtiful',
			'user_sex'=>'男',
		];
		$query=Db::connect('mysql')->name('account');
		
		/*//修改数据中存在主键可以省略where语句
		$result = $query ->update($data);
		
		//在修改时执行sql操作函数exp();
		$result1=$query ->exp('user_name','upper(user_name)')->update();*/
		
		//自增，自减
		$result1=$query->where('id',2)->inc('user_status',2)->update();
		
		dump($result1);
		
		
	}
	
	public function raw()
	{
		
		$data=[
			'user_name'=> Db::raw('upper(user_name)'),
			'user_status'=>Db::raw('user_status+2'),
			
		];
			$query=Db::connect('mysql')->name('account');
		$result=$query ->where('id',1)->update($data);
		return $result;
	}
	
	public function delete()
	{
		//单条删除
		$query=Db::connect('mysql')->name('account');
		
		//$result=$query->delete(20);
		//多条数据删除
		//$reslut=$query->delete([17,18,19]);
		
		//where()删除
		$result=$query->where('id','>','6')->delete();
		
		//删除所有数据
		//$result=$query->delet(true);
		return $result;
	}
	
    public function like()
	{
		//链接数据可库，实列化数据库对象
		$query=Db::connect('mysql')->name('account');
		
		//模糊查询like();
		//$result=$query->where('user_name','like','%wonderful%')->select();
		
		//like()数组操作
		//$result=$query->where('user_name','like',['%wonderful%','%beautiful%'],'or')->select();
		
		//快捷方式whereLike(),whereNotLike();
		//$result=$query->whereLike('user_name','%wonderful%')->select();
		$result=$query->whereNotLike('user_name',['%wonderful%','%beautiful%'],'or')->select();
		return json($result);
	}
	public function between()
	{
		//查询两者之间的数据between()
		$query=Db::connect('mysql')->name('account');
		
		//$result=$query->where('id','between',[1,5])->select();
		//快捷方式whereBetween(),whereNotbetween();
		//$result=$query->whereBetween('id','2,4')->select();
		$result=$query->whereNotbetween('id',[2,4])->select();
		return json($result);
	}
	
	public function inOrNull()
	{
		$query=Db::connect('mysql')->name('account');
		//whereIn();
		//$result = $query->where('id','in','1,2,3')->select();//not in 
		//快捷方式whereIn(),whereNotIn()
		//$result=$query->whereIn('id','1,2')->select();
		
		//$result=$query->whereNotIn('id','1,2')->select();
		
		//whereNull();
		//$result=$query->where('user_brithday','null')->select();//not null
		
		//快捷方式whereNOtNll(),whereNull();
		$result=$query->whereNOtNull('user_brithday')->select();
		return json($result);
	}
	public function exp()
	{
		//exp()自定义字段后的sql语句
		$query=Db::connect('mysql')->name('account');
		//$result=$query->where('id','exp','in(1,2)')->select();
		//快捷方式
		$result =$query->whereExp('id','not in(1,2)')->select();
		dump($result);
	}
	
	public function time()
	{
		//whereTime()whereNotBetweenTime()有问题
		$query=Db::connect('mysql')->name('account');
		$result1=$query->whereTime('create_time','2020-7-10')->select();
		$query->removeOption('where')->select();
		$result= $query ->whereBetweenTime('create_time','2020-7-1','2020-7-14')->select();
		dump($result);
	}
}
