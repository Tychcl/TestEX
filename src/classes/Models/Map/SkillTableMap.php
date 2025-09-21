<?php

namespace Models\Map;

use Models\Skill;
use Models\SkillQuery;
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
 * This class defines the structure of the 'skill' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class SkillTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Models.Map.SkillTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'skill';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Skill';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Models\\Skill';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Models.Skill';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 14;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 14;

    /**
     * the column name for the id field
     */
    public const COL_ID = 'skill.id';

    /**
     * the column name for the teacherId field
     */
    public const COL_TEACHERID = 'skill.teacherId';

    /**
     * the column name for the num field
     */
    public const COL_NUM = 'skill.num';

    /**
     * the column name for the regNum field
     */
    public const COL_REGNUM = 'skill.regNum';

    /**
     * the column name for the city field
     */
    public const COL_CITY = 'skill.city';

    /**
     * the column name for the date field
     */
    public const COL_DATE = 'skill.date';

    /**
     * the column name for the place field
     */
    public const COL_PLACE = 'skill.place';

    /**
     * the column name for the start field
     */
    public const COL_START = 'skill.start';

    /**
     * the column name for the end field
     */
    public const COL_END = 'skill.end';

    /**
     * the column name for the theme field
     */
    public const COL_THEME = 'skill.theme';

    /**
     * the column name for the hours field
     */
    public const COL_HOURS = 'skill.hours';

    /**
     * the column name for the director field
     */
    public const COL_DIRECTOR = 'skill.director';

    /**
     * the column name for the secretary field
     */
    public const COL_SECRETARY = 'skill.secretary';

    /**
     * the column name for the docPath field
     */
    public const COL_DOCPATH = 'skill.docPath';

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
        self::TYPE_PHPNAME       => ['Id', 'Teacherid', 'Num', 'Regnum', 'City', 'Date', 'Place', 'Start', 'End', 'Theme', 'Hours', 'Director', 'Secretary', 'Docpath', ],
        self::TYPE_CAMELNAME     => ['id', 'teacherid', 'num', 'regnum', 'city', 'date', 'place', 'start', 'end', 'theme', 'hours', 'director', 'secretary', 'docpath', ],
        self::TYPE_COLNAME       => [SkillTableMap::COL_ID, SkillTableMap::COL_TEACHERID, SkillTableMap::COL_NUM, SkillTableMap::COL_REGNUM, SkillTableMap::COL_CITY, SkillTableMap::COL_DATE, SkillTableMap::COL_PLACE, SkillTableMap::COL_START, SkillTableMap::COL_END, SkillTableMap::COL_THEME, SkillTableMap::COL_HOURS, SkillTableMap::COL_DIRECTOR, SkillTableMap::COL_SECRETARY, SkillTableMap::COL_DOCPATH, ],
        self::TYPE_FIELDNAME     => ['id', 'teacherId', 'num', 'regNum', 'city', 'date', 'place', 'start', 'end', 'theme', 'hours', 'director', 'secretary', 'docPath', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'Teacherid' => 1, 'Num' => 2, 'Regnum' => 3, 'City' => 4, 'Date' => 5, 'Place' => 6, 'Start' => 7, 'End' => 8, 'Theme' => 9, 'Hours' => 10, 'Director' => 11, 'Secretary' => 12, 'Docpath' => 13, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'teacherid' => 1, 'num' => 2, 'regnum' => 3, 'city' => 4, 'date' => 5, 'place' => 6, 'start' => 7, 'end' => 8, 'theme' => 9, 'hours' => 10, 'director' => 11, 'secretary' => 12, 'docpath' => 13, ],
        self::TYPE_COLNAME       => [SkillTableMap::COL_ID => 0, SkillTableMap::COL_TEACHERID => 1, SkillTableMap::COL_NUM => 2, SkillTableMap::COL_REGNUM => 3, SkillTableMap::COL_CITY => 4, SkillTableMap::COL_DATE => 5, SkillTableMap::COL_PLACE => 6, SkillTableMap::COL_START => 7, SkillTableMap::COL_END => 8, SkillTableMap::COL_THEME => 9, SkillTableMap::COL_HOURS => 10, SkillTableMap::COL_DIRECTOR => 11, SkillTableMap::COL_SECRETARY => 12, SkillTableMap::COL_DOCPATH => 13, ],
        self::TYPE_FIELDNAME     => ['id' => 0, 'teacherId' => 1, 'num' => 2, 'regNum' => 3, 'city' => 4, 'date' => 5, 'place' => 6, 'start' => 7, 'end' => 8, 'theme' => 9, 'hours' => 10, 'director' => 11, 'secretary' => 12, 'docPath' => 13, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ID',
        'Skill.Id' => 'ID',
        'id' => 'ID',
        'skill.id' => 'ID',
        'SkillTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'Teacherid' => 'TEACHERID',
        'Skill.Teacherid' => 'TEACHERID',
        'teacherid' => 'TEACHERID',
        'skill.teacherid' => 'TEACHERID',
        'SkillTableMap::COL_TEACHERID' => 'TEACHERID',
        'COL_TEACHERID' => 'TEACHERID',
        'teacherId' => 'TEACHERID',
        'skill.teacherId' => 'TEACHERID',
        'Num' => 'NUM',
        'Skill.Num' => 'NUM',
        'num' => 'NUM',
        'skill.num' => 'NUM',
        'SkillTableMap::COL_NUM' => 'NUM',
        'COL_NUM' => 'NUM',
        'Regnum' => 'REGNUM',
        'Skill.Regnum' => 'REGNUM',
        'regnum' => 'REGNUM',
        'skill.regnum' => 'REGNUM',
        'SkillTableMap::COL_REGNUM' => 'REGNUM',
        'COL_REGNUM' => 'REGNUM',
        'regNum' => 'REGNUM',
        'skill.regNum' => 'REGNUM',
        'City' => 'CITY',
        'Skill.City' => 'CITY',
        'city' => 'CITY',
        'skill.city' => 'CITY',
        'SkillTableMap::COL_CITY' => 'CITY',
        'COL_CITY' => 'CITY',
        'Date' => 'DATE',
        'Skill.Date' => 'DATE',
        'date' => 'DATE',
        'skill.date' => 'DATE',
        'SkillTableMap::COL_DATE' => 'DATE',
        'COL_DATE' => 'DATE',
        'Place' => 'PLACE',
        'Skill.Place' => 'PLACE',
        'place' => 'PLACE',
        'skill.place' => 'PLACE',
        'SkillTableMap::COL_PLACE' => 'PLACE',
        'COL_PLACE' => 'PLACE',
        'Start' => 'START',
        'Skill.Start' => 'START',
        'start' => 'START',
        'skill.start' => 'START',
        'SkillTableMap::COL_START' => 'START',
        'COL_START' => 'START',
        'End' => 'END',
        'Skill.End' => 'END',
        'end' => 'END',
        'skill.end' => 'END',
        'SkillTableMap::COL_END' => 'END',
        'COL_END' => 'END',
        'Theme' => 'THEME',
        'Skill.Theme' => 'THEME',
        'theme' => 'THEME',
        'skill.theme' => 'THEME',
        'SkillTableMap::COL_THEME' => 'THEME',
        'COL_THEME' => 'THEME',
        'Hours' => 'HOURS',
        'Skill.Hours' => 'HOURS',
        'hours' => 'HOURS',
        'skill.hours' => 'HOURS',
        'SkillTableMap::COL_HOURS' => 'HOURS',
        'COL_HOURS' => 'HOURS',
        'Director' => 'DIRECTOR',
        'Skill.Director' => 'DIRECTOR',
        'director' => 'DIRECTOR',
        'skill.director' => 'DIRECTOR',
        'SkillTableMap::COL_DIRECTOR' => 'DIRECTOR',
        'COL_DIRECTOR' => 'DIRECTOR',
        'Secretary' => 'SECRETARY',
        'Skill.Secretary' => 'SECRETARY',
        'secretary' => 'SECRETARY',
        'skill.secretary' => 'SECRETARY',
        'SkillTableMap::COL_SECRETARY' => 'SECRETARY',
        'COL_SECRETARY' => 'SECRETARY',
        'Docpath' => 'DOCPATH',
        'Skill.Docpath' => 'DOCPATH',
        'docpath' => 'DOCPATH',
        'skill.docpath' => 'DOCPATH',
        'SkillTableMap::COL_DOCPATH' => 'DOCPATH',
        'COL_DOCPATH' => 'DOCPATH',
        'docPath' => 'DOCPATH',
        'skill.docPath' => 'DOCPATH',
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
        $this->setName('skill');
        $this->setPhpName('Skill');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Models\\Skill');
        $this->setPackage('Models');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('teacherId', 'Teacherid', 'INTEGER', 'teacher', 'id', true, null, null);
        $this->addColumn('num', 'Num', 'INTEGER', true, null, null);
        $this->addColumn('regNum', 'Regnum', 'INTEGER', true, null, null);
        $this->addColumn('city', 'City', 'VARCHAR', true, 65, null);
        $this->addColumn('date', 'Date', 'DATE', true, null, null);
        $this->addColumn('place', 'Place', 'LONGVARCHAR', true, null, null);
        $this->addColumn('start', 'Start', 'DATE', true, null, null);
        $this->addColumn('end', 'End', 'DATE', true, null, null);
        $this->addColumn('theme', 'Theme', 'LONGVARCHAR', true, null, null);
        $this->addColumn('hours', 'Hours', 'INTEGER', true, null, null);
        $this->addColumn('director', 'Director', 'VARCHAR', true, 65, null);
        $this->addColumn('secretary', 'Secretary', 'VARCHAR', true, 65, null);
        $this->addColumn('docPath', 'Docpath', 'LONGVARCHAR', true, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('Teacher', '\\Models\\Teacher', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':teacherId',
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
        return $withPrefix ? SkillTableMap::CLASS_DEFAULT : SkillTableMap::OM_CLASS;
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
     * @return array (Skill object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = SkillTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SkillTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SkillTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SkillTableMap::OM_CLASS;
            /** @var Skill $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SkillTableMap::addInstanceToPool($obj, $key);
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
            $key = SkillTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SkillTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Skill $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SkillTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(SkillTableMap::COL_ID);
            $criteria->addSelectColumn(SkillTableMap::COL_TEACHERID);
            $criteria->addSelectColumn(SkillTableMap::COL_NUM);
            $criteria->addSelectColumn(SkillTableMap::COL_REGNUM);
            $criteria->addSelectColumn(SkillTableMap::COL_CITY);
            $criteria->addSelectColumn(SkillTableMap::COL_DATE);
            $criteria->addSelectColumn(SkillTableMap::COL_PLACE);
            $criteria->addSelectColumn(SkillTableMap::COL_START);
            $criteria->addSelectColumn(SkillTableMap::COL_END);
            $criteria->addSelectColumn(SkillTableMap::COL_THEME);
            $criteria->addSelectColumn(SkillTableMap::COL_HOURS);
            $criteria->addSelectColumn(SkillTableMap::COL_DIRECTOR);
            $criteria->addSelectColumn(SkillTableMap::COL_SECRETARY);
            $criteria->addSelectColumn(SkillTableMap::COL_DOCPATH);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.teacherId');
            $criteria->addSelectColumn($alias . '.num');
            $criteria->addSelectColumn($alias . '.regNum');
            $criteria->addSelectColumn($alias . '.city');
            $criteria->addSelectColumn($alias . '.date');
            $criteria->addSelectColumn($alias . '.place');
            $criteria->addSelectColumn($alias . '.start');
            $criteria->addSelectColumn($alias . '.end');
            $criteria->addSelectColumn($alias . '.theme');
            $criteria->addSelectColumn($alias . '.hours');
            $criteria->addSelectColumn($alias . '.director');
            $criteria->addSelectColumn($alias . '.secretary');
            $criteria->addSelectColumn($alias . '.docPath');
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
            $criteria->removeSelectColumn(SkillTableMap::COL_ID);
            $criteria->removeSelectColumn(SkillTableMap::COL_TEACHERID);
            $criteria->removeSelectColumn(SkillTableMap::COL_NUM);
            $criteria->removeSelectColumn(SkillTableMap::COL_REGNUM);
            $criteria->removeSelectColumn(SkillTableMap::COL_CITY);
            $criteria->removeSelectColumn(SkillTableMap::COL_DATE);
            $criteria->removeSelectColumn(SkillTableMap::COL_PLACE);
            $criteria->removeSelectColumn(SkillTableMap::COL_START);
            $criteria->removeSelectColumn(SkillTableMap::COL_END);
            $criteria->removeSelectColumn(SkillTableMap::COL_THEME);
            $criteria->removeSelectColumn(SkillTableMap::COL_HOURS);
            $criteria->removeSelectColumn(SkillTableMap::COL_DIRECTOR);
            $criteria->removeSelectColumn(SkillTableMap::COL_SECRETARY);
            $criteria->removeSelectColumn(SkillTableMap::COL_DOCPATH);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.teacherId');
            $criteria->removeSelectColumn($alias . '.num');
            $criteria->removeSelectColumn($alias . '.regNum');
            $criteria->removeSelectColumn($alias . '.city');
            $criteria->removeSelectColumn($alias . '.date');
            $criteria->removeSelectColumn($alias . '.place');
            $criteria->removeSelectColumn($alias . '.start');
            $criteria->removeSelectColumn($alias . '.end');
            $criteria->removeSelectColumn($alias . '.theme');
            $criteria->removeSelectColumn($alias . '.hours');
            $criteria->removeSelectColumn($alias . '.director');
            $criteria->removeSelectColumn($alias . '.secretary');
            $criteria->removeSelectColumn($alias . '.docPath');
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
        return Propel::getServiceContainer()->getDatabaseMap(SkillTableMap::DATABASE_NAME)->getTable(SkillTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Skill or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Skill object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(SkillTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Models\Skill) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SkillTableMap::DATABASE_NAME);
            $criteria->add(SkillTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = SkillQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            SkillTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                SkillTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the skill table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return SkillQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Skill or Criteria object.
     *
     * @param mixed $criteria Criteria or Skill object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SkillTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Skill object
        }

        if ($criteria->containsKey(SkillTableMap::COL_ID) && $criteria->keyContainsValue(SkillTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.SkillTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = SkillQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
