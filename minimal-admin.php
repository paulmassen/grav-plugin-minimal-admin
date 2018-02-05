<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;

/**
 * Class MinimalAdminPlugin
 * @package Grav\Plugin
 */
class MinimalAdminPlugin extends Plugin
{
  /**
   * @return array
   *
   * The getSubscribedEvents() gives the core a list of events
   *     that the plugin wants to listen to. The key of each
   *     array section is the event that the plugin listens to
   *     and the value (in the form of an array) contains the
   *     callable (or function) as well as the priority. The
   *     higher the number the higher the priority.
   */
  public static function getSubscribedEvents()
  {
    return [
      'onPluginsInitialized' => ['onPluginsInitialized', 0]
    ];
  }

  /**
   * Initialize the plugin
   */
  public function onPluginsInitialized()
  {
    // Check if plugin is enabled
    if ($this->config['plugins.webpacker.enabled']) {

      // Proceed only if we are in admin
      if ($this->isAdmin()) {

        // Enable the main event we are interested in
        $this->enable([
          'onAdminTwigTemplatePaths' => ['onAdminTwigTemplatePaths', 0],
          'onAssetsInitialized' => ['onAssetsInitialized', 0]
        ]);
      }
    }
  }

  /**
   * On Assets Initialized event
   */
  public function onAssetsInitialized()
  {
    if ($this->isEditor()) {
      $this->grav['assets']->addCss('plugin://minimal-admin/assets/css/minimal-admin.css');
    }
  }

  /**
   * On Admin Twig Template Paths event
   */
  public function onAdminTwigTemplatePaths($event)
  {
    if ($this->isEditor()) {
      $event['paths'] = array_merge($event['paths'], [__DIR__ . '/templates/admin']);
    }
    return $event;
  }

  /**
   * Check if user is Editor
   */
  private function isEditor()
  {
    $user = $this->grav['user'];
    $selectedGroup = $this->config['plugins.minimal-admin.group'];

    if ($user->groups != null) {
      if (in_array($selectedGroup, $user->groups)) {
        return true;
      }
    }
  }
}
