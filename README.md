CsnCms
=======

**What is CsnCms?**

CsnCms is a Module for CMS based on DoctrineORMModule

**What exactly does CsnCms?**

CsnCms has been created with educational purposes to demonstrate how CMS can be done. It is fully functional.

**What's the use again?**

Nothing but yet another CMS Module like ZfcUser.

Installation
============

Installation via composer is supported, simply add the following line to your ```composer.json```

```
"repositories": [
	{
		"type": "vcs",
		"url": "https://github.com/coolcsn/CsnCms"
	}
],
"require" : {
    "coolcsn/csn-user": "dev-master"
}
```

After adding to the composer's packagist.org (not ready yet)

```
"require" : {
    "coolcsn/csn-user": "dev-master"
}
```

An example application configuration could look like the following:

```
'modules' => array(
    'Application',
    'DoctrineModule',
    'DoctrineORMModule',
    'CsnCms'
)
```

Configuration
=============

This Module doesn't require any special configuration. All that's needed is to set up a Connection for Doctrine.

Dependencies
============

This Module depends on the following Modules:

 - DoctrineORMModule
