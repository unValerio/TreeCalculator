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
		// $function = $stack[sizeof($stack)-1]['value'];
		$function = array_pop($stack)['value'];

		// Qué hacer si es IF
		if ($function === 'IF') {

			$result = 0;

			$newStack = array();
			$row = array_pop($stack);
			while ($row['subtype'] != 'Union') {
				$newStack[] = $row;
				$row = array_pop($stack);
			}

			// Si hay una condición tipo "SI" = "SI"
			if (sizeof($newStack) === 3) {
				$logical = 'FALSE';
				switch ($newStack[1]['value']) {
					case '=': // igual que
						if ($newStack[0]['value'] === $newStack[2]['value']) {
							$logical = 'TRUE';
						}
						break;
					case '<>': // diferente de
						if ($newStack[0]['value'] != $newStack[2]['value']) {
							$logical = 'TRUE';
						}
						break;
					default:
						break;
				}
				$stack[] = ''; //Para que el arreglo sea del mismo tamaño que la condición tipo TRUE
			}
			else{
				// $logical = $stack[sizeof($stack)-2]['value'];
				$logical = $newStack[0]['value'];
			}

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