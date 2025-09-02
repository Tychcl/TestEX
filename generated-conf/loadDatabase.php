<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->initDatabaseMapFromDumps(array (
  'default' => 
  array (
    'tablesByName' => 
    array (
      'category' => '\\Models\\Map\\CategoryTableMap',
      'categoryList' => '\\Models\\Map\\CategorylistTableMap',
      'event' => '\\Models\\Map\\EventTableMap',
      'eventAwardDegree' => '\\Models\\Map\\EventawarddegreeTableMap',
      'eventInfo' => '\\Models\\Map\\EventinfoTableMap',
      'eventLevel' => '\\Models\\Map\\EventlevelTableMap',
      'eventRole' => '\\Models\\Map\\EventroleTableMap',
      'skill' => '\\Models\\Map\\SkillTableMap',
      'student' => '\\Models\\Map\\StudentTableMap',
      'teacher' => '\\Models\\Map\\TeacherTableMap',
      'userRole' => '\\Models\\Map\\UserroleTableMap',
    ),
    'tablesByPhpName' => 
    array (
      '\\Category' => '\\Models\\Map\\CategoryTableMap',
      '\\Categorylist' => '\\Models\\Map\\CategorylistTableMap',
      '\\Event' => '\\Models\\Map\\EventTableMap',
      '\\Eventawarddegree' => '\\Models\\Map\\EventawarddegreeTableMap',
      '\\Eventinfo' => '\\Models\\Map\\EventinfoTableMap',
      '\\Eventlevel' => '\\Models\\Map\\EventlevelTableMap',
      '\\Eventrole' => '\\Models\\Map\\EventroleTableMap',
      '\\Skill' => '\\Models\\Map\\SkillTableMap',
      '\\Student' => '\\Models\\Map\\StudentTableMap',
      '\\Teacher' => '\\Models\\Map\\TeacherTableMap',
      '\\Userrole' => '\\Models\\Map\\UserroleTableMap',
    ),
  ),
));
