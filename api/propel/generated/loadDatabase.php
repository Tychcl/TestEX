<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->initDatabaseMapFromDumps(array (
  'default' => 
  array (
    'tablesByName' => 
    array (
      'Statuses' => '\\Models\\Map\\StatusesTableMap',
      'Tasks' => '\\Models\\Map\\TasksTableMap',
    ),
    'tablesByPhpName' => 
    array (
      '\\Statuses' => '\\Models\\Map\\StatusesTableMap',
      '\\Tasks' => '\\Models\\Map\\TasksTableMap',
    ),
  ),
));
