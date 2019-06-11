<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\Model\Generator;

use Psr\Log\LoggerInterface;

interface YvesAssetsBuildConfigGeneratorInterface
{
    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function generateYvesAssetsBuildConfig(LoggerInterface $logger): bool;
}