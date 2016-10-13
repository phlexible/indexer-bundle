PhlexibleIndexerBundle
======================

The PhlexibleIndexerBundle adds support for indexing content in phlexible.

Installation
------------

1. Download PhlexibleIndexerBundle using composer
2. Enable the Bundle
3. Import PhlexibleIndexerBundle routing
4. Clear the symfony cache

### Step 1: Download PhlexibleIndexerBundle using composer

Add PhlexibleIndexerBundle by running the command:

``` bash
$ php composer.phar require phlexible/indexer-bundle "~1.0.0"
```

Composer will install the bundle to your project's `vendor/phlexible` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Phlexible\Bundle\IndexerBundle\PhlexibleIndexerBundle(),
    );
}
```

### Step 3: Import PhlexibleIndexerBundle routing

Import the PhlexibleIndexerBundle routing.

``` yaml
# app/config/routing.yml
phlexible_indexer:
    resource: "@PhlexibleIndexerBundle/Controller/"
    type:     annotation
```

### Step 4: Clear the symfony cache

If you access your phlexible application with environment prod, clear the cache:

``` bash
$ php app/console cache:clear --env=prod
```
