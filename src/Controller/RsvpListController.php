<?php
/**
 * @file
 * Contains \Drupal\rsvp_list\Controller\RsvpListController
 */
namespace Drupal\rsvp_list\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

class RsvpListController extends ControllerBase {

  protected function load() {
    $select = Database::getConnection()->select('rsvplist', 'r');
    $select->join('users_field_data', 'u', 'r.uid = u.uid');
    $select->join('node_field_data', 'n', 'r.nid = n.nid');
    $select->addField('u', 'name', 'username');
    $select->addField('n', 'title');
    $select->addField('r', 'mail');
    $entries = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
    return $entries;
  }

  public function report() {
    $content = [];
    $content['message'] = [
      '#markup' => $this->t('List of all Event RSVPs and all attenders'),
    ];
    $header = [
      t('Name'),
      t('Event'),
      t('Email'),
    ];
    $rows = [];
    foreach ($this->load() as $entry) {
      $rows[] = $entry;
    }
    $content['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => t('No entities available.'),
    ];
    $content['#cache']['max-age'] = 0;
    return $content;
  }
}
