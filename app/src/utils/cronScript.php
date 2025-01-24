<?php

require_once __DIR__.'/../../Database.php';

$stmt = Database::get()->prepare('
    CALL naloz_kare();
');
$stmt->execute();

$logFile = __DIR__.'/../../logs/cronLog';
$log = date('d-m-Y H:i:s')." Penalty fees accounted\n";
file_put_contents($logFile, $log, FILE_APPEND);
