<?php

namespace Gielfeldt\SimpleWorker\Test;

use Gielfeldt\SimpleWorker\Pool;

/**
 * @covers \Gielfeldt\SimpleWorker\Pool
 */
class PoolTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test setup.
     *
     * @covers \Gielfeldt\SimpleWorker\Pool::__construct
     */
    public function testSetup()
    {
        $pool = new Pool();
        $this->assertInstanceOf('\Gielfeldt\SimpleWorker\Pool', $pool, 'Pool was not created properly.');
    }

    /**
     * Test singleton.
     *
     * @covers \Gielfeldt\SimpleWorker\Pool::get
     */
    public function testSingleton()
    {
        $pool1 = Pool::get('pool1');
        $pool2 = Pool::get('pool2');
        $this->assertNotSame($pool1, $pool2, 'Pools were not different.');

        $pool11 = Pool::get('pool1');
        $pool22 = Pool::get('pool2');

        $this->assertSame($pool1, $pool11, 'Pools were not the same.');
        $this->assertSame($pool2, $pool22, 'Pools were not the same.');
    }

    /**
     * Test worker.
     *
     * @covers \Gielfeldt\SimpleWorker\Pool::addWorker
     * @covers \Gielfeldt\SimpleWorker\Pool::process
     */
    public function testWorker()
    {
        $pool = new Pool();
        $worker = new SimpleTestWorker('key1', 1);

        $processed = false;
        $pool->addWorker($worker, function () use (&$processed) {
            $processed = true;
        });

        $this->assertFalse($processed, 'Worker prematurely executed.');
        $pool->process();
        $this->assertTrue($processed, 'Worker did not execute as expected.');
    }

    /**
     * Test worker.
     *
     * @covers \Gielfeldt\SimpleWorker\Pool::addWorkers
     * @covers \Gielfeldt\SimpleWorker\Pool::process
     */
    public function testConcurrency()
    {
        $pool = new Pool();
        $processed = [];
        $callback = function ($worker) use (&$processed) {
            $processed[$worker->key] = true;
        };

        $workers = [];
        $workers[] = new SimpleTestWorker('key1', 1);
        $workers[] = new SimpleTestWorker('key2', 1);
        $workers[] = new SimpleTestWorker('key3', 1);
        $workers[] = new SimpleTestWorker('key4', 1);
        $workers[] = new SimpleTestWorker('key5', 1);
        $workers[] = new SimpleTestWorker('key6', 1);
        $workers[] = new SimpleTestWorker('key7', 1);
        $pool->addWorkers($workers, $callback);

        $this->assertTrue(count($processed) == 0, 'Worker prematurely executed.');
        $pool->process();
        $this->assertTrue(count($processed) == 7, 'Workers did not execute as expected.');
    }

    /**
     * Test worker.
     *
     * @covers \Gielfeldt\SimpleWorker\Pool::addWorker
     * @covers \Gielfeldt\SimpleWorker\Pool::process
     */
    public function testException()
    {
        $pool = new Pool();
        $callback = function () {
            throw new \RuntimeException('Error');
        };

        $pool->addWorker(new SimpleTestWorker('key1', 1), $callback);

        $e = null;
        try {
            $pool->process();
        } catch (\RuntimeException $e) {
        }
        $this->assertEquals('Error', $e->getMessage(), 'Wrong exception was thrown.');
    }

    /**
     * Test callbacks.
     *
     * @covers \Gielfeldt\SimpleWorker\Pool::addWorker
     * @covers \Gielfeldt\SimpleWorker\Pool::process
     */
    public function testCallbacks()
    {
        $progress = 0;
        $counter = 0;
        $finished = false;
        $options = [];
        $options['progress_callback'] = function () use (&$progress) {
            $progress++;
        };
        $options['finish_callback'] = function () use (&$finished) {
            $finished = true;
        };
        $pool = new Pool($options);
        $callback = function () use (&$counter) {
            $counter++;
        };

        $pool->addWorker(new SimpleTestWorker('key1', 1), $callback);
        $pool->addWorker(new SimpleTestWorker('key2', 1), $callback);
        $pool->addWorker(new SimpleTestWorker('key3', 1), $callback);

        $pool->process();
        $this->assertTrue($finished, 'Finish callback was not executed.');
        $this->assertTrue(is_numeric($progress) && $progress > 0, 'Progress counter was not incremented correctly.');
        $this->assertEquals(3, $counter, 'Counter was not incremented correctly.');
    }
}
