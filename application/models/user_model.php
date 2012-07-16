<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//update by L 2012.7.16
//更新内容:
//add get_info()
//add is_login()
//add login()
//add logout()
//add edit_student()
//add edit_teacher()

//----------------------------函数列表----------------------------
/*
 * exist($id)		判断ID是否存在
 * @id			需要判断的id
 * @return		0不存在|1学生ID|2老师ID
 * */
/*
 * login($id,$pwd,$remember=false)		用户登录
 * @id			登录id
 * @pwd			密码
 * @remember	保持登录1个小时
 * @return		账户所有信息|错误信息
 * */
/*
 * logout()		用户登出
 * @return 		null
 * */
/*
 * is_login()	判断用户是否登入
 * @return		false|账户信息
 * */
/*
 * create_student($id,$nickname)	创建学生
 * @id			学生id
 * @nickname	学生昵称
 * @return		true|错误信息
 * */
/*
 * edit_student($id,$new_nickname)		编辑学生
 * @id				学生id
 * @new_nickname	新昵称
 * @return			true|错误信息
 * */
/*
 * create_teacher($array)		创建老师
 * @array	id				id
 * 			nickname		昵称
 * 			password		密码
 * 			phone			电话号码
 * 			mail			邮箱
 * 			introduction	简介
 * @return		true|错误信息
 * */
/*
 * edit_teacher($array)			编辑老师
 * @array	nickname		昵称
 * 			password		密码
 * 			phone			电话
 * 			mail			邮箱
 * 			introduction	简介
 * 			avatar			头像
 * @return		true|错误信息
 * */
/*
 * get_info($id)		获取账户信息
 * @id			账户id
 * @return		false|账户的所有信息
 * */
//---------------------------------------------------------------

class user_model extends CI_model
{
	function exist($id)
	{
		$res=$this->db->query("select id from teacher where id=$id limit 1")->row_array();
		if(empty($res))
		{
			$res=$this->db->query("select id from student where id=$id limit 1")->row_array();
			if(empty($res))
			{
				return 0;
			}
			else
			{
				return 1;
			}
		}
		else
		{
			return 2;
		}
	}
	
	function login($id,$pwd,$remember=false)
	{
		$id2=$this->db->escape($id);
		$res_id=$this->user_model->exist($id2);
		if($res_id==2)
		{
			$temp=$this->db->query("select * from teacher where id=$id2 limit 1")->row_array();
			if(md5($pwd.$temp['salt'])==$temp['password'])
			{
				if($remember)
				{
					$this->input->set_cookie('id', $id, 3600);
				}
				else
				{
					$this->input->set_cookie('id', $id, 0);
				}
				$this->session->set_userdata('id', $id);
				return $temp;
			}
			else
			{
				return "Error:错误的密码";
			}
		}
		else
		{
			//向学院it服务器发送请求，验证学生的学号和密码，根据返回值来继续操作
		}
	}
	
	function logout()
	{
		$this->input->set_cookie('id', '', null);
		$this->session->sess_destroy();
	}
	
	function is_login()
	{
		$session_id=$this->session->userdata('id');
		$cookie_id=$this->input->cookie('id',true);
		if(!empty($session_id)&&!empty($cookie_id))
		{
			$temp=$this->user_model->get_info($session_id);
			if($temp['id']==$cookie_id)
			{
				return $temp;
			}
		}
		return false;
	}
	
	function create_student($id,$nickname)
	{
		$id=$this->db->escape($id);
		$nickname=$this->db->escape($nickname);
		$res=$this->db->query("select id from student where id=$id or nickname=$nickname limit 1")->row_array();
		if(empty($res))
		{
			$this->db->query("insert into student(id,nickname) values($id,$nickname)");
			$this->db->query("insert into list(owner) values($id)");
			return true;
		}
		else
		{
			return "Error:重复的ID或昵称";
		}
	}
	
	function edit_student($id,$new_nickname)
	{
		$id=$this->db->escape($id);
		$new_nickname=$this->db->escape($new_nickname);
		$res=$this->db->query("select id from student where nickname=$new_nickname limit 1")->row_array();
		if(empty($res))
		{
			$this->db->query("update student set nickname=$new_nickname where id=$id");
			return true;
		}
		else
		{
			return "Error:重复的昵称";
		}
	}
	
	function create_teacher($array)
	{
		$id=$this->db->escape($array['id']);
		$nickname=$this->db->escape($array['nickname']);
		if(isset($array['phone'])){$phone=$this->db->escape($array['phone']);}else{$phone="''";}
		if(isset($array['mail'])){$mail=$this->db->escape($array['mail']);}else{$mail="''";}
		if(isset($array['introduction'])){$introduction=$this->db->escape($array['introduction']);}else{$introduction="''";}
		
		$res=$this->db->query("select id from teacher where id=$id or nickname=$nickname limit 1")->row_array();
		if(empty($res))
		{
			$salt=substr(uniqid(),-10);
			$pwd=md5($array['password'].$salt);
			$this->db->query("insert into teacher(id,password,nickname,mail,phone,introduction,salt) values($id,'$pwd',$nickname,$mail,$phone,$introduction,'$salt')");
			$this->db->query("insert into list(owner) values($id)");
			return true;
		}
		else
		{
			return "Error:重复的ID或昵称";
		}
	}
	
	function edit_teacher($id,$array)
	{
		$id=$this->db->escape($id);
		if(isset($array['nickname'])){
			$nickname=$this->db->escape($array['nickname']);
			$temp=$this->db->query("select id from teacher where nickname=$nickname limit 1")->row_array();
			if(!empty($res)){return "Error:重复的昵称";}
		}else{$nickname='nickname';}
		if(isset($array['phone'])){$phone=$this->db->escape($array['phone']);}else{$phone='phone';}
		if(isset($array['mail'])){$mail=$this->db->escape($array['mail']);}else{$mail='mail';}
		if(isset($array['password'])){
			$password=$array['password'];
			$temp=$this->db->query("select salt from teacher where id=$id")->row_array();
			$password="'".md5($password.$temp['salt'])."'";
		}else{$password='password';}
		if(isset($array['avatar'])){$avatar=$this->db->escape($array['avatar']);}else{$avatar='avatar';}
		if(isset($array['introduction'])){$introduction=$this->db->escape($array['introduction']);}else{$introduction='introduction';}

		$this->db->query("update teacher set password=$password,nickname=$nickname,phone=$phone,mail=$mail,avatar=$avatar,introduction=$introduction where id=$id");
		return true;
	}
	
	function get_info($id)
	{
		$id=$this->db->escape($id);
		$temp=$this->user_model->exist($id);
		switch($temp)
		{
			case 2:
			{
				$res=$this->db->query("select * from teacher where id=$id limit 1")->row_array();
				return $res;
			}
			case 1:
			{
				$res=$this->db->query("select * from student where id=$id limit 1")->row_array();
				return $res;
			}
			case 0:
			{
				return 'Error:不存在的账户';
			}
		}
	}
}
