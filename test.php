<?php

require __DIR__ . '/vendor/autoload.php';

use Valerio\FormulaToken;
use Valerio\FormulaParser;

/**
*   Fórmula de excel parcial, puede ser "IF", etc.
*/
class ExcelFunction
{
	public $name;
	public $content;
}

/**
*   Función IF
*/
class IfFunction extends ExcelFunction
{
	
}

/**
*   Arbol de fórmula completo
*/
class ParsedTree
{
	public $tree;


}

/**
*  Pila del árbol para crear el ParsedTree con la calculadora
*  Esta es una pila inteligente que analiza el valor que tiene el último elemento y si es stop y coincide con su deep, entonces esa parte la analiza (convierte a clase función)
*/
class TreeStack
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

			$this->stack[] = array_reverse($newStack);
		}
		else{
			$this->stack[] = $row;
		}
	}

	public function show()
	{
		return $this->stack;
	}

}

/**
*   Cómputo de Fórmulas
*/
class Compute
{
	private $stack;

	private $result;

	function __construct($formula)
	{
		$this->stack = new TreeStack();
		$formulaParser = new FormulaParser($formula);
		$this->TreeCreator($formulaParser);
	}

	public function getResult()
	{
		return $this->result;
	}

	public function TreeCreator(Valerio\FormulaParser $parsedTree)
	{
		$indent = 1;
		$token = null;

		// $formula = $parsedTree->getFormula();

		// Crear árbol
		for ($i = 0; $i < $parsedTree->getTokenCount(); $i++) {
			$token = $parsedTree->getToken($i);
			
			if ($token->getTokenSubType() == FormulaToken::TOKEN_SUBTYPE_STOP ) $indent--;

			$row = array(
				'index' => $i,
				'type' => $token->getTokenType(),
				'subtype' => $token->getTokenSubType(),
				'value' => $token->getValue(),
				'deep' => $indent
			);
			$this->stack->push($row);

			//printf("%-3s&nbsp&nbsp&nbsp&nbsp%-20s&nbsp&nbsp&nbsp&nbsp%-10s&nbsp&nbsp&nbsp&nbsp%-10s&nbsp&nbsp&nbsp&nbsp%-30s<br>", $i, $token->getTokenType(), $token->getTokenSubType(), $token->getValue(), str_repeat("| ", $indent) . $token->getValue());
			
			if ($token->getTokenSubType() == FormulaToken::TOKEN_SUBTYPE_START ) $indent++;
		}

		$this->result = $this->stack->show();
	}

}


// Trabajo completo
$formula = '=IF(TRUE,IF(FALSE,3,2),5)';

$computo = new Compute($formula);

echo json_encode(array('formula' => $formula, 'computo' => $computo->getResult()));







