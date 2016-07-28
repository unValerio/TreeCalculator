<?php
namespace Valerio;

/**
*   Operadores
*/
class Operators
{
	// Ahorita sólo funciona para IF(TRUE,5,10)
	public function startFunction($stack)
	{
		// Obtener el tipo de función
		$function = $stack[sizeof($stack)-1]['value'];

		// Qué hacer si es IF
		if ($function === 'IF') {

			$result = 0;

			$logical = $stack[sizeof($stack)-2]['value'];

			if ($logical === 'TRUE') {
				$result = $stack[3]['value'];
			}
			elseif($logical === 'FALSE'){
				$result = $stack[1]['value'];
			}

			return array(
				'index' => $stack[sizeof($stack)-1]['index'].'-'.$stack[0]['index'],
				'type' => 'Operand',
				'subtype' => 'Number',
				'value' => $result,
				'deep' => $stack[0]['deep']
			);
		}

		return $stack;
	}
}