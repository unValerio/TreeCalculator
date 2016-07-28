<?php

require __DIR__ . '/vendor/autoload.php';

use Valerio\Compute;



//$formula = '=IF("SI"="SI",9876,69)';
//$formula = '=IF(FALSE,90,IF("ESTE"="ESTE",67,IF(TRUE,12,43)))';
$formula = '=IF("SI"="SI",97,85)';

$computo = new Compute($formula);

echo $computo->getResult();