<?php

declare(strict_types=1);

namespace Stsbl\EximStatsBundle\EventListener;

use IServ\CoreBundle\Event\MenuEvent;
use IServ\AdminBundle\EventListener\AdminMenuListenerInterface;

final class MenuListener implements AdminMenuListenerInterface
{
    /**
     * {@inheritDoc}
     */
    public function onBuildAdminMenu(MenuEvent $event): void
    {
        $menu = $event->getMenu();
        $block = $menu->getChild(self::ADMIN_NETWORK);
        $block->addChild(
            'admin_eximstats',
            ['route' => 'admin_eximstats_index', 'label' => _('E-Mail server statistics')]
        )
            ->setExtra('orderNumber', 99)
            ->setExtra('icon', 'mail')
        ;
    }
}
