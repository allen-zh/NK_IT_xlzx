<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//update by ZH 2012.7.21
//更新内容:
//add get_people($id)
//add send($id,$to,$content)
//add show($id,$to)
//add read($msg_id)
//add delete($msg_id)

//----------------------------函数列表----------------------------
/*
 * get_people($id)		获取与用户有私信来往的人的id
 * @id					user id
 * @return		error info | 
 *				result_array()
 *					post		发送者id
 *					to_id		接受者id
 *					last_msg	最后一条私信的id
 *					last_time	最后一条私信的时间(降序排列)
 * */
/* send($id,$to,$content)	发送私信
 * @id		发送者id
 * @to		接受者id
 * @content	内容
 * @return	msg_id
 *
 * */
/* show($id,$to)	显示私信
 * @id		用户id
 * @to		对方id
 * @return		error info | 
 *				result_array()
 *					id			私信id
 *					post		发送者id
 *					receive		接受者id
 *					content		私信内容
 *					date		私信时间(降序排列)
 * */ 

//---------------------------------------------------------------

class message_model extends CI_model
{
	public function get_people($id)
	{
		$id=$this->db->escape($id);
		$res = $this->db->query("select * from msglist where 
			post=$id or to_id=$id  ORDER BY last_time DESC")->result_array();	//按时间降序排列			
		if( ! empty($res))
		{
			return $res;			
		}		
		else
		{
			return "no msg";
		} 	
	}
	
	function send($id,$to,$content)
	{
		$id=$this->db->escape($id);
		$to=$this->db->escape($to);
		$content=$this->db->escape($content);
		$res = $this->db->query("select * from msglist where 
			(post=$id and to_id=$to) or (to_id=$id and post=$to) limit 1" )->row_array();
		//找到两人的私信记录	
		$this->db->query("insert into message(post,receive,content) values($id,$to,$content)");
		$msg_id = $this->db->insert_id();		//插入新私信，$msg_id为私信的id 
		if( ! empty($res))  //若私信记录非空则更新最后一次的发送接收关系 私信id 和时间，为空则插入新纪录
		{
			$list_id = $res['id'];
			$this->db->query("update msglist set post=$id,to_id=$to,last_msg=$msg_id 
				where id=$list_id ");	
			return $msg_id;
		}		
		else
		{									
			$this->db->query("insert into msglist (post,to_id,last_msg) values ($id,$to,$msg_id)");
			return $msg_id;
		}		
	}

	function show($id,$to)  //
	{
		$id=$this->db->escape($id);
		$to=$this->db->escape($to);
		$res = $this->db->query("select * from message where 
			(post=$id and receive=$to) or (receive=$id and post=$to) ORDER BY date DESC" )->result_array();
		if( ! empty($res))
		{
			return $res;		
		}
		else
		{
			return "no msg";
		}
	}
	
	function read($msg_id)
	{
		//清除未读状态
	}
	
	function delete($msg_id)
	{
		//删除私信
	}
}
