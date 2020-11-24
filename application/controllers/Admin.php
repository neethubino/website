<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
 
	public function __construct() 
	{
	    parent::__construct();
	    $this->load->helper('form'); 
	    $this->load->helper('url');
	    $this->load->database();
	    $this->load->model('model'); 
	    $this->load->library('session');
	} 
	public function login()
	{
		$this->load->view('Admin/login');
	}
		public function logout()
	{
		   $this->session->sess_destroy();
		   redirect(base_url('Admin/login'));
	}
	public function register_hotel()
	{
		$this->load->view('Admin/register_hotel');
	}
	public function logincode()
	{
		$username=$this->input->post('username');
	 	$password=$this->input->post('password');
	 	$data=$this->model->logincode1('admin_login',$username,$password);
		if($data!=false)
		{
		$this->session->set_userdata('id',$data['id']);
	 	redirect(base_url('Admin/admin_home'));
		}
			else
			{
			$this->session->set_flashdata('msg','invalid data');
			redirect(base_url('Admin/login'));
			}
	}
	public function admin_home()
	{
		if($this->session->userdata('id')!='')
		{
		$this->load->view('Admin/admin_home');
		}
		else
		{
			redirect(base_url('Admin/login'));
		}
	}
	public function viewimages()
	{
		if($this->session->userdata('id')!='')
		{
		$id=$this->uri->segment(3);
		$data['details']=$this->model->viewimages('room_images',$id);
		$this->load->view('Admin/viewimages',$data);
		}
		else
		{
			redirect(base_url('Admin/login'));
		}
	}

	public function viewcategory()
	{
		if($this->session->userdata('id')!='')
		{
		$id=$this->uri->segment(3);
		$data['details']=$this->model->viewcategory('category',$id);
		$this->load->view('Admin/viewcategory',$data);
		}
		else
		{
			redirect(base_url('Admin/login'));
		}
	}
	
	public function change_password()
	{
		$this->load->view('Admin/change_password');
	}
	public function change_pswd()
	{
		if($this->input->post('change_pass'))
		{
			$old_pass=$this->input->post('old_pass');
			$new_pass=$this->input->post('new_pass');
			$confirm_pass=$this->input->post('confirm_pass');
			$session_id=$this->session->userdata('id');
			$que=$this->db->query("select * from admin_login where id='$session_id'");
			$row=$que->row();
			//$pass=$row;
			//print_r($row->password);exit;
			if(($old_pass==$row->password) && ($confirm_pass==$new_pass))
				{
					$this->model->change_pass($session_id,$new_pass);
					//echo "Password changed successfully !";
					$this->session->set_flashdata('msg1','Password changed successfully !"');
				}
			    else{
					//echo "Invalid";
					$this->session->set_flashdata('msg','invalid data');
				}
		}
		redirect(base_url('Admin/change_password'));
		// $this->load->view('staff/change_password');
	}
	public function addhotel_details()
	{
	    $table="hotel_details";
		$hotel_name=$this->input->post('hotelname');
	    $location=$this->input->post('location');
		$hotel_description=$this->input->post('hotel_description');
	    $check1=$this->input->post('check1');
	    $check2=$this->input->post('check2');
	    $check3=$this->input->post('check3');
	    $check4=$this->input->post('check4');
	    $rate1=$this->input->post('rate1');
	    $rate2=$this->input->post('rate2');
	    $rate3=$this->input->post('rate3');
	    $rate4=$this->input->post('rate4');
	    $pool_name=$this->input->post('pool_name');



		$config['upload_path'] = './upload/';
            $config['allowed_types'] = '*';
            $config['max_size'] = '5000';
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('image'))
             {
          
                print_r($this->upload->display_errors());
              } 
              else 
              {
	            $data = $this->upload->data();
	            $img=$data['file_name'];
	            
        	  }

				$data=array(
					'hotel_name'=>$hotel_name,
					'location'=>$location,
				    'hotel_description'=>$hotel_description,
					 'hotel_image'=>$img,
					 'pool_name'=>$pool_name
					
	);
		$this->model->insert($table,$data);





		$hotel_id = $this->db->insert_id();
		  $this->load->library('upload');
               $number_of_files_uploaded=count($_FILES['images']['name']);
             //  print_r($number_of_files_uploaded);exit;
               for ($i = 0; $i < $number_of_files_uploaded; $i++)
                {
                   $_FILES['userfile']['name'] = $_FILES['images']['name'][$i];
                   $_FILES['userfile']['type'] = $_FILES['images']['type'][$i];
                   $_FILES['userfile']['tmp_name'] =$_FILES['images']['tmp_name'][$i];
                   $_FILES['userfile']['error'] = $_FILES['images']['error'][$i];
                   $_FILES['userfile']['size'] = $_FILES['images']['size'][$i];
                   
                   $config = array(
                       'upload_path' =>"upload", 
                       'allowed_types' => '*',
                   );
                  
                   $this->upload->initialize($config);
                   if (!$this->upload->do_upload())
                    {
                       echo "Error in uploding";exit;
                    } 
                    else
                    {
                       $image=$this->upload->data();
                       $user_file=$image['file_name'];
                       $data1=array(
                       	'hotel_id'=> $hotel_id,
                       	'images'=> $user_file
                       );
                      $this->model->insert("room_images",$data1);
                 	}
               	}


               	  
    				$data2 = array(
        						'hotel_id'=> $hotel_id,
        						'check1'=> $check1, 
        						'check2'=> $check2,
        						'check3'=> $check3,
        						'check4'=> $check4,
        						'rate1'=> $rate1,
        						'rate2'=> $rate2,
        						'rate3'=> $rate3,
        						'rate4'=> $rate4,//here I want to implode my values
    							);
    				
    			    $this->model->insert("category",$data2);


    			    	$data=array(
					 'hotel_id'=> $hotel_id,
					 'pool_name'=>$pool_name

					
	);
		$this->model->insert("pool_type",$data);


		redirect(base_url('Admin/admin_home'));
	}








	public function viewhoteldetails()
	{
		if($this->session->userdata('id')!='')
		{
		$data['details']=$this->model->view('hotel_details');
		$this->load->view('Admin/viewhoteldetails',$data);
		}
		else
		{
			redirect(base_url('Admin/login'));
		}
		
	}
	public function delhotel()
	{
		$id=$this->uri->segment(3);
		$this->model->delete('hotel_details',$id);
		redirect(base_url('Admin/viewhoteldetails'));
	}
	public function delroomimg()
	{
		$id=$this->uri->segment(3);
		$this->model->deleteroom('room_images',$id);
		redirect(base_url('Admin/viewhoteldetails'));
	}
	public function addimage()
	{
		$id=$this->uri->segment(3);
		$config['upload_path'] = './upload/';
            $config['allowed_types'] = '*';
            $config['max_size'] = '5000';
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('image'))
             {
          
                print_r($this->upload->display_errors());
              } 
              else 
              {
	            $data = $this->upload->data();
	            $img=$data['file_name'];
	            
        	  }

		$data = array(
        						'hotel_id'=> $id,
        						'images'=> $img 
        						
    							);
    				
    			    $this->model->insert("room_images",$data);
    			    redirect(base_url('Admin/viewhoteldetails'));



	}

	public function update_hotel()
	{
		$id=$this->uri->segment(3);
		$data['details']=$this->model->update('hotel_details',$id);
		$this->load->view('Admin/update_hotel',$data);
	}

	public function updatehoteldetails()
	{
	    $table="hotel_details";
		$hotel_name=$this->input->post('hotelname');
	    $location=$this->input->post('location');
		$hotel_description=$this->input->post('hotel_description');
	    // $check1=$this->input->post('check1');
	    // $check2=$this->input->post('check2');
	    // $check3=$this->input->post('check3');
	    // $check4=$this->input->post('check4');
	    // $rate1=$this->input->post('rate1');
	    // $rate2=$this->input->post('rate2');
	    // $rate3=$this->input->post('rate3');
	    // $rate4=$this->input->post('rate4');
	   
	    $id=$this->uri->segment(3);



		$config['upload_path'] = './upload/';
            $config['allowed_types'] = '*';
            $config['max_size'] = '5000';
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('image'))
             {
          
                print_r($this->upload->display_errors());
              } 
              else 
              {
	            $data = $this->upload->data();
	            $img=$data['file_name'];
	            
        	  }


        	  if ($img!="")
      	{
			$data=array(
					'hotel_name'=>$hotel_name,
					'location'=>$location,
				    'hotel_description'=>$hotel_description,
					 'hotel_image'=>$img
					
	);
			
		$this->model->updatehotel($table,$id,$data);		
		redirect(base_url('Admin/viewhoteldetails'));
	}
	else
	{
		$data=array(
					'hotel_name'=>$hotel_name,
					'location'=>$location,
				    'hotel_description'=>$hotel_description
					 
					
	);
			
		$this->model->updatehotel($table,$id,$data);		
		redirect(base_url('Admin/viewhoteldetails'));
	}
	}
	
	

		
	
































































	



	public function viewagriculture()
	{
		if($this->session->userdata('id')!='')
		{
		$data['details']=$this->model->view('agriculture');
		$this->load->view('Admin/viewagriculture',$data);
		}
		else
		{
			redirect(base_url('Admin/login'));
		}
		
	}
	public function viewmachinaries()
	{
		if($this->session->userdata('id')!='')
		{
		$data['details']=$this->model->view('machinaries');
		$this->load->view('Admin/viewma',$data);	}
		else
		{
			redirect(base_url('Admin/login'));
		}
		
	}
	public function viewbuffalos()
	{
		if($this->session->userdata('id')!='')
		{
		$data['details']=$this->model->view('buffalos');
		$this->load->view('Admin/viewbuff',$data);
		}
		else
		{
			redirect(base_url('Admin/login'));
		}
		
		
	}
	public function viewcows()
	{
		if($this->session->userdata('id')!='')
		{
		$data['details']=$this->model->view('cows');
		$this->load->view('Admin/viewcows',$data);	
	}
		else
		{
			redirect(base_url('Admin/login'));
		}
		
		
	}
	public function viewfisheries()
	{
		if($this->session->userdata('id')!='')
		{

		$data['details']=$this->model->view('fisheries');
		$this->load->view('Admin/viewfisheries',$data);	
	}
		else
		{
			redirect(base_url('Admin/login'));
		}
		
	}
	public function viewgoats()
	{
		if($this->session->userdata('id')!='')
		{
		$data['details']=$this->model->view('goats');
		$this->load->view('Admin/viewgoats',$data);	}
		else
		{
			redirect(base_url('Admin/login'));
		}
		
		
	}
	public function viewpig()
	{
		if($this->session->userdata('id')!='')
		{
		$data['details']=$this->model->view('pig');
		$this->load->view('Admin/viewpig',$data);

		}
		else
		{
			redirect(base_url('Admin/login'));
		}
		
			}
	public function viewpouitry()
	{
		if($this->session->userdata('id')!='')
		{
		$data['details']=$this->model->view('pouitry');
		$this->load->view('Admin/viewpouitry',$data);
		}
		else
		{
			redirect(base_url('Admin/login'));
		}
		

	}
	
		public function dela()
	{
		$id=$this->uri->segment(3);
		$this->model->delete('agriculture',$id);
		redirect(base_url('Admin/viewagriculture'));
	}
	public function deletebuffalos()
	{
		$id=$this->uri->segment(3);
		$this->model->delete('buffalos',$id);
		redirect(base_url('Admin/viewbuff'));
	}
		public function deletecows()
	{
		$id=$this->uri->segment(3);
		$this->model->delete('cows',$id);
		redirect(base_url('Admin/viewcows'));
	}
		public function deletefisheries()
	{
		$id=$this->uri->segment(3);
		$this->model->delete('fisheries',$id);
		redirect(base_url('Admin/viewfisheries'));
	}
		public function deletegoats()
	{
		$id=$this->uri->segment(3);
		$this->model->delete('goats',$id);
		redirect(base_url('Admin/viewgoats'));
	}
		public function deletepig()
	{
		$id=$this->uri->segment(3);
		$this->model->delete('pig',$id);
		redirect(base_url('Admin/viewpig'));
	}
		public function deletepouitry()
	{
		$id=$this->uri->segment(3);
		$this->model->delete('pouitry',$id);
		redirect(base_url('Admin/viewpouitry'));
	}
		public function deletemachinaries()
	{
		$id=$this->uri->segment(3);
		$this->model->delete('machinaries',$id);
		redirect(base_url('Admin/viewmachinaries'));
	}

	
	// public function logout()
	// {
	// 	session_destroy();
	// 	redirect('Home/index');
	// }
	
	//news
	public function addnews()
	{
		if($this->session->userdata('id')!='')
		{
		$this->load->view('Admin/addnews');
		}
		else
		{
			redirect(base_url('Admin/login'));
		}
		
	}
	public function addnewscode()
	{
		
		$qno=$this->input->post('qno');
		// $heading=$this->input->post('heading');
		$description=$this->input->post('description');
		// $config['upload_path'] = './upload/';
  //           $config['allowed_types'] = '*';
  //           $config['max_size'] = '5000';
  //           $this->load->library('upload', $config);
  //           if (!$this->upload->do_upload('image'))
  //            {
          
  //               print_r($this->upload->display_errors());
  //             } 
  //             else 
  //             {
	 //            $data = $this->upload->data();
	 //            $img=$data['file_name'];
  //       	  }
				$data=array(
					// 'heading'=>$heading,
					// 'image'=>$img,
					'description'=>$description
	);
		$this->model->insert('news',$data);
		redirect(base_url('Admin/addnews'));


	}


}