<?php


$DBCONFIG = require __DIR__ . "/../src/app/config/_db.php";

use \Phpmig\Adapter,
    \Pimple\Container,
    \PDO as PDO;

$container = new Container();

$container['db'] = $container->factory(function() use ($DBCONFIG) {
    $dsn = $DBCONFIG['adapter']. ':host=' . $DBCONFIG['host'] . ';dbname=' . $DBCONFIG['dbname'];
    $dbh = new PDO($dsn, $DBCONFIG['username'], $DBCONFIG['password']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
});

$container['phpmig.adapter'] = $container->factory(function() use ($container) {
    return new Adapter\PDO\Sql($container['db'], 'migrations');
});

$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . '../migrations';

$container['phpmig.migrations_template_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'migration_template.stub';

return $container;