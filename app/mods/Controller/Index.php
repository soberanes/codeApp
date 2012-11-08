<?php
class Controller_Index extends Controller_Abstract{
	protected $_controller = 'index';
	protected $_appId  = '159282917547481'; //Your APP ID here
	protected $_secret = 'd6448b4f47362db0a2c504342d589188'; //Your SECRET here

		
	public function indexAction(){
		/*
			Function INDEX
			Mostrará el index de la aplicación, en este caso donde se incluirá el archivo FLASH 
			Carga la vista index-index.phtml
		*/

		//Se conecta a FB API y crea el objeto $facebook
		$facebook = new Facebook(
				array('appId'  => $this->_appId,
					  'secret' => $this->_secret)
		);
		
		//Obtiene el usuario de facebook
		$user = $facebook->getUser();
		
		//Si no está logueado muestra en enlace para iniciar sesión con datos de facebook
		if (!$user) {
			//Se obtiene la URL de FB API
			$url = $facebook->getLoginUrl();
			//En la URL se especifican los permisos necesarios. Previamente dados de alta en la configuración de la app en Facebook
			$log = "<a href='".$url."&scope=publish_actions,user_about_me,publish_stream,status_update'>Iniciar sesi&oacute;n</a>";
			//Envía los datos a la vista index/index
			$this->_view_vars['log'] = $log;
		}

		$this->_view_vars['user'] = $user;
	}

	public function postAction(){
		/*
			Function POST
			Envía los datos a postear a FB API para que sean publicados en el perfil de facebook (muro) del usuario.
			Aqui podría cargar una vista llamada index-post.phtml o simplemente redireccionar a otro lado o no hacer nada..
			El juego puede seguir y hacer esto en backend.
		*/

		//Se crea el objeto de Facebook
		$facebook = new Facebook(
				array('appId'  => $this->_appId,
					  'secret' => $this->_secret)
		);
		
		//Se obtiene el usuario (validación)
		$user = $facebook->getUser();
		
		//Si existe (está logueado) entonces publica en el muro con la siguiente rutina.
		if($user){
			
			$titulo  = "Protarget Estrategia Promocional"; //Titulo o nombre de la aplicación
			$message = $_POST["message"]; //Mensaje a publicar. (Aquí iría la variable RESULTADOS DEL JUEGO) por método POST o GET según lo envíe ActionScript
			$imagen	 = URL."/style/images/logo_app.jpg"; //Una imagen que puede ser la de protarget o una diseñada para el juego.
			$caption = utf8_encode("Aplicación base para desarrollo"); //Un caption para la publicación

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
				name: 		 sería el titulo o nombre de la aplicación
				link: 		 link de la app en apps.facebook.com
				caption:	 un breve caption del post o app
				message:	 mensaje a publicar.
				description: una breve descripción del post o app
				picture:	 una imagen para el post.
			*/
			
			//Se postea en el wall del usuario por medio del objeto facebook creado y el metodo post de la API
			$post = $facebook->api("/".$user."/feed", "post", $mensaje);
			
			//Comprobación. Si el post fue exitoso se hace algo o no, se puede enviar un error a la vista o algo para validar.
			if($post){
				$this->_view_vars['user'] = $user;
			}
		}
	}
	

}