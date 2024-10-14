# wp-fail2ban/conf.d
A collection of standard configurations for *WP fail2ban*.

## Usage

First, update the configuration files to the latest version:

```ShellSession
[invisnet@test wordpress]$ cd wp-content/plugins/wp-fail2ban/conf.d
[invisnet@test conf.d]$ git pull
Already up to date.
[invisnet@test conf.d]$
```

Next, copy the configuration file to your `wp-content` directory. Be sure to name it `wp-fail2ban-config.php`.

```ShellSession
[invisnet@test conf.d]$ cp default-all.php ../wp-fail2ban-config.php
[invisnet@test conf.d]$
```

Then, edit your `wp-config.php` file and add the following line:

```PHP
include __DIR__.'/wp-content/wp-fail2ban-config.php';
```

Be sure to add it **before** the line that says:

```PHP
/* That's all, stop editing! Happy blogging. */
```

Finally, if there are any changes you want to make, edit `wp-fail2ban-config.php`.

----

**default-all.php**:
  * Default logs
  * All features enabled
