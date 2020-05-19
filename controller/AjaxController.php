<?php
class AjaxController {
	
	public function upload() {
		$this->not_viewable = true;
		$group_name = $_GET['group'];
		
		require(dirname(dirname(__FILE__)).'/vendor/Uploader.php');
		require(dirname(dirname(__FILE__)).'/vendor/ImageResize.php');
		

		$upload_dir = dirname(dirname(__FILE__)).'/static/upload/'.$group_name.'/';
		$upload_dir_to_return = 'static/upload/'.$group_name.'/';
		
		if(!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		
		
		$valid_extensions = array('gif', 'png', 'jpeg', 'jpg');
		
		$Upload = new FileUpload('uploadfile');
		
		$new_filename = md5($Upload->getFileName().date('yyyy-dd-mm H:i:s'));		
		$ext = $Upload->getExtension(); 
		$Upload->newFileName = $new_filename.'.'.$ext;
		
		$result = $Upload->handleUpload($upload_dir, $valid_extensions);
		
		if ($result) {
			$path = $Upload->getSavedFile();
			$imgsize = getimagesize($path);
			
			global $konfiguracja;
			$image_resizer = new \Eventviva\ImageResize($path);
			$image_resizer->resizeToHeight($konfiguracja['image_height']['small'])
						  ->save($upload_dir.'/'.$new_filename.'_small.'.$ext);
			if($group_name == 'Galerie') {
				$image_resizer->resizeToHeight($konfiguracja['image_height']['gallery-medium'])
						  	  ->save($upload_dir.'/'.$new_filename.'_medium.'.$ext);
			}
			else {
				$image_resizer->resizeToHeight($konfiguracja['image_height']['medium'])
							  ->save($upload_dir.'/'.$new_filename.'_medium.'.$ext);					}
		}		
		
		
		if (!$result) {
		    exit(json_encode(array('success' => false, 'msg' => $Upload->getErrorMsg())));   
		} else {
		    echo json_encode(array('success' => true, 
		    					   'file' => $new_filename.'.'.$ext,
		    					   'file_medium' => $upload_dir_to_return.$new_filename.'_medium.'.$ext,
		    )); die;
		}
				
	}
	
	
	public function progress() {
		$this->not_viewable = true;
		/**
		 * Simple Ajax Uploader
		 * Version 2.5.1
		 * https://github.com/LPology/Simple-Ajax-Uploader
		 *
		 * Copyright 2012-2016 LPology, LLC
		 * Released under the MIT license
		 *
		 * Returns upload progress updates for browsers that don't support the HTML5 File API.
		 * Falling back to this method allows for upload progress support across virtually all browsers.
		 *
		 */
		
		// This "if" statement is only necessary for CORS uploads -- if you're
		// only doing same-domain uploads then you can delete it if you want
		if (isset($_SERVER['HTTP_ORIGIN'])) {
			header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
			header('Access-Control-Allow-Credentials: true');
			header('Access-Control-Max-Age: 86400');    // cache for 1 day
		}
		
		if (isset($_REQUEST['progresskey'])) {
			$status = apc_fetch('upload_'.$_REQUEST['progresskey']);
		} else {
			exit(json_encode(array('success' => false)));
		}
		
		$pct = 0;
		$size = 0;
		
		if (is_array($status)) {
		
			if (array_key_exists('total', $status) && array_key_exists('current', $status)) {
		
				if ($status['total'] > 0) {
					$pct = round(($status['current'] / $status['total']) * 100);
					$size = round($status['total'] / 1024);
				}
			}
		}
		
		echo json_encode(array('success' => true, 'pct' => $pct, 'size' => $size));
	}
	
	
	public function kontakty_liczba_nieprzeczytanych() {
		$this->not_viewable = true;
	}
	
	public function kontakty_nieprzeczytane() {
		$this->not_viewable = true;
	}
	
	public function rezerwacje_pokoje() {
		$this->not_viewable = true;
		
		$grupa_id = $_GET['group_id'];
		$data_od = $_GET['from'];
		$data_do = $_GET['to'];
		
		if(!empty($grupa_id)) {
			$rezerwacje_model = Model::zaladuj_model('Rezerwacje');
			
			if(!empty($data_od) && !empty($data_do)) {
				$result = $rezerwacje_model->pobierz_pokoje_na_podstawie_rezerwacji($grupa_id, $data_od, $data_do);
			}
			else {
				$result = $rezerwacje_model->pobierz_pokoje_na_podstawie_grupy($grupa_id);
			}
				
			$pokoje = array();
			foreach ($result as $r) {
				$element = array(
					'id' => $r['pok_id'],
					'text' => $r['pok_numer'].' (Piętro: '.$r['pok_pietro'].')'
				);
				$pokoje[] = $element;
			}
			
			//var_dump($pokoje);
			
			echo json_encode($pokoje); die;
		}
		else {
			echo json_encode(null); die;
		}
	}
	
	public function rezerwacje_informacje_o_grupie() {
		$this->not_viewable = true;
		
		$grupa_id = $_GET['group_id'];
		if(!empty($grupa_id)) {
			$sql = "SELECT * FROM hotel_grupy_pokoi WHERE grp_id = ?";
			$parametry = array($grupa_id);
			$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
			
			$netto = $result[0]['grp_cena_netto'];
			
			global $konfiguracja;
			$podatek = $konfiguracja['tax']['pokoj'];
				
			$brutto = ($netto * 23/100) + $netto;
						
			$cena = array(
				'netto' => $netto,
				'podatek' => $podatek,
				'brutto' => $brutto
			);
					
			$informacje = array(
				'nazwa' => $result[0]['grp_nazwa'],
				'opis' => $result[0]['grp_opis'],
				'ikona' => Router::poprawny_url_obrazka($result[0]['grp_ikona'], 'GrupyPokoi','medium'),
				'cena' => $cena
			);
						
			echo json_encode($informacje); die;
		}
		else {
			echo json_encode(null); die;
		}
	}
	
	public function rezerwacje_dostepnosc_pokoi() { 
		$this->not_viewable = true;
		
		$grupa_id = $_GET['group_id'];
		$od = $_GET['from'];
		$do = $_GET['to'];
		if(!empty($grupa_id) && !empty($od) && !empty($do)) {
			$rezerwacje_model = Model::zaladuj_model('Rezerwacje');
			$result = $rezerwacje_model->pobierz_pokoje_na_podstawie_rezerwacji($grupa_id, $od, $do);
			
			$ile = count($result);
			
			echo json_encode($ile); die;
		}
		else {
			echo json_encode(null); die;
		}
	
	}
	
	public function rezerwacje_informacje_o_sali() {
		$this->not_viewable = true;
	
		$sala_id = $_GET['sala_id'];
		if(!empty($sala_id)) {
			$sql = "SELECT * FROM hotel_sale_restauracyjne WHERE sar_id = ?";
			$parametry = array($sala_id);
			$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
						
			$informacje = array(
					'nazwa' => $result[0]['sar_nazwa'],
					'opis' => $result[0]['sar_opis'],
					'ikona' => Router::poprawny_url_obrazka($result[0]['sar_zdjecie'], 'SaleRestauracyjne','medium'),
			);
	
			echo json_encode($informacje); die;
		}
		else {
			echo json_encode(null); die;
		}
	}
	
	public function rezerwacje_dostepnosc_stolikow() {
		$this->not_viewable = true;
	
		$sala_id = $_GET['sala_id'];
		$data = $_GET['data'];
		if(!empty($sala_id) && !empty($data)) {
			$rezerwacje_model = Model::zaladuj_model('RezerwacjeStolikow');
			$result = $rezerwacje_model->pobierz_stoliki_na_podstawie_rezerwacji($sala_id, $data);
				
			$ile = count($result);
				
			echo json_encode($ile); die;
		}
		else {
			echo json_encode(null); die;
		}
	
	}
}