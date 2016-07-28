<?php

require __DIR__ . '/vendor/autoload.php';

use Valerio\Compute;



$formula = '=IF(TRUE,IF(FALSE,69,IF(FALSE,599,987)),IF(FALSE,222,323))';

$computo = new Compute($formula);

echo $computo->getResult();