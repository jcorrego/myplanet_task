<?php

namespace Drupal\web;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a web entity type.
 */
interface WebInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
