<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//update by L 2012.7.15
//更新内容:
//全部

//----------------------------函数列表----------------------------
/*
 * exist($id)
 * @id		需要判断的id
 * @return	0不存在|1学生ID|2老师ID
 * */
/*
 * login($id,$pwd)
 * @id		登录id
 * @pwd		密码
 * @return	账户所有信息|错误信息
 * */
/*
 * logout()
 * @return null
 * */
/*
 * create_student($id,$nickname)
 * @id			学生id
 * @nickname	学生昵称
 * @return		true|错误信息
 * */
/*
 * edit_student($id,$new_nickname)
 * @id				学生id
 * @new_nickname	新昵称
 * @return			true|错误信息
 * */
/*
 * create_teacher($array)
 * @array	id				id
 * 			nickname		昵称
 * 			password		密码
 * 			phone			电话号码
 * 			mail			邮箱
 * 			introduction	简介
 * @return		true|错误信息
 * */
/*
 * edit_teacher($array)
 * @array	nickname		昵称
 * 			password		密码
 * 			phone			电话
 * 			mail			邮箱
 * 			introduction	简介
 * 			avatar			头像
 * @return		true|错误信息
 * */
/*
 * get_info($id)
 * @id			账户id
 * @return		账户的所有信息
 * */
//---------------------------------------------------------------

class user_model extends CI_model
{
	function exist($id)
	{
		$res=$this->db->query("select * from teacher where id=$id limit 1")->row_array();
		if(empty($res))
		{
			$res=$this->db->query("select * from student where id=$id limit 1")->row_array();
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
	
	function login($id,$pwd)
	{
		$id=$this->db->escape($id);
		$res_id=$this->user_model->exist($id);
		if($res_id==2)
		{
			$temp=$this->db->query("select * from teacher where id=$id limit 1")->row_array();
			if(md5($pwd.$temp['salt'])==$temp['password'])
			{
				$this->input->set_cookie('id', $id, 0);
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
			//向it服务器发送请求，根据返回值来继续操作
		}
	}
	
	function logout()
	{
		$this->input->set_cookie('id', '', null);
		$this->session->sess_destroy();
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
		switch($this->user_model->exist($id))
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
