<?php
namespace Valerio;

use Valerio\Operators;

/**
*  Pila del árbol para crear el ParsedTree con la calculadora
*  Esta es una pila inteligente que analiza el valor que tiene el último elemento y si es stop y coincide con su deep, entonces esa parte la analiza (convierte a clase función)
*/
class FormulaStack
{
	private $stack;

	function __construct()
	{
		$this->stack = array();
	}

	public function push($row)
	{
		if ($row['subtype'] === 'Stop') {

			$newStack = array($row);
			do {
				$currentRow = array_pop($this->stack);
				$newStack[] = $currentRow;
			} while ($currentRow['subtype'] != 'Start');

			$this->stack[] = Operators::startFunction($newStack);
			// $this->stack[] = $newStack;
		}
		else{
			$this->stack[] = $row;
		}
	}

	public function show()
	{
		if (sizeof($this->stack) === 1) {
			return (int) $this->stack[0]['value'];
		}
		return $this->stack;
	}
}