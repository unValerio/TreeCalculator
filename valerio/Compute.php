<?php
namespace Valerio;

use Valerio\FormulaStack;
use Valerio\FormulaParser;
use Valerio\FormulaToken;

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

	public function TreeCreator(FormulaParser $parsedTree)
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