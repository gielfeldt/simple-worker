<?php

namespace Gielfeldt\SimpleWorker;

/**
 * Class Pool
 *
 * @package Gielfeldt\SimpleWorker
 */
class Pool
{
    /**
     * Workers in the pool.
     * @var array
     */
    protected $workers = [];

    /**
     * Options for the pool.
     * @var array
     */
    protected $options;

    /**
     * Pool singletons.
     * @var Pool[]
     */
    static protected $pools = [];

    /**
     * Pool constructor.
     *
     * @param array $options
     *   Options for the pool.
     *    - concurrency
     *    - polling_interval
     */
    public function __construct(array $options = array())
    {
        $this->options = $options + [
                'concurrency' => 5,
                'polling_interval' => 0.1,
                'progress_callback' => null,
                'finish_callback' => null,
            ];
    }

    /**
     * Get a singleton pool.
     *
     * @param string $name
     *   Name of pool.
     * @param array $options
     *   Options for the pool.
     *
     * @return \Gielfeldt\SimpleWorker\Pool
     */
    public static function get($name, array $options = array())
    {
        if (!isset(static::$pools[$name])) {
            static::$pools[$name] = new static($options);
        }
        return static::$pools[$name];
    }

    /**
     * Add a worker to the pool.
     *
     * @param \Gielfeldt\SimpleWorker\SimpleWorkerInterface $worker
     *   The worker to add.
     * @param $callback
     *   The callback to fire when ready.
     */
    public function addWorker(SimpleWorkerInterface $worker, $callback)
    {
        $this->workers[] = [$worker, $callback];
    }

    /**
     * Add multiple workers to the pool.
     *
     * @param \Gielfeldt\SimpleWorker\SimpleWorkerInterface[] $workers
     *   The worker to add.
     * @param $callback
     *   The callback to fire when ready.
     */
    public function addWorkers(array $workers, $callback)
    {
        foreach ($workers as $worker) {
            $this->addWorker($worker, $callback);
        }
    }

    /**
     * Process workers.
     */
    public function process(array $options = array())
    {
        $options += $this->options;
        while ($this->workers) {
            $skipSleep = false;
            foreach ($this->workers as $idx => $worker) {
                if ($idx >= $options['concurrency']) {
                    break;
                }
                try {
                    if ($worker[0]->isReady()) {
                        call_user_func($worker[1], $worker[0]);
                        unset($this->workers[$idx]);
                        $this->workers = array_values($this->workers);
                        $skipSleep = true;
                        break;
                    }
                } catch (\Exception $e) {
                    // Remove worker from pool if exception occurred.
                    unset($this->workers[$idx]);
                    throw $e;
                }
            }
            if ($options['progress_callback']) {
                call_user_func($options['progress_callback'], $this);
            }
            if (!$skipSleep && $options['polling_interval'] > 0) {
                usleep($options['polling_interval'] * 1000000);
            }
        }
        if ($options['finish_callback']) {
            call_user_func($options['finish_callback'], $this);
        }
    }
}
