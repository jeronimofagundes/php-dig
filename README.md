# php-dig
An utility to run dig from PHP

# How to use this library
In order to one use this library, he/she can require it with composer, 
the standard PHP library package manager.

```
composer require "jeronimofagundes/php-dig:1.0.3"
```  

and use the following class in his/her code:
```
...
use \Jeronimofagundes\PhpDig\Dig;
use \Jeronimofagundes\PhpDig\DigConfig;
...
// If you need config the dig query, Do it like this:
$digConf = new DigConfig();
$digConf->setTimeout(10)->setServer("8.8.8.8")->setTries(5);

// Do a query with your config
$dig1 = new Dig($digConf);
$res1 = $dig1->query("github.com", "A");

// Or with no config at all (use defaults)
$dig2 = new Dig();
$res2 = $dig2->query("github.com", "A");
... 
```

To see the full docs, download this repo and open in your browser the docs/index.html file.