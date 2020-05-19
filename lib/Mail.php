<?php
class Mail {
	public static function wyslij($parametry) {
		
		global $konfiguracja;
		$mail_config = $konfiguracja['mail'];
	
		if($mail_config['can_send']) {
			
			require 'vendor/PHPMailer/PHPMailerAutoload.php';
			
			$mail = new PHPMailer;
			
			//$mail->SMTPDebug = 4;
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = $mail_config['smtp'];  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = $mail_config['email'];                 // SMTP username
			$mail->Password = $mail_config['password'];                           // SMTP password
			$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = $mail_config['smtp_port'];                                    // TCP port to connect to
			$mail->CharSet = "utf-8";
			
			$mail->setFrom($mail_config['email'], 'System HOTEL');
			if(!empty($parametry['do']['nazwa'])) {
				$mail->addAddress($parametry['do']['email'], $parametry['do']['nazwa']);     // Add a recipient
			}
			else {
				$mail->addAddress($parametry['do']['email']);     // Add a recipient
			}
			$mail->addReplyTo($mail_config['email'], 'System HOTEL');
		
			$mail->isHTML(true);                                  // Set email format to HTML
			
			$mail->Subject = $parametry['temat'];
			$mail->Body    = $parametry['wiadomosc_html'];
			$mail->AltBody = wordwrap($mail->html2text(nl2br($parametry['wiadomosc_html'])), 70);
			
			$mail->send();
		}
	}
	
	public static function wyslij_mail_potwierdz_wyslanie_kontaktu($email) {
		$wiadomosc_html = include_once 'mail_tmpl/mail_potwierdz_wyslanie_kontaktu_html.php';
		$temat = '[HOTEL BAHIASOL] Potwierdzenie wysłania wiadomości kontaktowej';	
		$parametry = array(
				'do' => array(
						'email' => $email,
				),
				'temat' => $temat,
				'wiadomosc_html' => $wiadomosc_html,
		);
		Mail::wyslij($parametry);
	}
	
	public static function wyslij_mail_odpowiedz_na_kontakt($odpowiedz) {
		$wiadomosc_html = include_once 'mail_tmpl/mail_odpowiedz_na_kontakt_html.php';
		$wiadomosc_html = str_replace('[[ODPOWIEDZ]]', $odpowiedz['kodp_tresc'], $wiadomosc_html);

		$temat = '[HOTEL BAHIASOL] Odpowiedź na wiadomość kontaktową';
		
		$kontakt_model = Model::zaladuj_model('Kontakty');
		$kontakt = $kontakt_model->pobierz_kontakt($odpowiedz['kodp_kon_id']);
		$kontakt = $kontakt[0];
		
		
		$parametry = array(
				'do' => array(
						'email' => $kontakt['kon_email'],
				),
				'temat' => $temat,
				'wiadomosc_html' => $wiadomosc_html,
		);
		Mail::wyslij($parametry);
	}
	
	public static function wyslij_mail_aktywacyjny($uzytkownik_id) {
		$wiadomosc_html = include_once 'mail_tmpl/mail_aktywacyjny_html.php';
		
		$uzytkownicy_model = Model::zaladuj_model('Uzytkownicy');
		$uzytkownik = $uzytkownicy_model->pobierz_uzytkownika($uzytkownik_id);
		$uzytkownik = $uzytkownik[0];
		
		$email = $uzytkownik['kuz_email'];
		global $konfiguracja;
		$link_aktywacyjny = $konfiguracja['base_url'].'index.php?controller=Login&action=activate&code='.$uzytkownik['kuz_kod_aktywacyjny'];
		
		$temat = '[HOTEL BAHIASOL] Aktywacja konta';
		$wiadomosc_html = str_replace('[[LINK_AKTYWACYJNY]]', $link_aktywacyjny, $wiadomosc_html);
		
		$parametry = array(
				'do' => array(
						'email' => $email,
						'nazwa' => $uzytkownik['uzy_imie'].' '.$uzytkownik['uzy_nazwisko'],
				),
				'temat' => $temat,
				'wiadomosc_html' => $wiadomosc_html,
		);
		
		Mail::wyslij($parametry);
	}
	
	public static function wyslij_mail_zmiany_hasla($konto) {
		$email = $konto['kuz_email'];
		$uzytkownik_model = Model::zaladuj_model('Uzytkownicy');
		$uzytkownik = $uzytkownik_model->pobierz_uzytkownika_na_podstawie_konta($konto['kuz_id']);
		$uzytkownik = $uzytkownik[0];
		
		$temat = "[HOTEL BAHIASOL] Resetowanie hasła";
		
		global $konfiguracja;
		$link_resetujacy = $konfiguracja['base_url'].'index.php?controller=Login&action=remind_reset&code='.$konto['kuz_kod_resetujacy_haslo'];
		
		$wiadomosc_html = include_once 'mail_tmpl\mail_zmiany_hasla_html.php';
		$wiadomosc_html = str_replace('[[LINK_RESETUJACY]]', $link_resetujacy, $wiadomosc_html);
		$wiadomosc_html = str_replace('[[EMAIL]]', $email, $wiadomosc_html);
		
		$parametry = array(
			'do' => array(
				'email' => $email,
				'nazwa' => $uzytkownik['uzy_imie'].' '.$uzytkownik['uzy_nazwisko'],
			),
			'temat' => $temat,
			'wiadomosc_html' => $wiadomosc_html,
		);
		
		Mail::wyslij($parametry);
	}
	
	public static function wyslij_mail_usuniecie_konta($email) {
		$temat = "[HOTEL BAHIASOL] Usunięcie konta";
		
		$wiadomosc_html = include_once 'mail_tmpl/mail_usuniecie_konta_html.php';
		
		$parametry = array(
				'do' => array(
					'email' => $email,
				),
				'temat' => $temat,
				'wiadomosc_html' => $wiadomosc_html,
		);
		
		Mail::wyslij($parametry);
	}
	
	public static function wyslij_mail_anulujacy_rezerwacje_pokoju($rezerwacja) {
		$pokoj_model = Model::zaladuj_model('Pokoje');
		$pokoj = $pokoj_model->pobierz_pokoj($rezerwacja['rp_pok_id']);
		$pokoj = $pokoj[0];
		
		$uzytkownik_model = Model::zaladuj_model('Uzytkownicy');
		$uzytkownik = $uzytkownik_model->pobierz_uzytkownika($rezerwacja['rp_uzy_id']);
		$uzytkownik = $uzytkownik[0];
		
		$wiadomosc_html = include_once 'mail_tmpl/mail_anulowanie_pokoj_html.php';
		$wiadomosc_html = str_replace('[[NUMER_POKOJU]]', $pokoj['pok_numer'], $wiadomosc_html);
		$wiadomosc_html = str_replace('[[DATA_REZERWACJI]]', date('Y-m-d', strtotime($rezerwacja['rp_data_dokonania_rezerwacji'])), $wiadomosc_html);
		
		$temat = '[HOTEL BAHIASOL] Anulowanie rezerwacji pokoju';
		
		$parametry = array(
				'do' => array(
						'email' => $uzytkownik['kuz_email'],
						'nazwa' => $uzytkownik['uzy_imie'].' '.$uzytkownik['uzy_nazwisko'],
				),
				'temat' => $temat,
				'wiadomosc_html' => $wiadomosc_html,
		);
		
		Mail::wyslij($parametry);
	}
	
	public static function wyslij_mail_potwierdzajacy_rezerwacje_pokoju($rezerwacja) {
		$wiadomosc_html = include_once 'mail_tmpl/mail_potwierdzajacy_rezerwacje_pokoju_html.php';
		
		$pokoj_model = Model::zaladuj_model('Pokoje');
		$pokoj = $pokoj_model->pobierz_pokoj($rezerwacja['rp_pok_id']);
		$pokoj = $pokoj[0];
		
		$grupy_model = Model::zaladuj_model('GrupyPokoi');
		$grupa = $grupy_model->pobierz_grupe_pokoi($pokoj['pok_grp_id']);
		$grupa = $grupa[0];
		
		$uzytkownicy_model = Model::zaladuj_model('Uzytkownicy');
		$uzytkownik = $uzytkownicy_model->pobierz_uzytkownika($rezerwacja['rp_uzy_id']);
		$uzytkownik = $uzytkownik[0];
		
		$wiadomosc_html = str_replace('[[NUMER_POKOJU]]', $pokoj['pok_numer'], $wiadomosc_html);
		$wiadomosc_html = str_replace('[[DATA_PRZYJAZDU]]', date('Y-m-d', strtotime($rezerwacja['rp_data_od'])), $wiadomosc_html);
		$wiadomosc_html = str_replace('[[DATA_WYJAZDU]]', date('Y-m-d', strtotime($rezerwacja['rp_data_do'])), $wiadomosc_html);
		$wiadomosc_html = str_replace('[[STANDARD_POKOJU]]', $grupa['grp_nazwa'], $wiadomosc_html);
		$wiadomosc_html = str_replace('[[CENA_ZA_POKOJ]]', $rezerwacja['rp_cena_brutto'], $wiadomosc_html);

		$temat = '[HOTEL BAHIASOL] Potwierdzenie rezerwacji pokoju';
		
		$parametry = array(
				'do' => array(
						'email' => $uzytkownik['kuz_email'],
						'nazwa' => $uzytkownik['uzy_imie'].' '.$uzytkownik['uzy_nazwisko'],
				),
				'temat' => $temat,
				'wiadomosc_html' => $wiadomosc_html,
		);
		
		Mail::wyslij($parametry);
	}
	
	public static function wyslij_mail_anulujacy_rezerwacje_stolika($rezerwacja) {
		$wiadomosc_html = include_once 'mail_tmpl/mail_anulujacy_rezerwacje_stolika_html.php';
		
		$stolik_model = Model::zaladuj_model('Stoliki');
		$stolik = $stolik_model->pobierz_stolik($rezerwacja['rs_sto_id']);
		$stolik = $stolik[0];
		
		$uzytkownicy_model = Model::zaladuj_model('Uzytkownicy');
		$uzytkownik = $uzytkownicy_model->pobierz_uzytkownika($rezerwacja['rs_uzy_id']);
		$uzytkownik = $uzytkownik[0];
		
		$wiadomosc_html = str_replace('[[NUMER_STOLIKA]]', $stolik['sto_numer'], $wiadomosc_html);
		$wiadomosc_html = str_replace('[[DATA_REZERWACJI]]', date('Y-m-d', strtotime($rezerwacja['rs_data_rezerwacji'])), $wiadomosc_html);
		
		$temat = '[HOTEL BAHIASOL] Anulowanie rezerwacji stolika';
		
		$parametry = array(
				'do' => array(
						'email' => $uzytkownik['kuz_email'],
						'nazwa' => $uzytkownik['uzy_imie'].' '.$uzytkownik['uzy_nazwisko'],
				),
				'temat' => $temat,
				'wiadomosc_html' => $wiadomosc_html,
		);
		
		Mail::wyslij($parametry);
		
	}

	public static function wyslij_mail_potwierdzajacy_rezerwacje_stolika($rezerwacja) {
		$wiadomosc_html = include_once 'mail_tmpl/mail_potwierdzajacy_rezerwacje_stolika_html.php';
		
		$stolik_model = Model::zaladuj_model('Stoliki');
		$stolik = $stolik_model->pobierz_stolik($rezerwacja['rs_sto_id']);
		$stolik = $stolik[0];
		
		$uzytkownicy_model = Model::zaladuj_model('Uzytkownicy');
		$uzytkownik = $uzytkownicy_model->pobierz_uzytkownika($rezerwacja['rs_uzy_id']);
		$uzytkownik = $uzytkownik[0];
		
		$wiadomosc_html = str_replace('[[NUMER_STOLIKA]]', $stolik['sto_numer'], $wiadomosc_html);
		$wiadomosc_html = str_replace('[[DATA_REZERWACJI]]', date('Y-m-d', strtotime($rezerwacja['rs_data_rezerwacji'])), $wiadomosc_html);
		
		$temat = '[HOTEL BAHIASOL] Potwierdzenie rezerwacji stolika';
		
		$parametry = array(
				'do' => array(
						'email' => $uzytkownik['kuz_email'],
						'nazwa' => $uzytkownik['uzy_imie'].' '.$uzytkownik['uzy_nazwisko'],
				),
				'temat' => $temat,
				'wiadomosc_html' => $wiadomosc_html,
		);
		
		Mail::wyslij($parametry);
	}
	
	public static function wyslij_mail_anulujacy_zamowienie_potrawy($zamowienie) {
		$wiadomosc_html = include_once 'mail_tmpl/mail_anulujacy_zamowienie_potrawy_html.php';
		
		$uzytkownicy_model = Model::zaladuj_model('Uzytkownicy');
		$uzytkownik = $uzytkownicy_model->pobierz_uzytkownika($zamowienie['zp_uzy_id']);
		$uzytkownik = $uzytkownik[0];
		
		$wiadomosc_html = str_replace('[[DATA_ZAMOWIENIA]]', date('Y-m-d', strtotime($zamowienie['zp_data_zamowienia'])), $wiadomosc_html);
		
		$temat = '[HOTEL BAHIASOL] Anulowanie zamówienia';
		
		$parametry = array(
				'do' => array(
						'email' => $uzytkownik['kuz_email'],
						'nazwa' => $uzytkownik['uzy_imie'].' '.$uzytkownik['uzy_nazwisko'],
				),
				'temat' => $temat,
				'wiadomosc_html' => $wiadomosc_html,
		);
		
		Mail::wyslij($parametry);
	}
	
	public static function wyslij_mail_potwierdzajacy_zamowienie_potrawy($zamowienie) {
		$wiadomosc_html = include_once 'mail_tmpl/mail_potwierdzajacy_zamowienie_potrawy_html.php';
		
		$uzytkownicy_model = Model::zaladuj_model('Uzytkownicy');
		$uzytkownik = $uzytkownicy_model->pobierz_uzytkownika($zamowienie['zp_uzy_id']);
		$uzytkownik = $uzytkownik[0];
		
		$wiadomosc_html = str_replace('[[ID_ZAMOWIENIA]]', 'POTRAWA_'.$zamowienie['zp_id'], $wiadomosc_html);
		
		$temat = '[HOTEL BAHIASOL] Potwierdzenie zamówienia';
		
		$parametry = array(
				'do' => array(
						'email' => $uzytkownik['kuz_email'],
						'nazwa' => $uzytkownik['uzy_imie'].' '.$uzytkownik['uzy_nazwisko'],
				),
				'temat' => $temat,
				'wiadomosc_html' => $wiadomosc_html,
		);
		
		Mail::wyslij($parametry);
	}
	

}