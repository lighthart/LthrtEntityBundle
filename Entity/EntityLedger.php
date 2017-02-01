<?php

namespace Lthrt\EntityBundle\Entity;

/**
 * EntityLog.
 *
 * All entities log last change
 *
 * If this interface is implemented is used, all changes will be stored
 * The Listener checks for the presence of this trait
 */
interface EntityLedger
{
}
