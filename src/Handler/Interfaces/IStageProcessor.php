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
     * @return void
     */
    public function setData(array $data);

    /**
     * @param array $data
     * @return bool
     */
    public function process(array $data);
}
