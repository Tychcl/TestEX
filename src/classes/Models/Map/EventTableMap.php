<?php

namespace Models\Map;

use Models\Event;
use Models\EventQuery;
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
 * This class defines the structure of the 'event' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class EventTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Models.Map.EventTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'event';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Event';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Models\\Event';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Models.Event';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 7;

    /**
     * the column name for the id field
     */
    public const COL_ID = 'event.id';

    /**
     * the column name for the infoId field
     */
    public const COL_INFOID = 'event.infoId';

    /**
     * the column name for the teacherId field
     */
    public const COL_TEACHERID = 'event.teacherId';

    /**
     * the column name for the teacherRoleId field
     */
    public const COL_TEACHERROLEID = 'event.teacherRoleId';

    /**
     * the column name for the studentId field
     */
    public const COL_STUDENTID = 'event.studentId';

    /**
     * the column name for the awardId field
     */
    public const COL_AWARDID = 'event.awardId';

    /**
     * the column name for the document field
     */
    public const COL_DOCUMENT = 'event.document';

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
        self::TYPE_PHPNAME       => ['Id', 'Infoid', 'Teacherid', 'Teacherroleid', 'Studentid', 'Awardid', 'Document', ],
        self::TYPE_CAMELNAME     => ['id', 'infoid', 'teacherid', 'teacherroleid', 'studentid', 'awardid', 'document', ],
        self::TYPE_COLNAME       => [EventTableMap::COL_ID, EventTableMap::COL_INFOID, EventTableMap::COL_TEACHERID, EventTableMap::COL_TEACHERROLEID, EventTableMap::COL_STUDENTID, EventTableMap::COL_AWARDID, EventTableMap::COL_DOCUMENT, ],
        self::TYPE_FIELDNAME     => ['id', 'infoId', 'teacherId', 'teacherRoleId', 'studentId', 'awardId', 'document', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'Infoid' => 1, 'Teacherid' => 2, 'Teacherroleid' => 3, 'Studentid' => 4, 'Awardid' => 5, 'Document' => 6, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'infoid' => 1, 'teacherid' => 2, 'teacherroleid' => 3, 'studentid' => 4, 'awardid' => 5, 'document' => 6, ],
        self::TYPE_COLNAME       => [EventTableMap::COL_ID => 0, EventTableMap::COL_INFOID => 1, EventTableMap::COL_TEACHERID => 2, EventTableMap::COL_TEACHERROLEID => 3, EventTableMap::COL_STUDENTID => 4, EventTableMap::COL_AWARDID => 5, EventTableMap::COL_DOCUMENT => 6, ],
        self::TYPE_FIELDNAME     => ['id' => 0, 'infoId' => 1, 'teacherId' => 2, 'teacherRoleId' => 3, 'studentId' => 4, 'awardId' => 5, 'document' => 6, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ID',
        'Event.Id' => 'ID',
        'id' => 'ID',
        'event.id' => 'ID',
        'EventTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'Infoid' => 'INFOID',
        'Event.Infoid' => 'INFOID',
        'infoid' => 'INFOID',
        'event.infoid' => 'INFOID',
        'EventTableMap::COL_INFOID' => 'INFOID',
        'COL_INFOID' => 'INFOID',
        'infoId' => 'INFOID',
        'event.infoId' => 'INFOID',
        'Teacherid' => 'TEACHERID',
        'Event.Teacherid' => 'TEACHERID',
        'teacherid' => 'TEACHERID',
        'event.teacherid' => 'TEACHERID',
        'EventTableMap::COL_TEACHERID' => 'TEACHERID',
        'COL_TEACHERID' => 'TEACHERID',
        'teacherId' => 'TEACHERID',
        'event.teacherId' => 'TEACHERID',
        'Teacherroleid' => 'TEACHERROLEID',
        'Event.Teacherroleid' => 'TEACHERROLEID',
        'teacherroleid' => 'TEACHERROLEID',
        'event.teacherroleid' => 'TEACHERROLEID',
        'EventTableMap::COL_TEACHERROLEID' => 'TEACHERROLEID',
        'COL_TEACHERROLEID' => 'TEACHERROLEID',
        'teacherRoleId' => 'TEACHERROLEID',
        'event.teacherRoleId' => 'TEACHERROLEID',
        'Studentid' => 'STUDENTID',
        'Event.Studentid' => 'STUDENTID',
        'studentid' => 'STUDENTID',
        'event.studentid' => 'STUDENTID',
        'EventTableMap::COL_STUDENTID' => 'STUDENTID',
        'COL_STUDENTID' => 'STUDENTID',
        'studentId' => 'STUDENTID',
        'event.studentId' => 'STUDENTID',
        'Awardid' => 'AWARDID',
        'Event.Awardid' => 'AWARDID',
        'awardid' => 'AWARDID',
        'event.awardid' => 'AWARDID',
        'EventTableMap::COL_AWARDID' => 'AWARDID',
        'COL_AWARDID' => 'AWARDID',
        'awardId' => 'AWARDID',
        'event.awardId' => 'AWARDID',
        'Document' => 'DOCUMENT',
        'Event.Document' => 'DOCUMENT',
        'document' => 'DOCUMENT',
        'event.document' => 'DOCUMENT',
        'EventTableMap::COL_DOCUMENT' => 'DOCUMENT',
        'COL_DOCUMENT' => 'DOCUMENT',
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
        $this->setName('event');
        $this->setPhpName('Event');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Models\\Event');
        $this->setPackage('Models');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('infoId', 'Infoid', 'INTEGER', 'eventInfo', 'id', true, null, null);
        $this->addForeignKey('teacherId', 'Teacherid', 'INTEGER', 'teacher', 'id', true, null, null);
        $this->addForeignKey('teacherRoleId', 'Teacherroleid', 'INTEGER', 'eventRole', 'id', true, null, null);
        $this->addForeignKey('studentId', 'Studentid', 'INTEGER', 'student', 'id', true, null, null);
        $this->addForeignKey('awardId', 'Awardid', 'INTEGER', 'eventAwardDegree', 'id', true, null, null);
        $this->addColumn('document', 'Document', 'LONGVARCHAR', true, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('Eventinfo', '\\Models\\Eventinfo', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':infoId',
    1 => ':id',
  ),
), 'RESTRICT', 'CASCADE', null, false);
        $this->addRelation('Teacher', '\\Models\\Teacher', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':teacherId',
    1 => ':id',
  ),
), 'RESTRICT', 'CASCADE', null, false);
        $this->addRelation('Eventrole', '\\Models\\Eventrole', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':teacherRoleId',
    1 => ':id',
  ),
), 'RESTRICT', 'CASCADE', null, false);
        $this->addRelation('Student', '\\Models\\Student', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':studentId',
    1 => ':id',
  ),
), 'RESTRICT', 'CASCADE', null, false);
        $this->addRelation('Eventawarddegree', '\\Models\\Eventawarddegree', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':awardId',
    1 => ':id',
  ),
), 'RESTRICT', 'CASCADE', null, false);
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
        return $withPrefix ? EventTableMap::CLASS_DEFAULT : EventTableMap::OM_CLASS;
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
     * @return array (Event object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = EventTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = EventTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + EventTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = EventTableMap::OM_CLASS;
            /** @var Event $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            EventTableMap::addInstanceToPool($obj, $key);
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
            $key = EventTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = EventTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Event $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                EventTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(EventTableMap::COL_ID);
            $criteria->addSelectColumn(EventTableMap::COL_INFOID);
            $criteria->addSelectColumn(EventTableMap::COL_TEACHERID);
            $criteria->addSelectColumn(EventTableMap::COL_TEACHERROLEID);
            $criteria->addSelectColumn(EventTableMap::COL_STUDENTID);
            $criteria->addSelectColumn(EventTableMap::COL_AWARDID);
            $criteria->addSelectColumn(EventTableMap::COL_DOCUMENT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.infoId');
            $criteria->addSelectColumn($alias . '.teacherId');
            $criteria->addSelectColumn($alias . '.teacherRoleId');
            $criteria->addSelectColumn($alias . '.studentId');
            $criteria->addSelectColumn($alias . '.awardId');
            $criteria->addSelectColumn($alias . '.document');
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
            $criteria->removeSelectColumn(EventTableMap::COL_ID);
            $criteria->removeSelectColumn(EventTableMap::COL_INFOID);
            $criteria->removeSelectColumn(EventTableMap::COL_TEACHERID);
            $criteria->removeSelectColumn(EventTableMap::COL_TEACHERROLEID);
            $criteria->removeSelectColumn(EventTableMap::COL_STUDENTID);
            $criteria->removeSelectColumn(EventTableMap::COL_AWARDID);
            $criteria->removeSelectColumn(EventTableMap::COL_DOCUMENT);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.infoId');
            $criteria->removeSelectColumn($alias . '.teacherId');
            $criteria->removeSelectColumn($alias . '.teacherRoleId');
            $criteria->removeSelectColumn($alias . '.studentId');
            $criteria->removeSelectColumn($alias . '.awardId');
            $criteria->removeSelectColumn($alias . '.document');
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
        return Propel::getServiceContainer()->getDatabaseMap(EventTableMap::DATABASE_NAME)->getTable(EventTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Event or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Event object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Models\Event) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(EventTableMap::DATABASE_NAME);
            $criteria->add(EventTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = EventQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            EventTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                EventTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the event table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return EventQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Event or Criteria object.
     *
     * @param mixed $criteria Criteria or Event object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Event object
        }

        if ($criteria->containsKey(EventTableMap::COL_ID) && $criteria->keyContainsValue(EventTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.EventTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = EventQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
