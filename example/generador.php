<?php 
	$options = array(
		'dataTemplate' => array(
			'pruebaV' => array(
				'type' => 'integer',				
				'length' => 10,
				'value' => 250,
			),
			'prueba' => array(
				'type' => 'text',				
				'length' => 10,
			),
			'prueba1' => array(
				'type' => 'decimal',
				'integers' => 10,
				'decimals' => 1,				
			),
		),
		'stringQuotes' => false,
		'columnSeparator' => ';',
		'rowSeparator' => '',
		'decimalSeparator' => '',		
	);
	
	$file = new File("archivo.csv", "", $options);
	$file->setData(
		array(
			array(
				"prueba1" => 123.25, 
				"prueba" => "Andres"
			)
		)
	);	
	$file->render();
	$file->save();	
	echo "$file \n";
	echo "Fichero generado ...";
?>