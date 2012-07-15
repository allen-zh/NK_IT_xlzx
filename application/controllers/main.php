<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class main extends CI_Controller
{
	function index()
	{
		$this->load->view('user/index');
	}
}
