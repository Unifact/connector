<?php

namespace Unifact\Connector\Handler\Interfaces;

use Unifact\Connector\Models\Job;
use Unifact\Connector\Models\Stage as StageModel;

interface IStageProcessor
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param array $data
     * @return array|object
     */
    public function process(array $data);
}
