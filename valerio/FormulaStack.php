<?php
namespace Valerio;

use Valerio\Operators;

/**
*  Pila del árbol para crear el ParsedTree con la calculadora
*/
class FormulaStack
{
	private $stack;

	function __construct()
	{
		$this->stack = array();

		// Row temporal, se puede usar para guardar un row para hacer resta y si no se ocupa se regresa
		$this->tempRow = null;
	}

	public function push($row)
	{
		// Si hay algo en temporal se analiza con el nuevo valor
		if ($this->tempRow != null) {
			// Si tempRow es una resta y row es numero, la fila nueva es el valor en negativo
			if ($this->tempRow['type'] === 'OperatorPrefix' && $this->tempRow['value'] === "-" && $row["subtype"] === "Number") {
				$row["value"] = "-".$row["value"];
			}
			// Si tempRow es una division y row es numero, la fila nueva es el valor de la división del ultimo dato en el stack con el nuevo dato
			else if($this->tempRow['type'] === 'OperatorInfix' && $this->tempRow['value'] === "/" && $row["subtype"] === "Number"){
				$row["value"] = array_pop($this->stack)['value'] / $row["value"];
			}
			else{
				// No se hizo nada, se regresa el tempRow al stack
				$this->stack[] = $this->tempRow;
			}
			// Se recetea tempRow
			$this->tempRow = null;
		}

		// Esta es una pila inteligente que analiza el valor que tiene el último elemento y si es stop y coincide con su deep, entonces esa parte la analiza (convierte a clase función)
		if ($row['subtype'] === 'Stop') {

			$newStack = array($row);
			do {
				$currentRow = array_pop($this->stack);
				$newStack[] = $currentRow;
			} while ($currentRow['subtype'] != 'Start');

			$this->stack[] = Operators::startFunction($newStack);
			// $this->stack[] = $newStack;
		}

		// Si es un signo negativo "-" se tiene que hacer una resta hacia el siguiente row
		else if($row['type'] === 'OperatorPrefix' && $row['value'] === "-"){
			// Se guarda el row para restar en el siguiente push
			$this->tempRow = $row;
		}
		else if($row['type'] === 'OperatorInfix' && $row['value'] === "/"){
			// Se guarda el row para dividir en el siguiente push
			$this->tempRow = $row;
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