<?php

namespace Drupal\server_general\Plugin\EntityViewBuilder;

use Drupal\node\NodeInterface;
use Drupal\server_general\EntityViewBuilder\NodeViewBuilderAbstract;
use Drupal\server_general\ThemeTrait\ElementWrapThemeTrait;
use Drupal\Core\Url;
use Drupal\og\Og;
use Drupal\og\OgMembershipInterface;

/**
 * The "Node Groups" plugin.
 *
 * @EntityViewBuilder(
 *   id = "node.group",
 *   label = @Translation("Node - Group"),
 *   description = "Node view builder for Group bundle."
 * )
 */
class NodeGroups extends NodeViewBuilderAbstract {

  use ElementWrapThemeTrait;

  /**
   * Build full view mode.
   */
  public function buildFull(array $build, NodeInterface $entity) {
    $elements = [];

    // Add body if it exists.
    if ($entity->hasField('body') && !$entity->get('body')->isEmpty()) {
      $elements[] = $this->wrapContainerWide([
        '#type' => 'processed_text',
        '#text' => $entity->get('body')->value,
        '#format' => $entity->get('body')->format,
      ]);
    }

    // Add subscription greeting for authenticated users.
    if ($this->currentUser->isAuthenticated()) {
      $is_member = Og::isMember($entity, $this->currentUser);
      $can_subscribe = Og::isGroup($entity->getEntityTypeId(), $entity->bundle());
      $user = $this->entityTypeManager->getStorage('user')->load($this->currentUser->id());
      if (!$is_member && $can_subscribe) {
        $subscribe_url = Url::fromRoute('og.subscribe', [
          'entity_type_id' => 'node',
          'group' => $entity->id(),
          'og_membership_type' => OgMembershipInterface::TYPE_DEFAULT,
        ]);

        $elements[] = $this->wrapContainerWide([
          '#markup' => $this->t('Hi @name, click <a href="@url">here</a> if you would like to subscribe to this group called @label.', [
            '@name' => $user->getDisplayName(),
            '@url' => $subscribe_url->toString(),
            '@label' => $entity->label(),
          ]),
        ]);
      }
      else if ($is_member) {
        $elements[] = $this->wrapContainerWide([
          '#markup' => $this->t('Hi @name, you are already a member of this group.', [
            '@name' => $user->getDisplayName(),
          ]),
        ]);
      }
    }

    $build[] = $elements;

    return $build;
  }

}
