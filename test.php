<?php

require __DIR__ . '/vendor/autoload.php';

use Valerio\FormulaToken;
use Valerio\FormulaParser;

/**
*   Operadores
*/
class Operators
{
	// Ahorita sólo funciona para IF(TRUE,5,10)
	public function startFunction($stack)
	{
		// Obtener el tipo de función
		$function = array_pop($stack)['value'];

		// Qué hacer si es IF
		if ($function === 'IF') {

			$result = 0;

			$logical = array_pop($stack)['value'];

			if ($logical === 'TRUE') {
				//echo '<p style="color:red">'.$stack[3]['value'].'</p>';
				$result = $stack[3]['value'];
			}
			elseif($logical === 'FALSE'){
				//echo '<p style="color:red">'.$stack[1]['value'].'</p>';
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

		return array(
			'index' => $stack[0]['index'].'-'.$stack[sizeof($stack)-1]['index'],
			'type' => 'Operand',
			'subtype' => 'Number',
			'value' => 0,
			'deep' => $stack[0]['deep']
		);
	}
}

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
		return $this->stack[0]['value'];
		// return $this->stack;
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
		$this->stack = new FormulaStack();
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

			// printf("%-3s&nbsp&nbsp&nbsp&nbsp%-20s&nbsp&nbsp&nbsp&nbsp%-10s&nbsp&nbsp&nbsp&nbsp%-10s&nbsp&nbsp&nbsp&nbsp%-30s<br>", $i, $token->getTokenType(), $token->getTokenSubType(), $token->getValue(), str_repeat("| ", $indent) . $token->getValue());
			
			if ($token->getTokenSubType() == FormulaToken::TOKEN_SUBTYPE_START ) $indent++;
		}

		$this->result = $this->stack->show();
	}

}


// Trabajo completo
$formula = '=IF(FALSE,54,IF(TRUE,222,323))';

$computo = new Compute($formula);

echo json_encode(array('formula' => $formula, 'computo' => $computo->getResult()));







