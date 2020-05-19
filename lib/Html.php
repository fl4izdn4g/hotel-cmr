<?php
require_once 'lib/Session.php';

class Html  {
	public static function zrob_opcje_do_select($elementy, $wartosc_pusta, $aktualny_element) {
		echo '<option value="">'.$wartosc_pusta.'</option>';
		
		if(!empty($elementy)) {
			$elementy_klucze = array_keys($elementy);
			$zaznaczony_element = '';
			if(!empty($aktualny_element)) {
				$zaznaczony_element = $aktualny_element;
			}
			
			foreach ($elementy as $klucz => $element) {
				$zaznaczony = ($klucz == $zaznaczony_element) ? 'selected="selected"' : '';
		
				echo '<option value="'.$klucz.'" '.$zaznaczony.'>'.$element.'</option>';
			}
		}
	}
	
	public static function czy_jest_blad($blad) {
		return !empty($blad) ? 'has-error': '';
	}
	
	public static function wypisz_bledy($blad) {
		if(!empty($blad)) {
			foreach ($blad as $b) {
				echo '<span class="error-message">'.$b.'</span>';
			}
		}
	}
	
	public static function zrob_element_formularza($typ, $parametry) {// $nazwa, $id, $name, $aktualna_wartosc, $blad) {
		$id = $parametry['id'];
		$nazwa = $parametry['nazwa'];
		$name = $parametry['name'];
		$blad = $parametry['blad'];
		$aktualna_wartosc = $parametry['aktualna_wartosc'];
		
		$wysokosc_pola = isset($parametry['wysokosc_pola']) ? $parametry['wysokosc_pola'] : null;
		
		$readonly = isset($parametry['readonly']) ? $parametry['readonly'] : null;
		
		$readonly_attribute = '';
		if(!empty($readonly)) {
			$readonly_attribute = 'readonly="readonly"';
		}
		
		$has_error = Html::czy_jest_blad($blad);
		
		if($typ == 'text') {
			echo '<div class="form-group '.$has_error.'">';
			echo '<label for="'.$id.'">'.$nazwa.'</label>';
			echo '<input name="'.$name.'" type="text" class="form-control" id="'.$id.'" value="'.$aktualna_wartosc.'" '.$readonly_attribute.'>';
			Html::wypisz_bledy($blad);
			echo '</div>';
		}
		
		if($typ == 'password') {
			echo '<div class="form-group '.$has_error.'">';
			echo '<label for="'.$id.'">'.$nazwa.'</label>';
			echo '<input name="'.$name.'" type="password" class="form-control" id="'.$id.'" value="'.$aktualna_wartosc.'" '.$readonly_attribute.'>';
			Html::wypisz_bledy($blad);
			echo '</div>';
		}
		
		if($typ == 'html-textarea') {
			echo '<div class="form-group '.$has_error.'">';
			echo '<label for="'.$id.'">'.$nazwa.'</label>';
			
			$disabled_attr = '';
			if(!empty($readonly)) {
				$disabled_attr = ' disabled="disabled" ';
			}
			
			echo '<textarea name="'.$name.'" type="text" class="form-control" id="'.$id.'" '.$readonly_attribute.' '.$disabled_attr.'>'.$aktualna_wartosc.'</textarea>';
			Html::wypisz_bledy($blad);			
			
			
			$toolbar = "{
				'image': false,
				'blockquote':false,
				'size': 'xs'
			}";
			
			$disable_editor = '';
			if(!empty($readonly)) {
				$toolbar = "{
					'blockquote': false,
				    'color': false,
				    'emphasis': false,
				    'font-styles': false,
				    'html': false,
				    'image': false,
				    'link': false,
				    'lists': false,
					'size': 'xs'
				}";
			}
			
			$script = "<script>
				var editor = jQuery('#".$id."').wysihtml5({
					toolbar: ".$toolbar."
				});
				".$disable_editor."
			</script>";
			View::zalacz_blok('scripts',$script);
			
			echo '</div>';
		}
		
		if($typ == 'textarea') {
			$wysokosc_pola_attr = '';
			if(!empty($wysokosc_pola)) {
				$wysokosc_pola_attr = ' style="height: '.$wysokosc_pola.'" ';
			}
			
			echo '<div class="form-group '.$has_error.'">';
			echo '<label for="'.$id.'">'.$nazwa.'</label>';
			echo '<textarea name="'.$name.'" type="text" class="form-control" id="'.$id.'" '.$readonly_attribute.$wysokosc_pola_attr.'>'.$aktualna_wartosc.'</textarea>';
			Html::wypisz_bledy($blad);
			echo '</div>';
		}
		
		if($typ == 'hidden') {
			echo '<input name="'.$name.'" type="hidden" class="form-control" id="'.$id.'" value="'.$aktualna_wartosc.'">';
		}
		
		
		if($typ == 'checkbox') {
			$checked_attribute = '';
			if(!empty($aktualna_wartosc)) {
				$checked_attribute = 'checked="checked"';
			}
						
			echo '<div class="form-group '.$has_error.'">';
			echo '<label for="'.$id.'"><input type="checkbox" name="'.$name.'" id="'.$id.'" '.$readonly_attribute.' '.$checked_attribute.' /> '.$nazwa.'</label>';
			Html::wypisz_bledy($blad);
			echo '</div>';
		}
		
		if($typ == 'datepicker') {
			echo '<div class="form-group '.$has_error.'">';
			echo '<label for="'.$id.'">'.$nazwa.'</label>';
			echo '<div class="input-group date">';
			echo 	'<div class="input-group-addon">';
			echo 		'<i class="fa fa-calendar"></i>';
			echo 	'</div>';
			echo 	'<input name="'.$name.'" type="text" class="form-control" id="'.$id.'" '.$readonly_attribute.' value="'.$aktualna_wartosc.'" />';
			echo '</div>';		
			Html::wypisz_bledy($blad);
				
			$script = "<script>jQuery('#".$id."').datepicker({language:'en', format: 'yyyy-mm-dd', clearBtn: true, autoclose: true, todayHighlight: true});</script>";
			View::zalacz_blok('scripts',$script);
				
			echo '</div>';
		}
		
		if($typ == 'telephone') {
			echo '<div class="form-group '.$has_error.'">';
			echo '<label for="'.$id.'">'.$nazwa.'</label>';
			echo '<div class="input-group date">';
			echo 	'<div class="input-group-addon">';
			echo 		'<i class="fa fa-phone"></i>';
			echo 	'</div>';
			echo 	'<input name="'.$name.'" type="text" class="form-control" id="'.$id.'" '.$readonly_attribute.' value="'.$aktualna_wartosc.'" />';
			echo '</div>';
			Html::wypisz_bledy($blad);
			
			$script = "<script>jQuery('#".$id."').inputmask('+99 999 999 999', {placeholder: '+__ ___ ___ ___', });</script>";
			View::zalacz_blok('scripts',$script);
			
			echo '</div>';
		}
		
		if($typ == 'upload') {
			$kontroler = $parametry['kontroler'];
			
			echo '<div class="form-group '.$has_error.'">';
			echo '<label for="'.$id.'">'.$nazwa.'</label>';
			echo '<input name="'.$name.'" type="hidden" class="form-control" id="'.$id.'" value="'.$aktualna_wartosc.'">';
			
			if(empty($aktualna_wartosc)) {
				$src = '';
				$display_attr = '';
			}
			else {
				$src = ' src="'.Router::poprawny_url_obrazka($aktualna_wartosc, $kontroler, 'medium').'" ';
				$display_attr = 'style="display: none"';
			}
			

			echo  '<div style="background: #fafafa">';
			echo 	'<div class="row" style="padding-top:0px;">';
			echo 		'<div id="dragbox" class="upload-area col-md-11">';
			echo 			'<div style="padding-top: 10px;"><img id="uploadedImage" '.$src.' class="img-responsive" style="margin: 0 auto" /></div>';
			echo			'<div id="upload-area" '.$display_attr.' >';
			echo				'<span class="glyphicon glyphicon-file" style="font-size: 60px; display: block; margin-bottom: 5px"></span>';
			echo				'<span style="display: block; text-align: center;">przeciągnij i opuść pliki tutaj<br/><span style="font-size: 18px">lub</span></span>';
			echo			'</div>';
			echo		'</div>';
			echo 	'</div><!-- .row -->';
			
			echo 	'<div class="row" style="padding:10px 0; margin: 0; text-align:center">';
			echo		'<button id="uploadButton" type="button" class="btn btn-large btn-primary" >Wybierz plik</button>';
			echo 	'</div><!-- .row -->';
			echo '</div>';
			
			echo '<div class="row" style="padding-top:10px;">';
			echo 	'<div id="progressOuter" class="progress progress-striped active" style="display:none;">';
			echo 		'<div id="progress" class="progress-bar progress-bar-success"  role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>';
			echo		'<div id="loaderImg"></div>';
			echo 	'</div>';
			echo '</div><!-- .row -->';
			
			echo '<div class="row" style="padding-top:10px;">';
			echo 	'<div class="col-xs-10">';
			echo 		'<div id="msgBox"></div>';
			echo 	'</div>';
			echo '</div><!-- .row -->';
			
			Html::wypisz_bledy($blad);
			echo '</div>';
			
			$script = '<script>';
			$script .= 'window.onload = function() {';
			global $konfiguracja;
			$group = $konfiguracja['upload_groups'][$kontroler];
			
			$script .= "var uploader = new ss.SimpleUpload({
				button: 'uploadButton',
				dropzone: 'dragbox',
				url: 'index.php?controller=Ajax&action=upload&group=".$group."', 
				name: 'uploadfile',
				progressUrl: 'index.php?controller=Ajax&action=progress', // enables cross-browser progress support (more info below)
				multipart: true,
				responseType: 'json',
				allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
				maxSize: 2*1024, // kilobytes
				onSubmit: function() {
		            jQuery('#msgBox').html('');
		        },
		        onComplete: function( filename, response ) {
		            progressOuter.style.display = 'none'; // hide progress bar when upload is completed
		            if ( !response ) {
		                msgBox.innerHTML = 'Nie można załadować pliku';
		                return;
		            }
		            if ( response.success === true ) {
					   jQuery('#uploadedImage').attr('src', response.file_medium);
					   jQuery('#upload-area').hide();
					   jQuery('#".$id."').val(response.file);
		            } else {
		                if (response.msg)  {
		                    msgBox.innerHTML = escapeTags( response.msg );
		                } else {
		                    msgBox.innerHTML = 'Wystąpił bład i zapis pliku nie powiódł się.';
		                }
		            }
		          },
		          onError: function( filename, type, status, statusText, response, uploadBtn, size ) {
					progressOuter.style.display = 'none';
		            msgBox.innerHTML = 'Nie udało się załadować pliku';
		          }
			});";
			$script .= "};";
			
			$script .= '</script>';
			
			View::zalacz_blok('scripts', $script);
		}
		
	}
	
	public static function zrob_element_formularza_select($parametry) {
		$nazwa = $parametry['nazwa'];
		$id = $parametry['id'];
		$name = $parametry['name'];
		$wartosc_pusta = $parametry['wartosc_pusta'];
		$wartosci = $parametry['wartosci'];
		$aktualna_wartosc = $parametry['aktualna_wartosc'];
		$blad = $parametry['blad'];
		$readonly = isset($parametry['readonly']) ? $parametry['readonly'] : null;
		
		$readonly_attr = '';
		if(!empty($readonly)) {
			$readonly_attr = 'disabled="disabled"';
		}
		
		$czy_select2 = $parametry['czy_select2'];
		
		
		$has_error = !empty($blad) ? 'has-error': '';
		echo '<div class="form-group '.$has_error.'">';
		echo '<label for="'.$id.'">'.$nazwa.'</label>';
		echo '<select name="'.$name.'" class="form-control" id="'.$id.'" '.$readonly_attr.'>';
		Html::zrob_opcje_do_select($wartosci, $wartosc_pusta, $aktualna_wartosc);
		echo '</select>';
		if(!empty($blad)) {
			foreach ($blad as $b) {
				echo '<span class="error-message">'.$b.'</span>';
			}
		}
		echo '</div>';
		
		if($czy_select2) {
			echo '<input type="hidden" id="'.$id.'_restore" value="'.$aktualna_wartosc.'" />';		
			
			$script = '<script>';
			$script .= 'jQuery(function() {';
  			$script .= 		'jQuery("#'.$id.'").select2();';
			$script .= '});';			
			$script .= '</script>';
			
			View::zalacz_blok('scripts', $script);
		}
	}
	
	public static function zrob_element_menu($link, $opcje) {
		$active_attr = '';
		if(isset($_GET['controller']) && $_GET['controller'] == $link['controller']) {
			$active_attr = ' class="active" ';
		}
		echo '<li '.$active_attr.' ><a href="'.Router::utworz_link($link).'" >'.$opcje['nazwa'].'</a></li>';
	}
	
	public static function dodaj_alert($typ, $wiadomosc) {
		Session::ustaw_alert($typ, $wiadomosc);
	}
	
	public static function pokaz_alerty() {
		$alerty = Session::pobierz_wszystkie_alerty();
		
		if(isset($alerty['error'])) {
			foreach ($alerty['error'] as $error) {
				Html::zbuduj_alert('alert-danger', $error);
			}
		}
		
		if(isset($alerty['warning'])) {
			foreach ($alerty['warning'] as $error) {
				Html::zbuduj_alert('alert-warning', $error);
			}
		}
		
		if(isset($alerty['info'])) {
			foreach ($alerty['info'] as $error) {
				Html::zbuduj_alert('alert-info', $error);
			}
		}
		
		if(isset($alerty['success'])) {
			foreach ($alerty['success'] as $error) {
				Html::zbuduj_alert('alert-success', $error);
			}
		}
	}
		
 	public static function zbuduj_alert($typ, $wiadomosc) {
 		echo '<div class="alert '.$typ.' alert-dismissible">';
 		echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
 		echo $wiadomosc;
 		echo '</div>';
 	}
 	
 	
 	public static function pokaz_ile_czasu_minelo($data_od) {
 		$current_timestamp = strtotime(date('Y-m-d H:i:s'));
 		$data_od_timestamp = strtotime($data_od);
 		
 		$obecna_data = new DateTime();
		$data = new DateTime($data_od);
		$roznica = $obecna_data->diff($data);
		
		//var_dump($roznica);
		////die;
		
		if($roznica->y >0) {
			return $roznica->y.' lat';
		}
		
		if($roznica->m > 0) {
			return $roznica->m.' mies.';
		}
		
		if($roznica->d > 0) {
			return $roznica->d.' dni';
		}
		
		if($roznica->h > 0) {
			return $roznica->h.' godz.';
		}
		
		if($roznica->i > 0) {
			return $roznica->i.' min.';
		}
		
		if($roznica->s > 0) {
			return $roznica->s.' sek.';
		}
		
		return 'teraz';
 	}
}