<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//update by ZH 2012.7.23
//更新内容:
//add add($sid,$tid,$date,$type,$info)
//add get($rid)
//add edit($rid,$date)
//add ok($rid)
//add get_notice($uid,$type)
//add show($uid,$ok)
//

//----------------------------函数列表----------------------------
/*
 * add($sid,$tid,$date,$type,$info)		添加一条预约
 * @$sid,$tid,$date,$type,$info			学生id|techer ID|date|type|info
 * @return								$rid 预约id
 * */

/*
 * get($rid)	get reservation info
 * @rid 		预约的id
 * @return		row_array()
 * */ 
/*
 * edit($rid,$date)	修改预约时间
 * 
 * 
 * */

/*
 * ok($rid)		修改由于状态为成功
 * 
 * 
 * */
/*
 * get_notice($uid,$type)	获取提醒信息
 * @uid						用户id
 * @type					提醒的类型 1预约 0私信
 * @return			result_array()	thing字段为预约或私信的id
 *  
 * */ 
/*
 * show($uid,$ok)			获取收到的预约（老师） 或者 发出的预约（学生）
 * @uid			老师或学生的id
 * @ok			预约的状态	0为处理中 1为预约成功（老师同意）
 * @return		result_array()
 * */

 
/*
 *
 * 
 * 
 * */

//---------------------------------------------------------------

class reservation_model extends CI_model
{

	function add($sid,$tid,$date,$type,$info)
	{
		$sid=$this->db->escape($sid);
		$tid=$this->db->escape($tid);
		$date=$this->db->escape($date);
		$type=$this->db->escape($type);
		$info=$this->db->escape($info);
		//insert info
		date_default_timezone_set('PRC');
		$rtime = date('YmdHis');		
		$this->db->query("insert into reservation(sid,tid,date,type,info,rtime) 	
			values($sid,$tid,$date,$type,$info,$rtime)");
		$rid = $this->db->insert_id();		
		//insert notice:check
		$this->db->query("insert into notice(post,receive,type,thing) 	
			values($sid,$tid,1,$rid)");
		return 'reservation id:'.$rid;
	
	}
		
	function get($rid)
	{
		$res = $this->db->query("select * from reservation where id=$rid  limit 1")->row_array();	
		return $res;
	}
	
	function edit($rid,$date)
	{
		
		//insert new info
		$this->db->query("update reservation set date=$date where id=$rid");	
		//insert notice:check
		
	}
	
	function ok($rid)
	{
		$this->db->query("update reservation set ok=1 where id=$rid");	
		//insert notice:ok
	
	}
	function get_notice($uid,$type)
	{
		$res = $this->db->query("select * from notice where 
				receive=$uid and status=1 and type=$type ORDER BY id DESC")->result_array();
		return $res;
	}
	
	function show($uid,$ok)
	{
		//1student or 2teacher
		$user = $this->user_model->exist($uid);	
		$uid = (int)$uid;
		//var_dump($uid);
		//get info
		if($user == 2)
		{
			$res = $this->db->query("select * from reservation where 
				tid=$uid and ok=$ok ORDER BY rtime DESC")->result_array();
			/* if(empty($res))
				return 'teacher:no reservation';
			else */	
			return $res;
		}
		else if($user == 1) 
		{
			$res = $this->db->query("select * from reservation where 
				sid=$uid and ok=$ok ORDER BY rtime DESC")->result_array();
			/* if(empty($res))
				return 'no reservation';
			else	 */
			return $res;
		}
		else return "user not exist";
	}
		
}
