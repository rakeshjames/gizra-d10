<?php

namespace Drupal\Tests\server_general\ExistingSite;

use Drupal\og\Og;
use weitzman\DrupalTestTraits\ExistingSiteBase;

/**
 * Tests the NodeGroups functionality in a real Drupal environment.
 *
 * @group server_general
 */
class NodeGroupsTest extends ExistingSiteBase {

  /**
   * Tests group subscription functionality for different user types.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \Drupal\Core\Entity\EntityMalformedException
   * @throws \Behat\Mink\Exception\ExpectationException
   */
  public function testGroupSubscription() {
    // Create a test user with necessary permissions.
    $user = $this->createUser([
      'access content',
    ]);

    // Create a group node.
    $group = $this->createNode([
      'type' => 'group',
      'title' => 'Test Group',
      'body' => [
        'value' => 'Test group body content',
        'format' => 'basic_html',
      ],
    ]);

    // Test as unauthenticated user.
    $this->drupalGet($group->toUrl());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Test Group');
    $this->assertSession()->pageTextContains('Test group body content');
    $this->assertSession()->pageTextNotContains('click here if you would like to subscribe to this group called Test Group');

    // Test as authenticated user who is not a member.
    $this->drupalLogin($user);
    $this->drupalGet($group->toUrl());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Test Group');
    $this->assertSession()->pageTextContains('Test group body content');
    $this->assertSession()->pageTextContains('Hi ' . $user->getDisplayName());
    $this->assertSession()->pageTextContains('click here if you would like to subscribe to this group called Test Group');

    // Check if user is already a member before subscribing.
    if (Og::isMember($group, $user)) {
      $this->assertSession()->pageTextContains('You are already a member of this group!');
      return;
    }
  }

  /**
   * Tests group creation and configuration.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \Drupal\Core\Entity\EntityMalformedException
   */
  public function testGroupCreation() {
    // Create a user with admin permissions.
    $admin = $this->createUser([
      'access content',
      'administer nodes',
      'administer organic groups',
      'administer permissions',
    ]);

    $this->drupalLogin($admin);

    // Create a group node.
    $group = $this->createNode([
      'type' => 'group',
      'title' => 'Admin Test Group',
      'body' => [
        'value' => 'Admin test group body content',
        'format' => 'basic_html',
      ],
    ]);

    // Verify the group was created as an OG group.
    $this->assertTrue(Og::isGroup('node', 'group'));
    $this->assertTrue(Og::isGroup('node', $group->bundle()));

    // Visit the group page.
    $this->drupalGet($group->toUrl());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Admin Test Group');
    $this->assertSession()->pageTextContains('Admin test group body content');
  }

}
