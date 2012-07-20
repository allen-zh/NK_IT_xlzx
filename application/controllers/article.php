<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//update by ZH 2012.7.20
//更新内容:
//add post()
//add get()
//add

//----------------------------函数列表----------------------------
/*
 *action()
 *
 *
 **/

//---------------------------------------------------------------

class article extends CI_Controller
{
	function action()
	{
		if(!empty($_POST['do']))
		{
			switch(strtolower($_POST['do']))
			{
				case 'post':
				{
					
					break;
				}
				case 'edit':
				{
					
					break;
				}
				case 'remove':
				{
					
					break;
				}
			}
		}
	}
	
	function post($array){
		
	}
	
	function get($id){
		
	}
	
}
