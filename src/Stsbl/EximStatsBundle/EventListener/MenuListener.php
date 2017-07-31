<?php
// src/Stsbl/EximStatsBundle/EventListener/MenuListener.php
namespace Stsbl\EximStatsBundle\EventListener;

use IServ\CoreBundle\Event\MenuEvent;
use IServ\AdminBundle\EventListener\AdminMenuListenerInterface;

/**
 * Class MenuListener
 * @package IServ\SysmonBundle\EventListener
 */
class MenuListener implements AdminMenuListenerInterface
{
    /**
     * @param \IServ\CoreBundle\Event\MenuEvent $event
     * @return \Knp\Menu\ItemInterface
     */
    public function onBuildAdminMenu(MenuEvent $event)
    {
    	$menu = $event->getMenu();

    	$block = $menu->getChild(self::ADMIN_NETWORK);

    	$block->addChild('admin_eximstats', array('route' => 'admin_eximstats_index', 'label' => _('E-Mail server statistics')))
    	   ->setExtra('orderNumber', 99)
    	   ->setExtra('icon', 'mail')
    	;

    	return $menu;
    }
}
