<?php
require_once 'Router.php';

class Application {
	
	private $router;
	
	
	
	public function __construct($router) {
		$this->router = $router;
	}
	
	public function run() {
		try {
			$request = $this->router->extract();
			
			$controller_request = $request['controller'];
			$action_request = $request['action'];
			
	 		require_once 'controller/'.$controller_request.'Controller.php';
			
			$controller_name = $request['controller'].'Controller';
			$controller = new $controller_name();
			
			$model_path = 'model/'.$controller_request.'.php';
			
			if(file_exists($model_path)) {
				require_once $model_path;
				$model = new $controller_request();
					
				$controller->model = $model;
			}
			
			if(method_exists($controller, $action_request)) {
				if(Security::nie_wymaga_logowania(Security::KONTROLER_TYPE, $controller_name)) {
					$controller->$action_request();
				} else if(Security::czy_zalogowany()) {
					$user = Session::pobierz_zalogownego_uzytkownika();
					if($user['role_code'] == 'ADMINISTRATOR_GLOWNY') {
						$controller->$action_request();
					}
					else {
						if(Security::ma_dostep(Security::KONTROLER_TYPE, $controller_name) ) {
							$controller->$action_request();
						}
						else {
							Security::przekierowanie_do_home();
						}
					}
				}
				else {
					Security::przekierowanie_do_logowania();
				}
			}
			else {
				require_once 'view/500_error.php'; die;
			}
			
					
			if(!isset($controller->not_viewable)) {
				$view_path = 'view/'.$controller_request.'/'.$action_request.'.php';
				if(!file_exists($view_path)) {
					require_once 'view/404_error.php';
				}
				else {
					if(!empty($controller->without_template)) {
						require_once 'view/head_layout.php';
						require_once $view_path;
						require_once 'view/foot_layout.php';
					}
					else {
						require_once 'view/header_layout.php';
						require_once $view_path;
						require_once 'view/footer_layout.php';
					}
				}
			}
		}
		catch (Exception $ex) {
			var_dump($ex); die;
			require_once 'view/500_error.php'; die;
		}
	}
}
