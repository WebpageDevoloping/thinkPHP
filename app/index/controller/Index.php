<?php
namespace app\index\controller;

use app\BaseController;

use think\facade\View;//composer安装扩展：composer require topthink/think-view；

class Index extends BaseController
{
    public function index()
    {
        return View::fetch();
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }
}
