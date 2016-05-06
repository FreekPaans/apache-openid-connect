# Apache OpenID Connect

The projects allows you to turn Apache into an OpenID Connect Provider (OP). This is useful if you currently have an environment that's currently protected by Apache's Basic or Digest Authentication, and want to use that authentication in related environments without sharing password files.

It builds on top of Brent Shaffer's https://github.com/bshaffer/oauth2-server-php for all the OAuth/OIDC related magic, so big thanks to him for that effort.

## Getting started
Before starting, please make sure you're familiar with both OAuth2 and OpenID Connect.

Getting things to work requires a couple of steps:

1. Configure oauth2-server-php
2. Generate keys
3. Configure apache
 
### Configure oauth2-server-php
oauth2-server-php requires a database to function. For this project we assume a mysql database. First, copy `config.default.php` to `config.php` and modify accordingly. Then do `php generate-sql.php | mysql -u <your user> -p <your database>` to generate the schema. Then define your first OAuth Client by inserting a row in the `oauth_clients` table.

### Generate keys
OpenID Connect needs a private/public key pair to sign its tokens. First create a `keys` directory in the root and `cd` into it, then invoke `../bin/generate-keys.sh` to generate the key pair.

### Configure Apache
First configure Apache such that you can access `www-root` through it. Then setup Apache to require authentication for the `www-root/authorization.php` file. An example `.htaccess` file for doing so can be found in `examples/htaccess`. Now, when you visit `authorization.php?client_id=<your client id>&state=<csrf state>&response_type=code&scope=openid`, you should be requested to enter your credentials and be asked whether you want to Authorize the client.
