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
*/
class TreeStack
{
	private $pila;

	function __construct()
	{
		$this->pila = array();
	}

	public function show()
	{
		return json_encode($this->pila);
	}

	public function add($value)
	{
		$this->pila[] = $value;
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

			$this->analyzeType($token->getTokenType());

			// printf("%-3s&nbsp&nbsp&nbsp&nbsp%-20s&nbsp&nbsp&nbsp&nbsp%-10s&nbsp&nbsp&nbsp&nbsp%-10s&nbsp&nbsp&nbsp&nbsp%-30s<br>", $i, $token->getTokenType(), $token->getTokenSubType(), $token->getValue(), str_repeat("| ", $indent) . $token->getValue());
			
			if ($token->getTokenSubType() == FormulaToken::TOKEN_SUBTYPE_START ) $indent++;
		}

		$this->result = $this->stack->show();
	}

	private function analyzeType($type)
	{
		switch ($type) {
			case 'Function':
				$this->stack->add($type);
				break;
			
			default:
				$this->stack->add("nada");
				break;
		}
	}

}


// Trabajo completo
$computo = new Compute('=IF(TRUE,10,5)');

echo $computo->getResult();







