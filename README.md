<p align="center">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset="assets/images/logo-white.svg">
    <source media="(prefers-color-scheme: light)" srcset="assets/images/logo-dark.svg">
    <img alt="Perpetual WP Logo" src="assets/images/logo-white.svg" height="150px">
  </picture>
</p>

> [!NOTE]  
> Once you've installed the plugin, all updates are automatically installed from GitHub. Please note, this plugin **is not available in the WordPress.org repository**.

![Build](https://img.shields.io/github/actions/workflow/status/jsgm/perpetual-wp/phplint.yml) ![License](https://img.shields.io/github/license/jsgm/perpetual-wp) ![Last release](https://img.shields.io/github/v/release/jsgm/perpetual-wp) ![Last commit](https://img.shields.io/github/last-commit/jsgm/perpetual-wp/main
)

## What is Perpetual WP?
Perpetual WP removes bloat and delivers straightforward enhancements to your WordPress—no configuration required.

While alternatives like [ClassicPress](https://classicpress.net), which is a full fork of WordPress, Perpetual WP takes a unique approach as a plugin, ensuring full compatibility with the latest version of WordPress.

## Disabled features
**Perpetual WP** disables unnecessary features to keep the admin panel cleaner and improve performance. 

### Prevent a feature from being disabled
WordPress use cases can vary greatly, and it’s possible that you may need some of the features that are disabled.

**Perpetual WP** honors your choices whenever possible, and in other cases, you can modify the settings through wp-admin under `Settings > General`. For instance, while WordPress defaults to enabling post revisions with `WP_POST_REVISIONS` set to true, this plugin will automatically set it to `false` unless you specify a different value in your wp-config.php file.

For any other cases, you can use the filter `pw_modules`. This allows you to disable any feature via a child theme or directly by other plugin developers, preventing undesired behaviours.

## Functions
Perpetual-wp also introduces a few functions for developers. All functions will be prefixed with `pw_` instead the default `wp_`.

Function|Description
---|---
`pw_count_hooks(string $tag, int $priority): int`|Returns the total number of hooks registered for a filter or action.<br>If no priority is specified, it returns the count of all hooks across all priority levels.
`pw_is_crawler(string $user_agent): bool`|Checks if the given $user_agent belongs to a known crawler.<br>If no $user_agent is provided, the function checks the current user's agent string.
