<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->initDatabaseMapFromDumps(array (
  'default' => 
  array (
    'tablesByName' => 
    array (
      'Tasks' => '\\Map\\TasksTableMap',
    ),
    'tablesByPhpName' => 
    array (
      '\\Tasks' => '\\Map\\TasksTableMap',
    ),
  ),
));
