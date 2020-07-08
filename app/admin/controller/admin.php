<?php
namespace app\admin\controller;

use app\BaseController;

use think\facade\View;
class Admin extends BaseController
{
	public function admin()
	{
		return View::fetch();
	}
}