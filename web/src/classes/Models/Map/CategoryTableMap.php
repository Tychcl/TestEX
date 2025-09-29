<?php

namespace Models\Map;

use Models\Category;
use Models\CategoryQuery;
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
 * This class defines the structure of the 'category' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class CategoryTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Models.Map.CategoryTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'category';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Category';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Models\\Category';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Models.Category';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 8;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 8;

    /**
     * the column name for the id field
     */
    public const COL_ID = 'category.id';

    /**
     * the column name for the teacherId field
     */
    public const COL_TEACHERID = 'category.teacherId';

    /**
     * the column name for the organ field
     */
    public const COL_ORGAN = 'category.organ';

    /**
     * the column name for the date field
     */
    public const COL_DATE = 'category.date';

    /**
     * the column name for the num field
     */
    public const COL_NUM = 'category.num';

    /**
     * the column name for the post field
     */
    public const COL_POST = 'category.post';

    /**
     * the column name for the place field
     */
    public const COL_PLACE = 'category.place';

    /**
     * the column name for the categoryId field
     */
    public const COL_CATEGORYID = 'category.categoryId';

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
        self::TYPE_PHPNAME       => ['Id', 'Teacherid', 'Organ', 'Date', 'Num', 'Post', 'Place', 'Categoryid', ],
        self::TYPE_CAMELNAME     => ['id', 'teacherid', 'organ', 'date', 'num', 'post', 'place', 'categoryid', ],
        self::TYPE_COLNAME       => [CategoryTableMap::COL_ID, CategoryTableMap::COL_TEACHERID, CategoryTableMap::COL_ORGAN, CategoryTableMap::COL_DATE, CategoryTableMap::COL_NUM, CategoryTableMap::COL_POST, CategoryTableMap::COL_PLACE, CategoryTableMap::COL_CATEGORYID, ],
        self::TYPE_FIELDNAME     => ['id', 'teacherId', 'organ', 'date', 'num', 'post', 'place', 'categoryId', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'Teacherid' => 1, 'Organ' => 2, 'Date' => 3, 'Num' => 4, 'Post' => 5, 'Place' => 6, 'Categoryid' => 7, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'teacherid' => 1, 'organ' => 2, 'date' => 3, 'num' => 4, 'post' => 5, 'place' => 6, 'categoryid' => 7, ],
        self::TYPE_COLNAME       => [CategoryTableMap::COL_ID => 0, CategoryTableMap::COL_TEACHERID => 1, CategoryTableMap::COL_ORGAN => 2, CategoryTableMap::COL_DATE => 3, CategoryTableMap::COL_NUM => 4, CategoryTableMap::COL_POST => 5, CategoryTableMap::COL_PLACE => 6, CategoryTableMap::COL_CATEGORYID => 7, ],
        self::TYPE_FIELDNAME     => ['id' => 0, 'teacherId' => 1, 'organ' => 2, 'date' => 3, 'num' => 4, 'post' => 5, 'place' => 6, 'categoryId' => 7, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ID',
        'Category.Id' => 'ID',
        'id' => 'ID',
        'category.id' => 'ID',
        'CategoryTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'Teacherid' => 'TEACHERID',
        'Category.Teacherid' => 'TEACHERID',
        'teacherid' => 'TEACHERID',
        'category.teacherid' => 'TEACHERID',
        'CategoryTableMap::COL_TEACHERID' => 'TEACHERID',
        'COL_TEACHERID' => 'TEACHERID',
        'teacherId' => 'TEACHERID',
        'category.teacherId' => 'TEACHERID',
        'Organ' => 'ORGAN',
        'Category.Organ' => 'ORGAN',
        'organ' => 'ORGAN',
        'category.organ' => 'ORGAN',
        'CategoryTableMap::COL_ORGAN' => 'ORGAN',
        'COL_ORGAN' => 'ORGAN',
        'Date' => 'DATE',
        'Category.Date' => 'DATE',
        'date' => 'DATE',
        'category.date' => 'DATE',
        'CategoryTableMap::COL_DATE' => 'DATE',
        'COL_DATE' => 'DATE',
        'Num' => 'NUM',
        'Category.Num' => 'NUM',
        'num' => 'NUM',
        'category.num' => 'NUM',
        'CategoryTableMap::COL_NUM' => 'NUM',
        'COL_NUM' => 'NUM',
        'Post' => 'POST',
        'Category.Post' => 'POST',
        'post' => 'POST',
        'category.post' => 'POST',
        'CategoryTableMap::COL_POST' => 'POST',
        'COL_POST' => 'POST',
        'Place' => 'PLACE',
        'Category.Place' => 'PLACE',
        'place' => 'PLACE',
        'category.place' => 'PLACE',
        'CategoryTableMap::COL_PLACE' => 'PLACE',
        'COL_PLACE' => 'PLACE',
        'Categoryid' => 'CATEGORYID',
        'Category.Categoryid' => 'CATEGORYID',
        'categoryid' => 'CATEGORYID',
        'category.categoryid' => 'CATEGORYID',
        'CategoryTableMap::COL_CATEGORYID' => 'CATEGORYID',
        'COL_CATEGORYID' => 'CATEGORYID',
        'categoryId' => 'CATEGORYID',
        'category.categoryId' => 'CATEGORYID',
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
        $this->setName('category');
        $this->setPhpName('Category');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Models\\Category');
        $this->setPackage('Models');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('teacherId', 'Teacherid', 'INTEGER', 'teacher', 'id', true, null, null);
        $this->addColumn('organ', 'Organ', 'LONGVARCHAR', true, null, null);
        $this->addColumn('date', 'Date', 'DATE', true, null, null);
        $this->addColumn('num', 'Num', 'INTEGER', true, null, null);
        $this->addColumn('post', 'Post', 'LONGVARCHAR', true, null, null);
        $this->addColumn('place', 'Place', 'LONGVARCHAR', true, null, null);
        $this->addForeignKey('categoryId', 'Categoryid', 'INTEGER', 'categoryList', 'id', true, null, null);
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
        $this->addRelation('Categorylist', '\\Models\\Categorylist', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':categoryId',
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
        return $withPrefix ? CategoryTableMap::CLASS_DEFAULT : CategoryTableMap::OM_CLASS;
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
     * @return array (Category object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = CategoryTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = CategoryTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + CategoryTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CategoryTableMap::OM_CLASS;
            /** @var Category $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            CategoryTableMap::addInstanceToPool($obj, $key);
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
            $key = CategoryTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = CategoryTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Category $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CategoryTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(CategoryTableMap::COL_ID);
            $criteria->addSelectColumn(CategoryTableMap::COL_TEACHERID);
            $criteria->addSelectColumn(CategoryTableMap::COL_ORGAN);
            $criteria->addSelectColumn(CategoryTableMap::COL_DATE);
            $criteria->addSelectColumn(CategoryTableMap::COL_NUM);
            $criteria->addSelectColumn(CategoryTableMap::COL_POST);
            $criteria->addSelectColumn(CategoryTableMap::COL_PLACE);
            $criteria->addSelectColumn(CategoryTableMap::COL_CATEGORYID);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.teacherId');
            $criteria->addSelectColumn($alias . '.organ');
            $criteria->addSelectColumn($alias . '.date');
            $criteria->addSelectColumn($alias . '.num');
            $criteria->addSelectColumn($alias . '.post');
            $criteria->addSelectColumn($alias . '.place');
            $criteria->addSelectColumn($alias . '.categoryId');
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
            $criteria->removeSelectColumn(CategoryTableMap::COL_ID);
            $criteria->removeSelectColumn(CategoryTableMap::COL_TEACHERID);
            $criteria->removeSelectColumn(CategoryTableMap::COL_ORGAN);
            $criteria->removeSelectColumn(CategoryTableMap::COL_DATE);
            $criteria->removeSelectColumn(CategoryTableMap::COL_NUM);
            $criteria->removeSelectColumn(CategoryTableMap::COL_POST);
            $criteria->removeSelectColumn(CategoryTableMap::COL_PLACE);
            $criteria->removeSelectColumn(CategoryTableMap::COL_CATEGORYID);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.teacherId');
            $criteria->removeSelectColumn($alias . '.organ');
            $criteria->removeSelectColumn($alias . '.date');
            $criteria->removeSelectColumn($alias . '.num');
            $criteria->removeSelectColumn($alias . '.post');
            $criteria->removeSelectColumn($alias . '.place');
            $criteria->removeSelectColumn($alias . '.categoryId');
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
        return Propel::getServiceContainer()->getDatabaseMap(CategoryTableMap::DATABASE_NAME)->getTable(CategoryTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Category or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Category object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(CategoryTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Models\Category) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CategoryTableMap::DATABASE_NAME);
            $criteria->add(CategoryTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = CategoryQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            CategoryTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                CategoryTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the category table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return CategoryQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Category or Criteria object.
     *
     * @param mixed $criteria Criteria or Category object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CategoryTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Category object
        }

        if ($criteria->containsKey(CategoryTableMap::COL_ID) && $criteria->keyContainsValue(CategoryTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.CategoryTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = CategoryQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
