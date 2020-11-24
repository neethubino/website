<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct() 
	{ 
	    parent::__construct(); 
	    $this->load->helper('form');
	    $this->load->helper('url');
	    $this->load->database();
	    $this->load->model('model'); 
	    $this->load->library('session');  
	} 
	public function index()
	{
		$this->load->view('Site/index');
	}
	public function about()
	{
		$this->load->view('Site/about');
	}
	public function booking()
	{
		$this->load->view('Site/booking');
	}
	public function contact()
	{
		$this->load->view('Site/contact');
	}
	public function room()
	{
		$data['details']=$this->model->view('hotel_details');
		$this->load->view('Site/room',$data);
	}
	public function room_single()
	{
		$id=$this->uri->segment(3);
		$data['details']=$this->model->viewimages('room_images',$id);
		$this->load->view('Site/room_single',$data);
	}
	public function services()
	{
		$this->load->view('Site/services');
	}
	public function payment()
	{
		$this->load->view('Site/payment');
	}
	
	// public function checkout()
	// {
	// 	$id=$this->uri->segment(3);
	// 	$table=$this->input->post('table');
	// 	$name=$this->input->post('name');
	// 	$price=$this->input->post('price');
	// 	$this->session->set_userdata('id',$id);
	// 	$this->session->set_userdata('name',$name);
	// 	$this->session->set_userdata('price',$price);

	// 	$data['details']=$this->model->sele($table,$id);
	// 	$this->load->view('Site/checkout',$data);
		
	// }

	
	
	
	
	
	
	
	// public function registercode()
	// {
	// 	$name=$this->input->post('name');
	// 	$email=$this->input->post('email');
	// 	$phone=$this->input->post('phone');
	// 	$password=$this->input->post('password');
	// 	$course=$this->input->post('course');
	// 	$data=array('name'=>$name,
	// 				'email'=>$email,
	// 				'phone'=>$phone,
	// 				'password'=>$password,
	// 				'course'=>$course
	// 				);
	// 	$data['details']=$this->model->insert('registration',$data);
	// 	$id=$this->db->insert_id();
	// 	$data=array(
	// 				'username'=>$email,
	// 				'password'=>$password,
	// 				'user_id'=>$id
	// 				);
	// 	$data['details']=$this->model->insert('user_login',$data);
	// 	redirect(base_url('Home/register'));
	// }

}