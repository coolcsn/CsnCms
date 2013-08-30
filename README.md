CsnCms
======
Zend Framework 2 Module

### What is CsnCms? ###
CsnCms is a Content Management System module based on `DoctrineORMModule`, `CsnUser` authentication and `CsnAuthorization`.

### What exactly does CsnCms do? ###
CsnCms has been created with educational purposes to demonstrate how CMS can be done. It is fully functional, working in perfect harmony with *Doctrine* and the other Csn modules.

Installation
------------
1. Installation via composer is supported, simply run (make sure you've set `"minimum-stability": "dev"` in your *composer.json* file):
`php composer.phar require coolcsn/csn-cms:dev-master`

2. Configure referenced modules ([CsnUser](https://github.com/coolcsn/CsnUser) and [CsnAuthorization](https://github.com/coolcsn/CsnAuthorization)) following their instructions.

3. Add 'CsnCms' to your application configuration in `config/application.config.php`. An example application configuration could look like the following:

```
'modules' => array(
    'Application',
    'DoctrineModule',
    'DoctrineORMModule',
    'CsnUser',
    'CsnAuthorization',
    'CsnCms'
)
```

4. Run `./vendor/bin/doctrine-module orm:schema-tool:update` to update the database schema (**Note:** You may need to force the update by adding ` --force` to the command). Then import the sample data located in `./vendor/coolcsn/CsnCms/data/SampleData.sql`. You can easily do that with *PhpMyAdmin* for instance.

- **Important:** CsnCms requires setting a connection for Doctrine (if you haven't done this for some of your other modules). You can paste the following snippet in `config/autoload/doctrine.local.php`, replacing the tokens with your actual connection parameters:

```
return array(
  'doctrine' => array(
    'connection' => array(
      'orm_default' => array(
        'driverClass' =>'Doctrine\DBAL\Driver\PDOMySql\Driver',
        'params' => array(
          'host'     => 'localhost',
          'port'     => '3306',
          'user'     => 'username',
          'password' => 'password',
          'dbname'   => 'database',
)))));
```

>### We are done, uh? ###
Navigate to ***[hostname]/csn-cms***. Enjoy :)

Dependencies
------------
This Module depends on the following Modules:

- DoctrineORMModule
- CsnUser
- CsnAuthorization

Recommends
----------
- [coolcsn/CsnUser](https://github.com/coolcsn/CsnUser) - Authentication (login, registration) module.
- [coolcsn/CsnAuthorization](https://github.com/coolcsn/CsnAuthorization) - Authorization module.
- [coolcsn/CsnAclNavigation](https://github.com/coolcsn/CsnAclNavigation) - Navigation module;