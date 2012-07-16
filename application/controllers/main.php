<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//update by L 2012.7.15
//更新内容:
//全部

//----------------------------函数列表----------------------------
//---------------------------------------------------------------

class main extends CI_Controller
{
	function index()
	{
		/*测试老师注册
		$info=array(
			'id'=>'0910505',
			'nickname'=>'PoppinL',
			'password'=>'qq199148');
		var_dump($this->user_model->create_teacher($info));*/
		
		//测试老师登录
		//var_dump($this->user_model->login('0910505','12345',true));
		//var_dump($this->user_model->is_login());
		//var_dump($this->user_model->logout());
		
		//测试修改老师信息
		/*$info=array(
			'password'=>'12345');
		var_dump($this->user_model->edit_teacher('0910505',$info));*/
		
		//测试获取某id的信息
		//var_dump($this->user_model->get_info('0910505'));
	}
}
