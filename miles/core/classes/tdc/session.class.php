<?php	
	class Session {
		private static $sessionName;		
		public static function get($indice = null){
			if ($indice != null){
				return isset($_SESSION[$indice])?$_SESSION[$indice]:'';
			}
			if (function_exists("Mencache")){
				$memcache = new Memcache;
				return $memcache->get(session_id());
			}else{
				$memcache = new stdClass;
				$memcache->autenticado = false;
				if (!isset($_SESSION)){
					session_name(self::getName());
					session_start();					
				}
				foreach ($_SESSION as $key =>$valor){
					$memcache->{$key} =  $valor;
				}
				return $memcache;
			}		
		}
		public static function set($sesion){
			if (function_exists("Mencache")){
				$memcache = new Memcache;
				$memcache->set(session_id(), $valor) or die ("Failed to save data at the server");
			}else{
				if (!isset($_SESSION)){
					session_name(self::getName());
					session_start();
				}
				foreach ($sesion as $key =>$valor){
					$_SESSION[$key] =  $valor;
				}
			}
		}
		public static function del(){
			if (function_exists("Mencache")){
				$memcache = new Memcache;
				$memcache->delete(session_id());
			}else{
				if (session_name() != self::getName()) session_name(self::getName());
				foreach($_SESSION as $key => $valor){
					unset($_SESSION[$key]);
				}
				// removendo todas as sessões
				@session_start();
				session_destroy();
				unset( $_SESSION );
			}
		}
		public static function append($key,$valor=""){
			$_SESSION[$key] = $valor;
		}
		public static function setName($name){
			self::$sessionName = $name;
		}
		public static function getName(){
			return self::$sessionName;
		}
	}