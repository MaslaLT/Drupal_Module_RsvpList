<?php

namespace Drupal\rsvp_list\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;

/** Provides an 'RSVP' List BLock
 * @Block(
 *   id = "rsvp_block",
 *   admin_label = @Translation("RSVP Block"),
 *   category = @Translation("Blocks")
 * )
 */
class RsvpBlock extends BlockBase {

  /**
   * @inheritDoc
   */
  public function build() {
    return \Drupal::formBuilder()->getForm('Drupal\rsvp_list\Form\RsvpForm');
  }

  /**
   * @inheritDoc
   */
  public function blockAccess(AccountInterface $account) {
    /** @var \Drupal\node\Entity\Node $node */
    $node = \Drupal::routeMatch()->getParameter('node');
    $enabler = \Drupal::service('rsvp_list.enabler');
    if(!is_null($node)) {
      $nid = $node->nid->value;
    }
    if(is_numeric($nid)) {
      if($enabler->isEnabled($node)) {
        return AccessResult::allowedIfHasPermission($account, 'view rsvp_list');
      }
    }
    return AccessResult::forbidden();
  }

}
