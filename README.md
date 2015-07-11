Installation
------------

1. `git clone git@github.com:nitroxy/nvote`
2. `git submodule update --init`
3. Configure vhost
4. Install pecl-memcache (not pecl-memcached)
5. Create mysql database and user
6. Create `config.php` (see `config.php.sample`)
7. Ask a webadmin to generete a keypair for you.
8. `chmod a+rw upload` or similar (webserver need write permission to this dir)
9. For the selected event make sure you are in the correct crew group before logging in.
