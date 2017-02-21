Yii2 Page Manager
=================

Application sitemap and navigation manager module for Yii 2.0 Framework

**:warning: Breaking changes in 0.14.0 and 0.18.0**

`data structure` and `public properties` are updated and query menu items from now on via `domain_id`

Requirements
------------

- URL manager from [codemix/yii2-localeurls](https://github.com/codemix/yii2-localeurls) configured in application
- role based access control; `auth_items` for every `module_controller_action`


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require dmstr/yii2-pages-module "*"
```

or add

```
"dmstr/yii2-pages-module": "*"
```

to the require section of your `composer.json` file.


Setup
-----

Run migrations

```
./yii migrate \
    --disableLookup=1 \
    --migrationPath=@vendor/dmstr/yii2-pages-module/migrations
```


Configuration
-------------

Enable module in application configuration

```
'pages' => [
	'class' => 'dmstr\modules\pages\Module',
	'layout' => '@admin-views/layouts/main',
	'roles' => ['Admin', 'Editor'],
	'defaultPageLayout' => '@app/modules/frontend/layouts/main',
	'availableRoutes' => [
		'site/index' => 'Index Route',
	],
	'availableViews' => [
		'@app/views/site/index.php' => 'Index View',
	],
],
...
// if used want a url suffix, e.g. '.html', add Url rules for that
'urlManager' => [
	...
	'rules' => [
		'<pagePath:[a-zA-Z0-9_\-\./\+]*>/<pageSlug:[a-zA-Z0-9_\-\.]*>-<pageId:[0-9]*>.html' => 'pages/default/page',
		'<pageSlug:[a-zA-Z0-9_\-\.]*>-<pageId:[0-9]*>.html' => 'pages/default/page',
	],
	...
],
```

Use settings module to configure additional controllers

- Add one controller route per line to section `pages`, key `availableRoutes`

### Settings

- `pages.availableRoutes` - routes per access_domain (for non-admin users)
- `pages.availableViews` - views per access_domain (for non-admin users)
- `pages.availableGlobalRoutes` - global routes (for admin users)
- `pages.availableGlobalViews` - global views(for admin users)


Usage
-----

#### Navbar (eg. `layouts/main`) 

*find a root node / leave node*

by `domain_id` i.e. `root` 

```
$menuItems = \dmstr\modules\pages\models\Tree::getMenuItems('root');
```

*use for example with bootstrap Navbar*

```
    echo yii\bootstrap\Nav::widget(
        [
            'options'         => ['class' => 'navbar-nav navbar-right'],
            'activateItems'   => false,
            'encodeLabels'    => false,
            'activateParents' => true,
            'items'           => Tree::getMenuItems('root'),
        ]
    );
```

#### Backend

- visit `/pages` to create a root-node for your current application language.
- click the *tree* icon
- enter `name identifier (no spaces and special chars)` as *Domain ID* and *Menu name* and save
- create child node
- assign name, title, language and route/view
- save

Now you should be able to see the page in your `Nav` widget in the frontend of your application.

#### Traits

- we use `\dmstr\db\traits\ActiveRecordAccessTrait` to have a check access behavior on active record level


#### Anchors

*available since 0.12.0-beta1*

:construction_worker: A workaround for creating anchor links is to define a route, like `/en/mysite-2` in the settings module.
On a node you can attach an anchor by using *Advanced URL settings*, with `{'#':'myanchor'}`.

It is recommended to create a new entry in *Tree* mode.


Copy pages
---

**Console config**

```
'controllerMap'       => [
	'copy-pages' => '\dmstr\modules\pages\commands\CopyController',
]
```

**CLI**

Command: `yii copy-pages/root-node --rootId --destinationLanguage`

**Web UI**

Url: `/pages/copy`

**RBAC permission**

`pages_copy`



Testing
-------

Requirements:

 - docker >=1.9.1
 - docker-compose >= 1.6.2

Codeception is run via "Potemkin"-Phundament.


    cd tests

Start test stack    
    
    make all

Run tests

    make run-tests
    

Ressources
----------

tbd

---

### ![dmstr logo](http://t.phundament.com/dmstr-16-cropped.png) Built by [dmstr](http://diemeisterei.de)
