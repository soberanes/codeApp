<?php
class Controller_Index extends Controller_Abstract{
	protected $_controller = 'index';
	protected $_appId  = '159282917547481'; //Your APP ID here
	protected $_secret = 'd6448b4f47362db0a2c504342d589188'; //Your SECRET here

		
	public function indexAction(){
		/*
			Function INDEX
			Mostrar� el index de la aplicaci�n, en este caso donde se incluir� el archivo FLASH 
			Carga la vista index-index.phtml
		*/

		//Se conecta a FB API y crea el objeto $facebook
		$facebook = new Facebook(
				array('appId'  => $this->_appId,
					  'secret' => $this->_secret)
		);
		
		//Obtiene el usuario de facebook
		$user = $facebook->getUser();
		
		//Si no est� logueado muestra en enlace para iniciar sesi�n con datos de facebook
		if (!$user) {
			//Se obtiene la URL de FB API
			$url = $facebook->getLoginUrl();
			//En la URL se especifican los permisos necesarios. Previamente dados de alta en la configuraci�n de la app en Facebook
			$log = "<a href='".$url."&scope=publish_actions,user_about_me,publish_stream,status_update'>Iniciar sesi&oacute;n</a>";
			//Env�a los datos a la vista index/index
			$this->_view_vars['log'] = $log;
		}

		$this->_view_vars['user'] = $user;
	}

	public function postAction(){
		/*
			Function POST
			Env�a los datos a postear a FB API para que sean publicados en el perfil de facebook (muro) del usuario.
			Aqui podr�a cargar una vista llamada index-post.phtml o simplemente redireccionar a otro lado o no hacer nada..
			El juego puede seguir y hacer esto en backend.
		*/

		//Se crea el objeto de Facebook
		$facebook = new Facebook(
				array('appId'  => $this->_appId,
					  'secret' => $this->_secret)
		);
		
		//Se obtiene el usuario (validaci�n)
		$user = $facebook->getUser();
		
		//Si existe (est� logueado) entonces publica en el muro con la siguiente rutina.
		if($user){
			
			$titulo  = "Protarget Estrategia Promocional"; //Titulo o nombre de la aplicaci�n
			$message = $_POST["message"]; //Mensaje a publicar. (Aqu� ir�a la variable RESULTADOS DEL JUEGO) por m�todo POST o GET seg�n lo env�e ActionScript
			$imagen	 = URL."/style/images/logo_app.jpg"; //Una imagen que puede ser la de protarget o una dise�ada para el juego.
			$caption = utf8_encode("Aplicaci�n base para desarrollo"); //Un caption para la publicaci�n

			//Se construye el mensaje a publicar con los datos previos
			$mensaje = array(
					"name" 			=> $titulo,
					"link" 			=> "http://apps.facebook.com/protargetapp/",
					"caption" 		=> $caption,
					"message"		=> $message,
					"description"	=> "Esta solo es una app de Protarget",
					"picture"		=> $imagen,
			
			);

			/*
				name: 		 ser�a el titulo o nombre de la aplicaci�n
				link: 		 link de la app en apps.facebook.com
				caption:	 un breve caption del post o app
				message:	 mensaje a publicar.
				description: una breve descripci�n del post o app
				picture:	 una imagen para el post.
			*/
			
			//Se postea en el wall del usuario por medio del objeto facebook creado y el metodo post de la API
			$post = $facebook->api("/".$user."/feed", "post", $mensaje);
			
			//Comprobaci�n. Si el post fue exitoso se hace algo o no, se puede enviar un error a la vista o algo para validar.
			if($post){
				$this->_view_vars['user'] = $user;
			}
		}
	}
	

}