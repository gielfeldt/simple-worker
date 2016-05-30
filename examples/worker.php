<?php

namespace Gielfeldt\SimpleWorker\Example;

require 'vendor/autoload.php';

use Gielfeldt\SimpleWorker\Pool;
use Gielfeldt\SimpleWorker\Test\SimpleTestWorker;

$pool = new Pool(['concurrency' => 1]);

$time = microtime(true);
$workers = [];
for ($i = 0; $i < 10; $i++) {
    $workers[] = new SimpleTestWorker(uniqid(), 0.5);
}


print "Adding worker\n";
$pool->addWorkers($workers, function ($worker) use ($time) {
    $elapsed = microtime(true) - $time;
    print "\n$worker->key is ready! Elapsed time: $elapsed\n";
});

print "Processing workers\n";
$pool->process([
    'progress_callback' => function ($pool) {
        print ".";
    },
    'finish_callback' => function ($pool) {
        print "\n";
    },
]);

