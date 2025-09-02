<?php

namespace Models\Map;

use Models\Teacher;
use Models\TeacherQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'teacher' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class TeacherTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Models.Map.TeacherTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'teacher';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Teacher';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Models\\Teacher';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Models.Teacher';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 9;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 9;

    /**
     * the column name for the id field
     */
    public const COL_ID = 'teacher.id';

    /**
     * the column name for the surname field
     */
    public const COL_SURNAME = 'teacher.surname';

    /**
     * the column name for the name field
     */
    public const COL_NAME = 'teacher.name';

    /**
     * the column name for the midname field
     */
    public const COL_MIDNAME = 'teacher.midname';

    /**
     * the column name for the login field
     */
    public const COL_LOGIN = 'teacher.login';

    /**
     * the column name for the password field
     */
    public const COL_PASSWORD = 'teacher.password';

    /**
     * the column name for the roleId field
     */
    public const COL_ROLEID = 'teacher.roleId';

    /**
     * the column name for the needUpdSkill field
     */
    public const COL_NEEDUPDSKILL = 'teacher.needUpdSkill';

    /**
     * the column name for the canUpdCategory field
     */
    public const COL_CANUPDCATEGORY = 'teacher.canUpdCategory';

    /**
     * The default string format for model objects of the related table
     */
    public const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     *
     * @var array<string, mixed>
     */
    protected static $fieldNames = [
        self::TYPE_PHPNAME       => ['Id', 'Surname', 'Name', 'Midname', 'Login', 'Password', 'Roleid', 'Needupdskill', 'Canupdcategory', ],
        self::TYPE_CAMELNAME     => ['id', 'surname', 'name', 'midname', 'login', 'password', 'roleid', 'needupdskill', 'canupdcategory', ],
        self::TYPE_COLNAME       => [TeacherTableMap::COL_ID, TeacherTableMap::COL_SURNAME, TeacherTableMap::COL_NAME, TeacherTableMap::COL_MIDNAME, TeacherTableMap::COL_LOGIN, TeacherTableMap::COL_PASSWORD, TeacherTableMap::COL_ROLEID, TeacherTableMap::COL_NEEDUPDSKILL, TeacherTableMap::COL_CANUPDCATEGORY, ],
        self::TYPE_FIELDNAME     => ['id', 'surname', 'name', 'midname', 'login', 'password', 'roleId', 'needUpdSkill', 'canUpdCategory', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, ]
    ];

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     *
     * @var array<string, mixed>
     */
    protected static $fieldKeys = [
        self::TYPE_PHPNAME       => ['Id' => 0, 'Surname' => 1, 'Name' => 2, 'Midname' => 3, 'Login' => 4, 'Password' => 5, 'Roleid' => 6, 'Needupdskill' => 7, 'Canupdcategory' => 8, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'surname' => 1, 'name' => 2, 'midname' => 3, 'login' => 4, 'password' => 5, 'roleid' => 6, 'needupdskill' => 7, 'canupdcategory' => 8, ],
        self::TYPE_COLNAME       => [TeacherTableMap::COL_ID => 0, TeacherTableMap::COL_SURNAME => 1, TeacherTableMap::COL_NAME => 2, TeacherTableMap::COL_MIDNAME => 3, TeacherTableMap::COL_LOGIN => 4, TeacherTableMap::COL_PASSWORD => 5, TeacherTableMap::COL_ROLEID => 6, TeacherTableMap::COL_NEEDUPDSKILL => 7, TeacherTableMap::COL_CANUPDCATEGORY => 8, ],
        self::TYPE_FIELDNAME     => ['id' => 0, 'surname' => 1, 'name' => 2, 'midname' => 3, 'login' => 4, 'password' => 5, 'roleId' => 6, 'needUpdSkill' => 7, 'canUpdCategory' => 8, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ID',
        'Teacher.Id' => 'ID',
        'id' => 'ID',
        'teacher.id' => 'ID',
        'TeacherTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'Surname' => 'SURNAME',
        'Teacher.Surname' => 'SURNAME',
        'surname' => 'SURNAME',
        'teacher.surname' => 'SURNAME',
        'TeacherTableMap::COL_SURNAME' => 'SURNAME',
        'COL_SURNAME' => 'SURNAME',
        'Name' => 'NAME',
        'Teacher.Name' => 'NAME',
        'name' => 'NAME',
        'teacher.name' => 'NAME',
        'TeacherTableMap::COL_NAME' => 'NAME',
        'COL_NAME' => 'NAME',
        'Midname' => 'MIDNAME',
        'Teacher.Midname' => 'MIDNAME',
        'midname' => 'MIDNAME',
        'teacher.midname' => 'MIDNAME',
        'TeacherTableMap::COL_MIDNAME' => 'MIDNAME',
        'COL_MIDNAME' => 'MIDNAME',
        'Login' => 'LOGIN',
        'Teacher.Login' => 'LOGIN',
        'login' => 'LOGIN',
        'teacher.login' => 'LOGIN',
        'TeacherTableMap::COL_LOGIN' => 'LOGIN',
        'COL_LOGIN' => 'LOGIN',
        'Password' => 'PASSWORD',
        'Teacher.Password' => 'PASSWORD',
        'password' => 'PASSWORD',
        'teacher.password' => 'PASSWORD',
        'TeacherTableMap::COL_PASSWORD' => 'PASSWORD',
        'COL_PASSWORD' => 'PASSWORD',
        'Roleid' => 'ROLEID',
        'Teacher.Roleid' => 'ROLEID',
        'roleid' => 'ROLEID',
        'teacher.roleid' => 'ROLEID',
        'TeacherTableMap::COL_ROLEID' => 'ROLEID',
        'COL_ROLEID' => 'ROLEID',
        'roleId' => 'ROLEID',
        'teacher.roleId' => 'ROLEID',
        'Needupdskill' => 'NEEDUPDSKILL',
        'Teacher.Needupdskill' => 'NEEDUPDSKILL',
        'needupdskill' => 'NEEDUPDSKILL',
        'teacher.needupdskill' => 'NEEDUPDSKILL',
        'TeacherTableMap::COL_NEEDUPDSKILL' => 'NEEDUPDSKILL',
        'COL_NEEDUPDSKILL' => 'NEEDUPDSKILL',
        'needUpdSkill' => 'NEEDUPDSKILL',
        'teacher.needUpdSkill' => 'NEEDUPDSKILL',
        'Canupdcategory' => 'CANUPDCATEGORY',
        'Teacher.Canupdcategory' => 'CANUPDCATEGORY',
        'canupdcategory' => 'CANUPDCATEGORY',
        'teacher.canupdcategory' => 'CANUPDCATEGORY',
        'TeacherTableMap::COL_CANUPDCATEGORY' => 'CANUPDCATEGORY',
        'COL_CANUPDCATEGORY' => 'CANUPDCATEGORY',
        'canUpdCategory' => 'CANUPDCATEGORY',
        'teacher.canUpdCategory' => 'CANUPDCATEGORY',
    ];

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function initialize(): void
    {
        // attributes
        $this->setName('teacher');
        $this->setPhpName('Teacher');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Models\\Teacher');
        $this->setPackage('Models');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('surname', 'Surname', 'VARCHAR', true, 45, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 45, null);
        $this->addColumn('midname', 'Midname', 'VARCHAR', true, 45, null);
        $this->addColumn('login', 'Login', 'LONGVARCHAR', true, null, null);
        $this->addColumn('password', 'Password', 'LONGVARCHAR', true, null, null);
        $this->addForeignKey('roleId', 'Roleid', 'INTEGER', 'userRole', 'id', true, null, null);
        $this->addColumn('needUpdSkill', 'Needupdskill', 'BOOLEAN', true, 1, true);
        $this->addColumn('canUpdCategory', 'Canupdcategory', 'BOOLEAN', true, 1, true);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('Userrole', '\\Models\\Userrole', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':roleId',
    1 => ':id',
  ),
), 'RESTRICT', 'CASCADE', null, false);
        $this->addRelation('Category', '\\Models\\Category', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':teacherId',
    1 => ':id',
  ),
), 'RESTRICT', 'CASCADE', 'Categories', false);
        $this->addRelation('Event', '\\Models\\Event', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':teacherId',
    1 => ':id',
  ),
), 'RESTRICT', 'CASCADE', 'Events', false);
        $this->addRelation('Skill', '\\Models\\Skill', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':teacherId',
    1 => ':id',
  ),
), 'RESTRICT', 'CASCADE', 'Skills', false);
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array $row Resultset row.
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string|null The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): ?string
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array $row Resultset row.
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param bool $withPrefix Whether to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass(bool $withPrefix = true): string
    {
        return $withPrefix ? TeacherTableMap::CLASS_DEFAULT : TeacherTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array $row Row returned by DataFetcher->fetch().
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array (Teacher object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = TeacherTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = TeacherTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + TeacherTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = TeacherTableMap::OM_CLASS;
            /** @var Teacher $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            TeacherTableMap::addInstanceToPool($obj, $key);
        }

        return [$obj, $col];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array<object>
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher): array
    {
        $results = [];

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = TeacherTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = TeacherTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Teacher $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                TeacherTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria Object containing the columns to add.
     * @param string|null $alias Optional table alias
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return void
     */
    public static function addSelectColumns(Criteria $criteria, ?string $alias = null): void
    {
        if (null === $alias) {
            $criteria->addSelectColumn(TeacherTableMap::COL_ID);
            $criteria->addSelectColumn(TeacherTableMap::COL_SURNAME);
            $criteria->addSelectColumn(TeacherTableMap::COL_NAME);
            $criteria->addSelectColumn(TeacherTableMap::COL_MIDNAME);
            $criteria->addSelectColumn(TeacherTableMap::COL_LOGIN);
            $criteria->addSelectColumn(TeacherTableMap::COL_PASSWORD);
            $criteria->addSelectColumn(TeacherTableMap::COL_ROLEID);
            $criteria->addSelectColumn(TeacherTableMap::COL_NEEDUPDSKILL);
            $criteria->addSelectColumn(TeacherTableMap::COL_CANUPDCATEGORY);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.surname');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.midname');
            $criteria->addSelectColumn($alias . '.login');
            $criteria->addSelectColumn($alias . '.password');
            $criteria->addSelectColumn($alias . '.roleId');
            $criteria->addSelectColumn($alias . '.needUpdSkill');
            $criteria->addSelectColumn($alias . '.canUpdCategory');
        }
    }

    /**
     * Remove all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be removed as they are only loaded on demand.
     *
     * @param Criteria $criteria Object containing the columns to remove.
     * @param string|null $alias Optional table alias
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return void
     */
    public static function removeSelectColumns(Criteria $criteria, ?string $alias = null): void
    {
        if (null === $alias) {
            $criteria->removeSelectColumn(TeacherTableMap::COL_ID);
            $criteria->removeSelectColumn(TeacherTableMap::COL_SURNAME);
            $criteria->removeSelectColumn(TeacherTableMap::COL_NAME);
            $criteria->removeSelectColumn(TeacherTableMap::COL_MIDNAME);
            $criteria->removeSelectColumn(TeacherTableMap::COL_LOGIN);
            $criteria->removeSelectColumn(TeacherTableMap::COL_PASSWORD);
            $criteria->removeSelectColumn(TeacherTableMap::COL_ROLEID);
            $criteria->removeSelectColumn(TeacherTableMap::COL_NEEDUPDSKILL);
            $criteria->removeSelectColumn(TeacherTableMap::COL_CANUPDCATEGORY);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.surname');
            $criteria->removeSelectColumn($alias . '.name');
            $criteria->removeSelectColumn($alias . '.midname');
            $criteria->removeSelectColumn($alias . '.login');
            $criteria->removeSelectColumn($alias . '.password');
            $criteria->removeSelectColumn($alias . '.roleId');
            $criteria->removeSelectColumn($alias . '.needUpdSkill');
            $criteria->removeSelectColumn($alias . '.canUpdCategory');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap(): TableMap
    {
        return Propel::getServiceContainer()->getDatabaseMap(TeacherTableMap::DATABASE_NAME)->getTable(TeacherTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Teacher or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Teacher object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ?ConnectionInterface $con = null): int
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TeacherTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Models\Teacher) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(TeacherTableMap::DATABASE_NAME);
            $criteria->add(TeacherTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = TeacherQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            TeacherTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                TeacherTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the teacher table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return TeacherQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Teacher or Criteria object.
     *
     * @param mixed $criteria Criteria or Teacher object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TeacherTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Teacher object
        }

        if ($criteria->containsKey(TeacherTableMap::COL_ID) && $criteria->keyContainsValue(TeacherTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.TeacherTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = TeacherQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
