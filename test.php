<?php

require __DIR__ . '/vendor/autoload.php';

use Valerio\FormulaToken;
use Valerio\FormulaParser;

/*
	Copyright (c) 2007 E. W. Bachtal, Inc.
	Ported to PHP by Maarten Balliauw (http://www.balliauw.be/maarten)

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software 
	and associated documentation files (the "Software"), to deal in the Software without restriction, 
	including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, 
	and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, 
	subject to the following conditions:
	
	  The above copyright notice and this permission notice shall be included in all copies or substantial 
	  portions of the Software.
	
	The software is provided "as is", without warranty of any kind, express or implied, including but not 
	limited to the warranties of merchantability, fitness for a particular purpose and noninfringement. In 
	no event shall the authors or copyright holders be liable for any claim, damages or other liability, 
	whether in an action of contract, tort or otherwise, arising from, out of or in connection with the 
	software or the use or other dealings in the software. 
	
	http://ewbi.blogs.com/develops/2007/03/excel_formula_p.html
	http://ewbi.blogs.com/develops/2004/12/excel_formula_p.html
*/

// Parse
$test = new FormulaParser('=IF(TRUE,10,5)');

// Print
$indent 	= 1;
$token 		= null;
echo "Formula: " . $test->getFormula() . "<br>";

for ($i = 0; $i < $test->getTokenCount(); $i++) {
	$token = $test->getToken($i);
	
	if ($token->getTokenSubType() == FormulaToken::TOKEN_SUBTYPE_STOP ) $indent--;
	
	printf("%-3s&nbsp&nbsp&nbsp&nbsp%-20s&nbsp&nbsp&nbsp&nbsp%-10s&nbsp&nbsp&nbsp&nbsp%-10s&nbsp&nbsp&nbsp&nbsp%-30s<br>", $i, $token->getTokenType(), $token->getTokenSubType(), $token->getValue(), str_repeat("| ", $indent) . $token->getValue());
	
	if ($token->getTokenSubType() == FormulaToken::TOKEN_SUBTYPE_START ) $indent++;
}