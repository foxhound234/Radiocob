<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	function __construct() {
		parent::__construct();
		if(!($this->session->profil=='A'))
		{
		 redirect('Visiteur/ConnexionAdmin','Refresh');
		}
	}

	public function Accueil()
	{
		$DonneesInjectees['nbinfojour']=$this->ModeleInfos->getnbinfojour();
		$DonneesInjectees['titredelapage']='Accueil';
		$this->afficher('Admin/AccueilAdmin',$DonneesInjectees);
	}
	public function Deconnexion()
	{
		session_destroy();
	  	redirect('/Visiteur/Accueil', 'refresh');
	}
	public function AjouterEvenement()
	{
		if($this->input->post('btnEvenement'))
		{
		 
			$Donneesevenement=array(
			  'titre'=>$this->input->post('txtTitre'),
			 'description'=>$this->input->post('txtDescription'),
			 'periode'=>$this->input->post('txtPeriode'),
			 'position'=>1,
			  'debut'=>$this->input->post('txtDateDebut'),
			  'fin'=>$this->input->post('txtDateFin')
			);

			$Evenements=$this->ModeleEvenement->AjouterEvenement($Donneesevenement);
		  
			redirect('/Admin/Accueil', 'refresh');	
				
		}
	}

	public function Ajouterinfolocal()
	{
		$config['upload_path'] = './assets/son';
		$config['allowed_types'] ='gif|jpg|png|jpeg|mp3';
		$config['max_size'] = 250000;
		$this->load->library('upload', $config);  # Load upload library
		$this->upload->initialize($config);
				
		if (!$this->upload->do_upload($field = 'userfile')) {
			$status                     = 'error';
			$msg                        = $this->upload->display_errors('', '');
			echo $msg;
		} else {
			
        $data = $this->upload->data();
		}
						
	}
	public function test()
	{
			$config['upload_path']          = './assets/images';
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 0 ;
			$config['max_width']            = 0 ;
			$config['max_height']           = 0 ;

			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload('userfile'))
			{
					$error =  $this->upload->display_errors();

					echo $error;
			}
			else
			{
					$data = $this->upload->data();
             $this->load->view('upload_success', $data);
				
			}	 
	}
	public function Ajouterjeux()
	{
		if($this->input->post('btnJeux'))
		{
			$config['upload_path']          = './assets/images';
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 100000;
			$config['max_width']            = 2500;
			$config['max_height']           = 2500;
         
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload('txtImages'))
			{
					$error =  $this->upload->display_errors();

					print_r($error);
			}
			else
			{
					$data = $this->upload->data();
					$Donneesjeux=array(
						'intitule'=>$this->input->post('txtIntitule'),
					   'description'=>$this->input->post('txtDescription'),
					   'fonctionnement'=>$this->input->post('txtFonctionnement'),
					   'image'=>$data['file_name'],
						'debut'=>$this->input->post('txtDateDebut'),
						'fin'=>$this->input->post('txtDateFin'),
						'interruption' => 1
					  );
					  $this->ModeleJeux->AjouterJeux($Donneesjeux);
					redirect('/Admin/Accueil', 'refresh');
			}
		}
	}
	public function ModifierLesEvenements()
	{
		$DonneesInjectees['titredelapage']='Modifier les Evenement';
		$DonneesInjectees['LesEvenements']=$this->ModeleEvenement->GetLesEvenements();
		$this->afficher('Admin/ModifierEvenements',$DonneesInjectees);
	}
	public function ModifierUnEvenement($id)
	{
		$DonneesInjectees['titredelapage']='Modifier les Evenement';
		$DonneesInjectees['LesEvenements']=$this->ModeleEvenement->GetLesEvenements($id);

	}
	private function afficher($page,$DonneesInjectees)
	{
		$this->load->view('Templates/HeaderAdmin',$DonneesInjectees);
		$this->load->view($page,$DonneesInjectees);
		$this->load->view('Templates/pieddepage');
	}
}
