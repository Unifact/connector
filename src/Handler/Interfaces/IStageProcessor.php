<?php

namespace Unifact\Connector\Handler\Interfaces;

use Unifact\Connector\Models\Job;
use Unifact\Connector\Models\Stage as StageModel;

interface IStageProcessor
{
    /**
     * @param Job $job
     * @return array|object
     */
    public function process(Job $job);
}
