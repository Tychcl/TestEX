<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->initDatabaseMapFromDumps(array (
  'default' => 
  array (
    'tablesByName' => 
    array (
      'Users' => '\\Models\\Map\\UsersTableMap',
    ),
    'tablesByPhpName' => 
    array (
      '\\Users' => '\\Models\\Map\\UsersTableMap',
    ),
  ),
));
