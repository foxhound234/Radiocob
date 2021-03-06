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
			$info=$this->ModeleInfos->getIdJour();
			if($this->ModeleInfos->getnbinfojour() == 0 )
			{
				$DonneesInjectees['nbinfojour']=$this->ModeleInfos->getnbinfojour();
			}else{

				$DonneesInjectees['LesInfoslocal']=$this->ModeleInfos->GetInfoLocal($info->id);
				$DonneesInjectees['nbinfojour']=$this->ModeleInfos->GetnbTxtlocal($info->id);
			}
		$DonneesInjectees['titredelapage']='Accueil';
		$DonneesInjectees['LesEmissions']=$this->ModeleEmission->RetournerEmission();
		$DonneesInjectees['LesEmissionsAssignée']=$this->ModeleEmission->RetournerLesEmissions();
		$DonneesInjectees['LesAnimateurs']=$this->ModeleAnimateur->RetournerAnimateur(); 
		$DonneesInjectees['LesEvenements']=$this->ModeleEvenement->GetLesEvenements();
		$DonneesInjectees['LesJeux']=$this->ModeleJeux->GetLesJeux();
		$DonneesInjectees['LesPartenaires']=$this->ModelePartenaires->getLespartenaires();
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
			$config['upload_path']          = './assets/images';
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 0 ;
			$config['max_width']            = 0 ;
			$config['max_height']           = 0 ;
			 
			$this->upload->initialize($config);
			$nomfichier=$_FILES['txtImages']['name'];
			$dossier='assets/images/';
           
		   if(file_exists($dossier.$nomfichier)||$nomfichier='')
		   {
			$Donneesevenement=array(
				'titre'=>$this->input->post('txtTitre'),
			   'description'=>$this->input->post('txtDescription'),
			   'periode'=>$this->input->post('txtPeriode'),
			   'position'=>1,
				'debut'=>$this->input->post('txtDateDebut'),
				'fin'=>$this->input->post('txtDateFin'),
				'images'=>$nomfichier
			  );
  
			  $Evenements=$this->ModeleEvenement->AjouterEvenement($Donneesevenement);
			  redirect('/Admin/Accueil', 'refresh');
		   }
		   else{

			if ( ! $this->upload->do_upload('txtImages'))
			{
					$error =  $this->upload->display_errors();
	
					echo $error;
			}
			else
			{
				$data = $this->upload->data();
				$Donneesevenement=array(
					'titre'=>$this->input->post('txtTitre'),
				   'description'=>$this->input->post('txtDescription'),
				   'periode'=>$this->input->post('txtPeriode'),
				   'position'=>1,
					'debut'=>$this->input->post('txtDateDebut'),
					'fin'=>$this->input->post('txtDateFin'),
					'images'=>$nomfichier
				  );
				  $Evenements=$this->ModeleEvenement->AjouterEvenement($Donneesevenement);
				  redirect('/Admin/Accueil', 'refresh');
			}
			
		   }	
		}
	}

	public function Ajouterinfolocal()
	{
	 if($this->input->post('btnInfo'))
	 {
		$date = date('Y-m-d H:i:s');
		$Donneesinfo=array(
			'son'=>$this->input->post('txtLien'),
		   'date'=>$date
		  );
		  $this->ModeleInfos->AjouterInfo($Donneesinfo);
		  redirect('/Admin/Accueil', 'refresh');
	 }
		
	 if($this->input->post('btntxtInfo'))
	 {
		 $info=$this->ModeleInfos->getIdJour();
		 $Donnestxtinfo=array(
			 'info-locale'=>$info->id,
			'titre'=>$this->input->post('txtTitre'),
			'information'=>$this->input->post('txtInfo')
		 );
		$this->ModeleInfos->AjoutertxtInfo($Donnestxtinfo);
		redirect('/Admin/Accueil', 'refresh');
	 }
	}
	public function AjouterEmission()
	{
		$config['upload_path']          = './assets/images';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['max_size']             = 0 ;
		$config['max_width']            = 0 ;
		$config['max_height']           = 0 ;
         
		$this->upload->initialize($config);
		$nomfichier=$_FILES['txtImages']['name']; 
		$dossier='/assets/images/';
		
			if(file_exists($dossier.$nomfichier)|| $nomfichier=='')
			{
				$DonneesEmissions=array(
					'titre'=>$this->input->post('txtTitre'),
				   'description'=>$this->input->post('txtDescription'),
				   'image'=>$nomfichier
				  );
				  $this->ModeleEmission->AjouterEmission($DonneesEmissions);
				  redirect('/Admin/Accueil', 'refresh');
			}
			else
			{
				if ( ! $this->upload->do_upload('txtImages'))
				{
						$error =  $this->upload->display_errors();
		
						echo $error;
				}
				else
				{
					$data = $this->upload->data();
					$DonneesEmissions=array(
						'titre'=>$this->input->post('txtTitre'),
					   'description'=>$this->input->post('txtDescription'),
					   'image'=>$data['file_name']
					  );
					  $this->ModeleEmission->AjouterEmission($DonneesEmissions);
					  redirect('/Admin/Accueil', 'refresh');
				}
					 
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
			$nomfichier=$_FILES['txtImages']['name']; 
			$dossier='/assets/images/';
				if(file_exists($dossier.$nomfichier)||$nomfichier=='')
				{
						$DonneesJeux=array(
								'Intitule'=>$this->input->post('txtIntitule'),
							   'description'=>$this->input->post('txtDescription'),
							   'fonctionnement'=>$this->input->post('txtFonctionnement'),
							   'image'=>$nomfichier,
							   'debut'=>$this->input->post('txtDateDebut'),
							   'fin'=>$this->input->post('txtDateFin')
							  );
							  $this->ModeleJeux->AjouterJeux($DonneesJeux);
							redirect('/Admin/Accueil', 'refresh');
				}
				else{
					if ( ! $this->upload->do_upload('txtImages'))
					{
							$error =  $this->upload->display_errors();
		
							print_r($error);
					}
					else
					{
							$data = $this->upload->data();
							$DonneesJeux=array(
								'Intitule'=>$this->input->post('txtIntitule'),
							   'description'=>$this->input->post('txtDescription'),
							   'fonctionnement'=>$this->input->post('txtFonctionnement'),
							   'image'=>$data['file_name'],
							   'debut'=>$this->input->post('txtDateDebut'),
							   'fin'=>$this->input->post('txtDateFin')
							  );
							  $this->ModeleJeux->AjouterJeux($DonneesJeux);
							redirect('/Admin/Accueil', 'refresh');
					}	
				}	
		}
	}

	public function Ajouteranimation()
	{
		if($this->input->post('btnAnimations'))
		{
			$DonneesEmissions=array(
				'emission'=>$this->input->post('txtnoEmission'),
			   'animateur'=>$this->input->post('txtnoAnimateurs')
			  );
			  $this->ModeleAnimation->AjouterUneAnimations($DonneesEmissions);
		}
	}

	public function Ajouteranimateur()
	{
		if($this->input->post('btnAnimateur'))
		{
			$config['upload_path']          = './assets/images';
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 100000;
			$config['max_width']            = 2500;
			$config['max_height']           = 2500;
			$this->upload->initialize($config);
			$nomfichier=$_FILES['txtImages']['name']; 
			$dossier='/assets/images/';
			if(file_exists($dossier.$nomfichier)||$nomfichier=='')
			{
				$DonneesAnimateurs=array(
					'id'=>$this->input->post('txtnom'),
					'nom'=>$this->input->post('txtnom'),
					'presentation'=>$this->input->post('txtPresentations'),
					'photo'=> $nomfichier,
					'site'=>$this->input->post('txtsite'));
				$this->ModeleAnimateur->AjoutAnimateur($DonneesAnimateurs);		
			}
			else{
				if ( ! $this->upload->do_upload('txtImages'))
				{
						$error =  $this->upload->display_errors();
	
						print_r($error);
				}
				else
				{
						$data = $this->upload->data();
						$DonneesAnimateurs=array(
							'id'=>$this->input->post('txtnom'),
							'nom'=>$this->input->post('txtnom'),
							'presentation'=>$this->input->post('txtPresentations'),
							'photo'=> $nomfichier,
							'site'=>$this->input->post('txtsite'));

							$this->ModeleAnimateur->AjoutAnimateur($DonneesAnimateurs);	
						redirect('/Admin/Accueil', 'refresh');
				}	
			}	
		}
	}


	public function AjouterPartenaire()
	{

		if($this->input->post('btnPartenaires'))
		{
			$config['upload_path']          = './assets/images';
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 100000;
			$config['max_width']            = 2500;
			$config['max_height']           = 2500;
			$this->upload->initialize($config);
			$nomfichier=$_FILES['txtLogo']['name']; 
			$dossier='/assets/images/';
			if(file_exists($dossier.$nomfichier)||$nomfichier=='')
			{
				$DonneesPartenaire=array(
					'nom'=>$this->input->post('txtNom'),
					'description'=>$this->input->post('txtDescription'),
					'logo'=> $nomfichier,
					'site'=>$this->input->post('txtSite'),
					'debut'=>$this->input->post('txtDateDebut'),
					'fin'=>$this->input->post('txtDateFin'),
					'type'=>$this->input->post('txtType'),
					'position'=>14
				);
				$this->ModelePartenaires->AjouterPartenaire($DonneesPartenaire);
				redirect('/Admin/Accueil', 'refresh');
			}
			else{
				if ( ! $this->upload->do_upload('txtLogo'))
				{
						$error =  $this->upload->display_errors();
	
						print_r($error);
				}
				else
				{
						$data = $this->upload->data();
						$DonneesPartenaire=array(
							'nom'=>$this->input->post('txtnom'),
							'description'=>$this->input->post('txtDescription'),
							'logo'=> $nomfichier,
							'site'=>$this->input->post('txtSite'),
							'debut'=>$this->input->post('txtDateDebut'),
							'fin'=>$this->input->post('txtDateFin'),
							'type'=>$this->input->post('txtType'),
							'position'=>14
						);
							

						$this->ModelePartenaires->AjouterPartenaire($DonneesPartenaire);
						redirect('/Admin/Accueil', 'refresh');
				}	
			}	
		}


	}
	public function ModifierAnimateurs()
	{
		if($this->input->post('btnAnimateur'))
		{
			$nomfichier=$_FILES['txtImages']['name']; 
			$dossier='/assets/images/';
			$id=$this->input->post('txtnoAnimateurs');
           
				  if($nomfichier=='')
				 {
					$DonneesAnimateurs=array(
						'nom'=>$this->input->post('txtnom'),
						'presentation'=>$this->input->post('txtPresentations'),
						'site'=>$this->input->post('txtsite'),
					   );
				    $this->ModeleAnimateur->ModifierAnimateur($DonneesAnimateurs,$id);  		
					redirect('/Admin/Accueil', 'refresh');
				 }

				 if(file_exists($dossier.$nomfichier))
				 {
					$DonneesAnimateurs=array(
						'nom'=>$this->input->post('txtnom'),
						'presentation'=>$this->input->post('txtPresentations'),
						'site'=>$this->input->post('txtsite'),
						'photo'=>$nomfichier
					   );
						$this->ModeleAnimateur->ModifierAnimateur($DonneesAnimateurs,$id);  
						redirect('/Admin/Accueil', 'refresh');		
				 }
				 else{
					 if ( ! $this->upload->do_upload('txtImages'))
					 {
							 $error = $this->upload->display_errors();
		 
							 print_r($error);
					 }
					 else
					 {
							 $data = $this->upload->data();
							 $DonneesAnimateurs=array(
								'nom'=>$this->input->post('txtnom'),
								'presentation'=>$this->input->post('txtPresentations'),
								'site'=>$this->input->post('txtsite'),
								'photo'=>$nomfichier
							   );
						    $this->ModeleAnimateur->ModifierAnimateur($DonneesAnimateurs,$id); 
							redirect('/Admin/Accueil', 'refresh');
					 }	
				 }	


		}

	}
	public function ModifierEmission()
	{
		if($this->input->post('btnEmission'))
		{
			$nomfichier=$_FILES['txtImages']['name']; 
			$dossier='/assets/images/';
			$id=$this->input->post('txtnoEmission');
           
				  if($nomfichier=='')
				 {
					$DonneesEmissions=array(
						'titre'=>$this->input->post('txtTitre'),
						'description'=>$this->input->post('txtDescription'),
					   );
				    $this->ModeleEmission->ModifierEmission($DonneesEmissions,$id);  		
					redirect('/Admin/Accueil', 'refresh');
				 }




				 if(file_exists($dossier.$nomfichier))
				 {
					$DonneesEmissions=array(
						'titre'=>$this->input->post('txtTitre'),
						'description'=>$this->input->post('txtDescription')
					   );
						$this->ModeleEmission->ModifierEmission($DonneesEmissions,$id);  		
				 }
				 else{
					 if ( ! $this->upload->do_upload('txtImages'))
					 {
							 $error =  $this->upload->display_errors();
		 
							 print_r($error);
					 }
					 else
					 {
							 $data = $this->upload->data();
							 $DonneesEmissions=array(
								'titre'=>$this->input->post('txtTitre'),
								'description'=>$this->input->post('txtDescription'),
								'image'=>$nomfichier
							   );
								$this->ModeleEmission->ModifierEmission($DonneesEmissions,$id);  		
							 redirect('/Admin/Accueil', 'refresh');
					 }	
				 }	


		}
	}
	public function ModifierEvenement()
	{
		if($this->input->post('btnEvenement'))
		{
			$config['upload_path']          = './assets/images';
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 0 ;
			$config['max_width']            = 0 ;
			$config['max_height']           = 0 ;
			 
			$this->upload->initialize($config);
			$nomfichier=$_FILES['txtImages']['name'];
			$dossier='assets/images/';
			$id=$this->input->post('txtnoEvenement');
			if(file_exists($dossier.$nomfichier))
			{
				$Donneesevenement=array(
					'titre'=>$this->input->post('txtTitre'),
				   'description'=>$this->input->post('txtDescription'),
				   'periode'=>$this->input->post('txtPeriode'),
				   'position'=>1,
					'debut'=>$this->input->post('txtDateDebut'),
					'fin'=>$this->input->post('txtDateFin'),
					'images'=>$nomfichier
				  );
				  $this->ModeleEvenement->ModifierUnEvenement($Donneesevenement,$id);
				  redirect('/Admin/Accueil', 'refresh');
			}
			else{
				if ( ! $this->upload->do_upload('txtImages'))
				{
						$error =  $this->upload->display_errors();
	
						print_r($error);
				}
				else
				{
						$data = $this->upload->data();
						$Donneesevenement=array(
							'titre'=>$this->input->post('txtTitre'),
						   'description'=>$this->input->post('txtDescription'),
						   'periode'=>$this->input->post('txtPeriode'),
						   'position'=>1,
							'debut'=>$this->input->post('txtDateDebut'),
							'fin'=>$this->input->post('txtDateFin'),
							'images'=>$nomfichier
						  );
						  $this->ModeleEvenement->ModifierUnEvenement($Donneesevenement,$id);	
						redirect('/Admin/Accueil', 'refresh');
				}	
			}	

			 if($nomfichier=="")
			 {
				$Donneesevenement=array(
					'titre'=>$this->input->post('txtTitre'),
				   'description'=>$this->input->post('txtDescription'),
				   'periode'=>$this->input->post('txtPeriode'),
				   'position'=>1,
					'debut'=>$this->input->post('txtDateDebut'),
					'fin'=>$this->input->post('txtDateFin')
				  );
				  $this->ModeleEvenement->ModifierUnEvenement($Donneesevenement,$id);
				  redirect('/Admin/Accueil', 'refresh');
			 }

		}	
	}

	public function ModifierJeux()
	{
		if($this->input->post('btnJeux'))
		{
			$id=$this->input->post('txtnoJeux');
			
			$config['upload_path']          = './assets/images';
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 100000;
			$config['max_width']            = 2500;
			$config['max_height']           = 2500;
			$this->upload->initialize($config);

            $nomfichier=$_FILES['txtImages']['name']; 
			$dossier='/assets/images/';
			  if($nomfichier=='')
			  {
				$DonneesJeux=array(
					'intitule'=>$this->input->post('txtIntitule'),
				   'description'=>$this->input->post('txtDescription'),
				   'fonctionnement'=>$this->input->post('txtFonctionnement'),
					'debut'=>$this->input->post('txtDateDebut'),
					'fin'=>$this->input->post('txtDateFin'),
					'interruption'=>$this->input->post('txtInterruption')
				  );
				
				 $this->ModeleJeux->ModifierUnJeux($DonneesJeux,$id);  		
				 redirect('/Admin/Accueil', 'refresh');
			  }




			  if(file_exists($dossier.$nomfichier))
			  {
				$DonneesJeux=array(
					'intitule'=>$this->input->post('txtIntitule'),
				   'description'=>$this->input->post('txtDescription'),
				   'fonctionnement'=>$this->input->post('txtFonctionnement'),
				   'image'=>$nomfichier,
					'debut'=>$this->input->post('txtDateDebut'),
					'fin'=>$this->input->post('txtDateFin'),
					'interruption'=>$this->input->post('txtInterruption')
				  );
				
					
					 redirect('/Admin/Accueil', 'refresh');	
			  }
			  else{
				  if (!$this->upload->do_upload('txtImages'))
				  {
						  $error =  $this->upload->display_errors();
	  
						  print_r($error);
				  }
				  else
				  {
						  $data = $this->upload->data();
						  $DonneesJeux=array(
							'intitule'=>$this->input->post('txtIntitule'),
						   'description'=>$this->input->post('txtDescription'),
						   'fonctionnement'=>$this->input->post('txtFonctionnement'),
						   'image'=>$nomfichier,
							'debut'=>$this->input->post('txtDateDebut'),
							'fin'=>$this->input->post('txtDateFin'),
							'interruption'=>$this->input->post('txtInterruption')
						  );
						
							 $this->ModeleJeux->ModifierUnJeux($DonneesJeux,$id);
							 
						  redirect('/Admin/Accueil', 'refresh');
				  }	


		}	
	}
}
	public function SupprimerJeux()
	{
		if($this->input->post('btnSupjeux')){
			$id=$this->input->post('txtid');
			$this->ModeleJeux->SupprimerJeux($id); 
			redirect('/Admin/Accueil', 'refresh');  
		}
	}
	public function SupprimerPartenaire()
	{
		if($this->input->post('btnSupPartenaire'))
		{
			$id=$this->input->post('txtidPartenaire');
			$this->ModelePartenaires->SupprimerPartenaire($id); 
			redirect('/Admin/Accueil', 'refresh'); 
		}
	}
	public function SupprimerEmission()
	{
		if($this->input->post('btnSupEmission'))
		{
			$id=$this->input->post("txtidEmission");
			$this->ModeleEmission->SupprimerEmission($id);
			redirect('/Admin/Accueil', 'refresh');
		}
	}
	public function SupprimerEvenement()
	{
		if($this->input->post('btnSupEvenement'))
		{
			$id=$this->input->post("txtidEvenement");
			$this->ModeleEvenement->SupprimerEvenement($id);
			redirect('/Admin/Accueil', 'refresh');
		}
	}
	
	public function SuppressionAnimateur()
	{
		if($this->input->post('btnSupAnimateur'))
		{
			$id=$this->input->post("txtidAnimateur");
			$this->ModeleAnimateur->SupprimerAnimateur($id);
			redirect('/Admin/Accueil', 'refresh');
		}
	}
	public function AjouterInterview()
	{
		if($this->input->post('btnInterview'))
		{
			$config['upload_path']          = './assets/images';
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 100000;
			$config['max_width']            = 2500;
			$config['max_height']           = 2500;
			$this->upload->initialize($config);
			
			$nomfichier=$_FILES['txtImages']['name']; 
			$dossier='/assets/images/';
			if(file_exists($dossier.$nomfichier))
			{
				$DonneesInterview=array(
					'Titre'=>$this->input->post('txtTitre'),
					'Description'=>$this->input->post('txtDescription'),
					'image'=>$nomfichier,
					'audio'=>$this->input->post('txtAudio'),
					'Datedebut'=>$this->input->post('txtDateDebut'),
					'Datefin'=>$this->input->post('txtDateFin')
				);

				$this->Modeleinterview->AjouterInterview($DonneesInterview);
				redirect('/Admin/Accueil', 'refresh');

			} else{

				if (!$this->upload->do_upload('txtImages'))
				{
						$error =  $this->upload->display_errors();
	
						print_r($error);
				}
				else
				{
					$DonneesInterview=array(
					'Titre'=>$this->input->post('txtTitre'),
					'Description'=>$this->input->post('txtDescription'),
					'image'=>$nomfichier,
					'audio'=>$this->input->post('txtAudio'),
					'Datedebut'=>$this->input->post('txtDateDebut'),
					'Datefin'=>$this->input->post('txtDateFin')
				);

						 $this->Modeleinterview->AjouterInterview($DonneesInterview);
						 
						redirect('/Admin/Accueil', 'refresh');
				}	
			}   

		}
	}

	public function AfficheAnimateurs($id)
	{
			
			$data = $this->ModeleAnimateur->getUnAnimateur($id);
			echo json_encode($data);	
	}
	public function AffichePartenaire($id)
	{
			$data = $this->ModelePartenaires->getUnPartenaire($id);
			echo json_encode($data);	
	}
	public function AfficheEmission($id)
	{
		$data = $this->ModeleEmission->RetournerEmission($id);
		echo json_encode($data);
	}
	public function AfficheEvenement($id)
	{
		$data=$this->ModeleEvenement->getUnEvenement($id);
		echo json_encode($data);
	}
	public function AfficheJeux($id)
	{
		$data=$this->ModeleJeux->getUnJeux($id);
		echo json_encode($data); 
	}
	public function AfficheInfo($id)
	{
		$data=$this->ModeleInfos->InfoLocal($id);
		echo json_encode($data); 
	}
	public function ModifierTxtLocal()
	{
		if($this->input->post('btnInfoModif'))
		{
			$id=$this->input->post('txtnoInfo');

			$DonneesInfo=array(
				'titre'=>$this->input->post('txtTitre'),
			   'information'=>$this->input->post('txtDescription'),
			  );
			  $this->ModeleInfos->ModifieInfo($DonneesInfo,$id);
			  redirect('/Admin/Accueil', 'refresh');
		}
   }
   public function SuppressionInformation(){
	if($this->input->post('btnSupInfo'))
	{
		$id=$this->input->post('txtidInfo');

		$this->ModeleInfos->SuppressionInfo($id);
			  redirect('/Admin/Accueil', 'refresh');
	}

   }
	private function afficher($page,$DonneesInjectees)
	{
		$this->load->view('Templates/HeaderAdmin',$DonneesInjectees);
		$this->load->view($page,$DonneesInjectees);
		$this->load->view('Templates/pieddepage');
	}
}
