<?php

namespace Gielfeldt\SimpleWorker\Test;

use Gielfeldt\SimpleWorker\SimpleWorkerInterface;

/**
 * Class SimpleTestWorker.
 *
 * @package Gielfeldt\SimpleWorker\Test
 */
class SimpleTestWorker implements SimpleWorkerInterface {
    /**
     * The key.
     * @var string
     */
    public $key;

    /**
     * The delay in seconds.
     * @var float
     */
    public $delay;

    /**
     * The timestamp to be ready at.
     * @var float
     */
    public $timestamp;

    /**
     * SimpleTestWorker constructor.
     *
     * @param string $key
     *   The key.
     * @param float $delay
     *   The delaystamp.
     */
    public function __construct($key, $delay) {
        $this->key = $key;
        $this->delay = $delay;
    }

    /**
     * {@inheritdoc}
     */
    public function isReady() {
        if (!isset($this->timestamp)) {
            $this->timestamp = microtime(true) + $this->delay;
        }
        if ($this->timestamp <= microtime(true)) {
            return true;
        }
        return false;
    }
}
