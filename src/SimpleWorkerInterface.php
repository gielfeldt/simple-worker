<?php

namespace Gielfeldt\SimpleWorker;

/**
 * Interface SimpleWorkerInterface.
 *
 * @package Gielfeldt\SimpleWorker
 */
interface SimpleWorkerInterface
{
    /**
     * Check if a worker is ready for work.
     *
     * @return bool
     *   TRUE if worker is ready for callback to be fired.
     */
    public function isReady();
}
