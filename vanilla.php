<?php

define('CHUNK_SIZE', 16384);

$output = '';
$process = proc_open(
  "php info.php",
  [
    ['pipe', 'r'],
    ['pipe', 'w'],
    ['pipe', 'w'],
  ],
  $pipes,
  getcwd(),
  []
);

# the issue is reproducible only in non-blocking mode. blocking mode is not acceptable as it is expected to handle multiple parallel tasks
stream_set_blocking($pipes[1], 0);
// stream_set_read_buffer($pipes[1], 0);

while ($close = proc_get_status($process)['running']) {
  echo 'process is running. CHUNK_SIZE=' . CHUNK_SIZE . PHP_EOL;

  $stdout = $pipes[1]; # stdout

  do {
    $data = fread($stdout, CHUNK_SIZE);
    echo strlen($data) . PHP_EOL;
    $output .= $data;
  } while (isset($data[0]) && ($close || isset($data[CHUNK_SIZE - 1])));

  if (feof($stdout)) {
    fclose($stdout);
    break;
  }

  sleep(1);
}

echo $output;
