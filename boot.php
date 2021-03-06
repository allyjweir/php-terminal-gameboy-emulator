<?php

require_once __DIR__ . '/vendor/autoload.php';

use GameBoy\Canvas\TerminalCanvas;
use GameBoy\Core;
use GameBoy\Keyboard;

if (count($argv) < 2) {
    echo 'You need to pass the ROM file name (Ex: drmario.rom)';
}

$rom = base64_decode(file_get_contents($argv[1]));

$canvas = new TerminalCanvas();
$core = new Core($rom, $canvas);
$keyboard = new Keyboard($core);

$core->start();

if ($core->stopEmulator & 2 == 2) {
    $core->stopEmulator &= 1;
    $core->lastIteration = (int) (microtime(true) * 1000);

    while (true) {
        $core->run();
        $keyboard->check();
    }
} else if (($core->stopEmulator & 2) == 0) {
    echo 'The GameBoy core is already running.' . PHP_EOL;
} else {
    echo 'GameBoy core cannot run while it has not been initialized.' . PHP_EOL;
}
