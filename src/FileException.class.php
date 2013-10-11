<?php
	/**
	 * Definir una clase de excepción personalizada para la clase File
	 *
	 * @author Andres Aldana M
	 * @date 27/09/2013
	 */
	class FileException extends Exception
	{
		protected $result;
		
		public function __construct($result) 
		{
			$this->result = $result;

			$code = isset($result['error_code']) ? $result['error_code'] : 0;

			if(isset($result['error_description'])) 
				$msg = $result['error_description'];
			else if(isset($result['error']) && is_array($result['error'])) 
				$msg = $result['error']['message'];
			else if (isset($result['error_msg']))
				$msg = $result['error_msg'];
			else
				$msg = 'Error desconocido. verifique getResult()';
				
			parent::__construct($msg, $code);
		}
		
		public function getResult()
		{
			return $this->result;
		}
		
		public function getType() 
		{
			if (isset($this->result['error'])) 
			{
				$error = $this->result['error'];
				
				if (is_string($error)) 
					return $error;
				else if (is_array($error)) 
				{
					if ( isset($error['type']) ) 
					  return $error['type'];					
				}
			}

			return 'Exception';
		}
		
		public function __toString() 
		{
			$str = $this->getType() . ': ';
			
			if ($this->code != 0)
			  $str .= $this->code . ': ';
			  
			return $str . $this->message;
		}
	}
?>