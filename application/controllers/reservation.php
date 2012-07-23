<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//update by ZH 2012.7.22
//更新内容:
//update index()		测试用

//----------------------------函数列表----------------------------
//---------------------------------------------------------------

class reservation extends CI_Controller
{
	function index()
	{
		$uid = $this->session->userdata('id');
		$res = $this->reservation_model->show($uid,1);
		$res2 = $this->reservation_model->show($uid,0);
		$notice = $this->reservation_model->get_notice($uid,1);
		echo 'new reservation:'.count($notice).'<br />';
		foreach ($notice as $row)
		{
			// sid,tid,date,type,info,rtime
			
			
			
			echo 'from'.$row['post'].'|';
		    $temp = $this->reservation_model->get($row['thing']);
			echo $temp['date'].'<br />';
			echo $temp['info'].'<br />';
			
		}
		
		if(empty($res))
				return 'no reservation';
		//var_dump( $res[0] );
		foreach ($res2 as $row)
		{
			// sid,tid,date,type,info,rtime
			echo 'todo<br />';
		   echo $row['sid'].'|';
		   echo $row['tid'].'|';
		   echo $row['date'].'<br />';
		}
		foreach ($res as $row)
		{
			// sid,tid,date,type,info,rtime
			echo 'success<br />';
		   echo $row['sid'].'|';
		   echo $row['tid'].'|';
		   echo $row['date'].'<br />';
		} 
		//$this->load->view('user/home',$res);
		//$this->output->set_output($res);
		
		$this->load->helper(array('form', 'url'));  
		$this->load->library('form_validation');
			
		
		
			$sid = $uid;
			$tid = $this->input->post('tid');
			$date = $this->input->post('date');
			$type = $this->input->post('type');
			$info = $this->input->post('info');
					 
			$this->reservation_model->add($sid,$tid,$date,$type,$info);
		
		if ($this->form_validation->run() == FALSE)
		{
		   $this->load->view('user/home');
		}	
	}
	
	
}
