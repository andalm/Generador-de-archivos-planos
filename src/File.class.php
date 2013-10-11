<?php 
	/***
	 * Generador de archivos de texto plano
	 * @author Andres Aldana M
	 * @date 27/09/2013
	 * @version 0.0.1
	 **/
		 
	class File
	{
		/**
		 * Plantilla para generar el archivo plano,
		 * opciones por defecto
		 */
		protected $template = array(
			'dataTemplate' => array(),
			'columnSeparator' => ',',
			'rowSeparator' => ',',
			'decimalSeparator' => '',
			'stringQuotes' => true
		);
		
		/**
		 * Nombre y extension del archivo a generar
		 */	 
		protected $name;
		
		/**
		 * Ruta a donde se va a guardar el archivo
		 */
		protected $path;
		
		/**
		 * String que representa el contenido del archivo 
		 */
		protected $text;
		
		/**
		 * Datos que se van a imprimir en el archivo tipo array(); 
		 */
		protected $data = array("");
		
		/**
		 * Contructor de la clase, por defecto están las propiedades: 
		 * Nombre del fichero (file.txt), Ruta del fichero y una plantilla vacia
		 */		
		function __construct($name = "file.txt", $path = "", $template = array())
		{
			try
			{
				//Validación de opciones de plantilla
				if( !self::_validateTemplate($template, $this->template) )
					throw new FileException(array(
						"error_msg" => "Propiedad de plantilla no definida"
					));
					
				$this->name = !empty($name) ? $name : "file.txt";
				$this->path = ($path == "" || empty($path)) ? 
							  realpath(dirname(__FILE__)) . "/../" : $path;
				
				$this->template = array_merge($this->template, $template);				
			}
			catch(FileException $e)
			{
				echo $e;
				exit;
			}
		}
		
		/**
		 * Obtener el nombre del fichero
		 */
		public function getName()
		{
			return $this->name;
		}
		
		/**
		 * Establecer el nombre del fichero
		 */
		public function setName($name = "file.txt")
		{
			try
			{
				//validar el tipo de dato
				if( !is_string($name) )
					throw new FileException(array(
						"error_msg" => "Propiedad, nombre de archivo no valida"
				));
					
				$this->name = !empty($name) ? $name : "file.txt";			
			}
			catch(FileException $e)
			{
				echo $e;
				exit;
			}		
		}	
		
		/**
		 * Obtener la ruta del fichero
		 */
		public function getPath()
		{
			return $this->path;
		}
		
		/**
		 * Establecer la ruta del fichero, si el argumento 
		 * se pasa vacio, lo establece automaticamente
		 */
		public function setPath($path = "")
		{
			try
			{
				//validar el tipo de dato
				if( !is_string($path) )
					throw new FileException(array(
						"error_msg" => "Propiedad, path no valida"
				));
					
				$this->path = $this->path = ($path == "" || empty($path)) ? 
				                        realpath(dirname(__FILE__)) . "/../" : $path;	
			}
			catch(FileException $e)
			{
				echo $e;
				exit;
			}		
		}
		
		/**
		 * Establece una nueva plantilla
		 */
		public function setTemplate($template = array())
		{
			try
			{
				//Validación de opciones de plantilla
				if( !self::_validateTemplate($template, $this->template) )
					throw new FileException(array(
						"error_msg" => "Propiedad de plantilla no definida"
					));
				
				$this->template = array_merge($this->template, $template);
			}
			catch(FileException $e)
			{
				echo $e;
				exit;
			}			
		}
		
		/**
		 * Obtiene la plantilla
		 */
		public function getTemplate()
		{
			return $this->template;
		}
		
		/**
		 * Establece los datos que se quieren guardar en el archivo
		 */
		public function setData($data = array())
		{
			try
			{
				if(!is_array($data))
					throw new FileException(array(
						"error_msg" => "Formato de datos incorrecto, solo se acepta de tipo array()"
					));			
				
				$this->data = $data;
			}
			catch(FileException $e)
			{
				echo $e;
				exit;
			}	
		}
		
		/**
		 * Obtiene los datos que se quieren guardar en el archivo
		 */
		public function getData()
		{
			return $this->data;		
		}
		
		/**
		 * Metodo publico para crear cadena que se va guardar en el fichero
		 */
		public function render()
		{
			$this->text = self::_concatText($this->data, $this->template);
		}
		 
		/**
		 * Metodo auxiliar que concatena el texto, con los formatos requeridos de los datos,
		 * el texto es lo que se imprimrá en el archivo plano.
		 *
		 * @param $data los datos a formatear e imprimir en el archivo
		 * @param $template Formato y orden de los datos que se imprimrán
		 *
		 * @return Devuelve el texto que se ha formateado
		 */
		protected static function _concatText($data, $template)
		{
			$text = '';
			
			try
			{	
				foreach($data as $dat)
				{
					foreach($template['dataTemplate'] as $key => $option)
					{
						if(isset($dat[$key])  || !empty($option['value']))
						{
							$auxDat = !empty($option['value']) ? $option['value'] : $dat[$key];
							if(strtolower($option['type']) == 'integer')
							{
								$default = isset($option['default']) ? $option['default'] : "0";
								
								$text .= self::_formatNumberFile($auxDat, $default, 
																 $option['length'], 0, $template['decimalSeparator']);
							}
							else if(strtolower($option['type']) == 'decimal')
							{
								$default = isset($option['default']) ? $option['default'] : "0";
								$integers = isset($option['integers']) ? $option['integers'] : 0;
								$decimals = isset($option['decimals']) ? $option['decimals'] : 0;
								
								$text .= self::_formatNumberFile($auxDat, $default, $integers, 
																 $decimals, $template['decimalSeparator']);
							}
							else if(strtolower($option['type']) == 'text')
							{
								$default = isset($option['default']) ? $option['default'] : " ";
								$text .= self::_formatTextFile($auxDat, $default , 
															   $option['length'], $template['stringQuotes']);
							}
							else if(strtolower($option['type']) == 'date')
							{
								$auxDate = new DateTime($auxDat);
								$text .= $auxDate->format($option['format']);
							}
							
							$text .= $template['columnSeparator'];							
						}
					}
					
					$text = trim($text, $template['columnSeparator']);
					$text .= $template['rowSeparator']. "\r\n";
				}
				
					
				return $text;
			}
			catch(FileException $e)
			{
				echo $e;
				exit;
			}
		}
		
		/**
		 * Metodo auxiliar para definir el formato de las cadenas de texto 
		 * 
		 * @param $txt Texto que se quiere formatear
		 * @valueDefault valor por defecto del campo de texto,
		 * inicialmente se encuentra desactivado
		 * @param $length tamaño de cadena deseado
		 * @param $quotes Define si se activan las comillas o no que 
		 * encierran las cadenas de texto
		 *
		 * @return Texto con el formato deseado
		 */
		protected static function _formatTextFile($txt = "", $valueDefault = " ", $length, $quotes)
		{
			$length = ($quotes) ? $length - 2 : $length;
			
			if(strlen($txt) > $length)
				$txt = substr($txt, 0, $length);
			else
				$txt = str_pad($txt, $length, $valueDefault, STR_PAD_RIGHT);
			
			return ($quotes) ? "\"$txt\"" : $txt;
		}
		
		/**
		 * Metodo auxiliar para definir el formato de las números
		 * 
		 * @param $number Núnero que se desea formatear
		 * @valueDefault valor por defecto del campo de numero,
		 * inicialmente se encuentra en valor 0
		 * @param $integers Cantidad de enteros deseado
		 * @param $decimals Cantidad de decimales deseado
		 * encierran las cadenas de texto
		 * @param $decimalSeparator separador decimal deseado
		 *
		 * @return Número con el formato requerido
		 */		
		protected static function _formatNumberFile($number = 0, $valueDefault = "0", $integers, $decimals, $decimalSeparator = "")
		{
			if($decimalSeparator != "")
			{
				return number_format($number, $decimals, $decimalSeparator, '');
			}
			else
			{
				$number = explode('.', $number . "");
				
				if(strlen($number[0]) > $integers)
					$auxInt = substr($number[0], 0, $integers);
				else
					$auxInt = str_pad($number[0], $integers, $valueDefault, STR_PAD_LEFT);
				
				if(!empty($number[1]))
				{
					if(strlen($number[1]) > $decimals)
						$auxDec = substr($number[1], 0, $decimals);					
					else
						$auxDec = str_pad($number[1], $decimals, $valueDefault, STR_PAD_LEFT);
				}
				else
					$auxDec = str_pad('', $decimals, $valueDefault, STR_PAD_LEFT);
				
				return $auxInt . $auxDec;
			}
		}
		
		/**
		 * Crea o sobreescribe el fichero con la ruta, nombre, extension 
		 * formato y contenido seleccionados.
		 */
		public function save()
		{
			try
			{
				$fp = fopen($this->path . "/" . $this->name, 'w');
				
				if(!$fp)
					throw new FileException(array(
						"error_msg" => "Error al crear el archivo, por favor verifique la ruta o nombre"
					));
					
				fwrite($fp, $this->text);			
				fclose($fp);
			}
			catch(FileException $e)
			{
				echo $e;
				exit;
			}
		}
		
		/**
		 * Evalua que el formato del template seleccionado sea correcto
		 *
		 * @param $optionsTemplate Template seleccionado por el usuario
		 * @param $defaultTemplate Template por defecto
		 *
		 * @return Retorna verdadero si el formato esta correcto o falso en
		 * caso contrario
		 */
		protected static function _validateTemplate($optionsTemplate, $defaultTemplate)
		{
			foreach($optionsTemplate as $key => $option)
			{
				if(!isset($defaultTemplate[$key]))
					return false;
			}
			
			return true;
		}
		
		/**
		 * Imprime el contenido del archivo en pantalla
		 *
		 * @return Texto que representa el contenido del archivo
		 */
		public function __toString()
		{
			return $this->text;
		}
	}
?>