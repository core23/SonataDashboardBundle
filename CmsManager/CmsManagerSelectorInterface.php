<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\DashboardBundle\CmsManager;

/**
 * The CmsManagerSelectorInterface is in charge of retrieving the correct CmsManagerInterface instance.
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
interface CmsManagerSelectorInterface
{
    /**
     * @return CmsManagerInterface
     */
    public function retrieve();

    /**
     * @return bool
     */
    public function isEditor();
}
