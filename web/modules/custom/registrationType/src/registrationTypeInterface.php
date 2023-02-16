<?php

namespace Drupal\registrationType;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a registration type entity type.
 */
interface registrationTypeInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}