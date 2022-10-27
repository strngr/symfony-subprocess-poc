<?php

include './vendor/autoload.php';

use Symfony\Component\Process\Process;


$process = new Process(['php', 'info.php']);
$process->disableOutput();
$process->start();

while ($process->isRunning()) {
    echo 'process is running' . PHP_EOL;
    sleep(1);
}
