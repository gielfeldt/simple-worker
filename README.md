# Simple Worker

[![Build Status](https://scrutinizer-ci.com/g/gielfeldt/simple-worker/badges/build.png?b=master)][8]
[![Test Coverage](https://codeclimate.com/github/gielfeldt/simple-worker/badges/coverage.svg)][3]
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gielfeldt/simple-worker/badges/quality-score.png?b=master)][7]
[![Code Climate](https://codeclimate.com/github/gielfeldt/simple-worker/badges/gpa.svg)][5]

[![Latest Stable Version](https://poser.pugx.org/gielfeldt/simple-worker/v/stable.svg)][1]
[![Latest Unstable Version](https://poser.pugx.org/gielfeldt/simple-worker/v/unstable.svg)][1]
[![License](https://poser.pugx.org/gielfeldt/simple-worker/license.svg)][4]
[![Total Downloads](https://poser.pugx.org/gielfeldt/simple-worker/downloads.svg)][1]

## Installation

To install the Simple Worker library in your project using Composer, first add the following to your `composer.json`
config file.
```javascript
{
    "require": {
        "gielfeldt/simple-worker": "^0.1"
    }
}
```

Then run Composer's install or update commands to complete installation. Please visit the [Composer homepage][6] for
more information about how to use Composer.

### Simple Worker

This class allows you to queue operations in a pool and let them commence when ready.

#### Motivation

1. De-coupling of the handling of guzzle asynchronous requests.

#### Using the Simple Worker library

##### Example

```php

```

For more examples see the examples/ folder.

#### Features

* Queue operations in a pool.

#### Caveats

1. Lots probably.


[1]:  https://packagist.org/packages/gielfeldt/simple-worker
[2]:  https://circleci.com/gh/gielfeldt/simple-worker
[3]:  https://codeclimate.com/github/gielfeldt/simple-worker/coverage
[4]:  https://github.com/gielfeldt/simple-worker/blob/master/LICENSE.md
[5]:  https://codeclimate.com/github/gielfeldt/simple-worker
[6]:  http://getcomposer.org
[7]:  https://scrutinizer-ci.com/g/gielfeldt/simple-worker/?branch=master
[8]:  https://scrutinizer-ci.com/g/gielfeldt/simple-worker/build-status/master
