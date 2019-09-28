<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\DashboardBundle\Action;

use Sonata\DashboardBundle\Admin\DashboardAdmin;
use Sonata\DashboardBundle\Model\DashboardInterface;
use Sonata\DashboardBundle\Model\DashboardManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

final class DashboardAction
{
    /**
     * @var DashboardManagerInterface
     */
    private $dashboardManager;

    /**
     * @var DashboardAdmin
     */
    private $admin;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * DashboardAction constructor.
     */
    public function __construct(DashboardManagerInterface $dashboardManager, DashboardAdmin $admin, Environment $twig)
    {
        $this->dashboardManager = $dashboardManager;
        $this->admin = $admin;
        $this->twig = $twig;
    }

    public function __invoke(Request $request, int $id): Response
    {
        $dashboard = $this->dashboardManager->find($id);

        if (!$dashboard instanceof DashboardInterface) {
            throw new NotFoundHttpException('Unknown dashboard');
        }

        if (!$dashboard->getEnabled()) {
            throw new NotFoundHttpException(sprintf(
                'Dashboard #%s is not enabled',
                $dashboard->getId()
            ));
        }

        $containers = [];

        // separate containers
        foreach ($dashboard->getBlocks() as $block) {
            $blockCode = $block->getSetting('code');
            if (null === $block->getParent()) {
                $containers[$blockCode] = $block;
            }
        }

        $dashboards = $this->dashboardManager->findBy([
                'enabled' => true,
            ], [
                'updatedAt' => 'DESC',
        ], 5);

        return new Response($this->twig->render('@SonataDashboard/Dashboard/dashboard.html.twig', [
            'object' => $dashboard,
            'action' => 'edit',
            'containers' => $containers,
            'dashboards' => $dashboards,
        ]));
    }
}
