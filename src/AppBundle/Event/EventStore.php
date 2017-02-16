<?php

namespace AppBundle\Event;

/**
 * Saving the event names for this bundle.
 * @author blange <lange@bestit-online.de>
 * @package AppBundle
 * @subpackage Event
 * @version $id$
 */
final class EventStore
{
    /**
     * Is triggered after the export of an order.
     * @var string
     */
    const POST_ORDER_EXPORT = 'postOrderExport';

    /**
     * Is triggered after the export of an order.
     * @var string
     */
    const PRE_ORDER_EXPORT = 'preOrderExport';
}
