<?php

namespace Base;

use \Category as ChildCategory;
use \CategoryQuery as ChildCategoryQuery;
use \Exception;
use \PDO;
use Map\CategoryTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `category` table.
 *
 * @method     ChildCategoryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildCategoryQuery orderByTeacherid($order = Criteria::ASC) Order by the teacherId column
 * @method     ChildCategoryQuery orderByOrgan($order = Criteria::ASC) Order by the organ column
 * @method     ChildCategoryQuery orderByDate($order = Criteria::ASC) Order by the date column
 * @method     ChildCategoryQuery orderByNum($order = Criteria::ASC) Order by the num column
 * @method     ChildCategoryQuery orderByPost($order = Criteria::ASC) Order by the post column
 * @method     ChildCategoryQuery orderByPlace($order = Criteria::ASC) Order by the place column
 * @method     ChildCategoryQuery orderByCategoryid($order = Criteria::ASC) Order by the categoryId column
 *
 * @method     ChildCategoryQuery groupById() Group by the id column
 * @method     ChildCategoryQuery groupByTeacherid() Group by the teacherId column
 * @method     ChildCategoryQuery groupByOrgan() Group by the organ column
 * @method     ChildCategoryQuery groupByDate() Group by the date column
 * @method     ChildCategoryQuery groupByNum() Group by the num column
 * @method     ChildCategoryQuery groupByPost() Group by the post column
 * @method     ChildCategoryQuery groupByPlace() Group by the place column
 * @method     ChildCategoryQuery groupByCategoryid() Group by the categoryId column
 *
 * @method     ChildCategoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCategoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCategoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCategoryQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCategoryQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCategoryQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCategoryQuery leftJoinTeacher($relationAlias = null) Adds a LEFT JOIN clause to the query using the Teacher relation
 * @method     ChildCategoryQuery rightJoinTeacher($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Teacher relation
 * @method     ChildCategoryQuery innerJoinTeacher($relationAlias = null) Adds a INNER JOIN clause to the query using the Teacher relation
 *
 * @method     ChildCategoryQuery joinWithTeacher($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Teacher relation
 *
 * @method     ChildCategoryQuery leftJoinWithTeacher() Adds a LEFT JOIN clause and with to the query using the Teacher relation
 * @method     ChildCategoryQuery rightJoinWithTeacher() Adds a RIGHT JOIN clause and with to the query using the Teacher relation
 * @method     ChildCategoryQuery innerJoinWithTeacher() Adds a INNER JOIN clause and with to the query using the Teacher relation
 *
 * @method     ChildCategoryQuery leftJoinCategorylist($relationAlias = null) Adds a LEFT JOIN clause to the query using the Categorylist relation
 * @method     ChildCategoryQuery rightJoinCategorylist($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Categorylist relation
 * @method     ChildCategoryQuery innerJoinCategorylist($relationAlias = null) Adds a INNER JOIN clause to the query using the Categorylist relation
 *
 * @method     ChildCategoryQuery joinWithCategorylist($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Categorylist relation
 *
 * @method     ChildCategoryQuery leftJoinWithCategorylist() Adds a LEFT JOIN clause and with to the query using the Categorylist relation
 * @method     ChildCategoryQuery rightJoinWithCategorylist() Adds a RIGHT JOIN clause and with to the query using the Categorylist relation
 * @method     ChildCategoryQuery innerJoinWithCategorylist() Adds a INNER JOIN clause and with to the query using the Categorylist relation
 *
 * @method     \TeacherQuery|\CategorylistQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildCategory|null findOne(?ConnectionInterface $con = null) Return the first ChildCategory matching the query
 * @method     ChildCategory findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildCategory matching the query, or a new ChildCategory object populated from the query conditions when no match is found
 *
 * @method     ChildCategory|null findOneById(int $id) Return the first ChildCategory filtered by the id column
 * @method     ChildCategory|null findOneByTeacherid(int $teacherId) Return the first ChildCategory filtered by the teacherId column
 * @method     ChildCategory|null findOneByOrgan(string $organ) Return the first ChildCategory filtered by the organ column
 * @method     ChildCategory|null findOneByDate(string $date) Return the first ChildCategory filtered by the date column
 * @method     ChildCategory|null findOneByNum(int $num) Return the first ChildCategory filtered by the num column
 * @method     ChildCategory|null findOneByPost(string $post) Return the first ChildCategory filtered by the post column
 * @method     ChildCategory|null findOneByPlace(string $place) Return the first ChildCategory filtered by the place column
 * @method     ChildCategory|null findOneByCategoryid(int $categoryId) Return the first ChildCategory filtered by the categoryId column
 *
 * @method     ChildCategory requirePk($key, ?ConnectionInterface $con = null) Return the ChildCategory by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCategory requireOne(?ConnectionInterface $con = null) Return the first ChildCategory matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCategory requireOneById(int $id) Return the first ChildCategory filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCategory requireOneByTeacherid(int $teacherId) Return the first ChildCategory filtered by the teacherId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCategory requireOneByOrgan(string $organ) Return the first ChildCategory filtered by the organ column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCategory requireOneByDate(string $date) Return the first ChildCategory filtered by the date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCategory requireOneByNum(int $num) Return the first ChildCategory filtered by the num column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCategory requireOneByPost(string $post) Return the first ChildCategory filtered by the post column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCategory requireOneByPlace(string $place) Return the first ChildCategory filtered by the place column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCategory requireOneByCategoryid(int $categoryId) Return the first ChildCategory filtered by the categoryId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCategory[]|Collection find(?ConnectionInterface $con = null) Return ChildCategory objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildCategory> find(?ConnectionInterface $con = null) Return ChildCategory objects based on current ModelCriteria
 *
 * @method     ChildCategory[]|Collection findById(int|array<int> $id) Return ChildCategory objects filtered by the id column
 * @psalm-method Collection&\Traversable<ChildCategory> findById(int|array<int> $id) Return ChildCategory objects filtered by the id column
 * @method     ChildCategory[]|Collection findByTeacherid(int|array<int> $teacherId) Return ChildCategory objects filtered by the teacherId column
 * @psalm-method Collection&\Traversable<ChildCategory> findByTeacherid(int|array<int> $teacherId) Return ChildCategory objects filtered by the teacherId column
 * @method     ChildCategory[]|Collection findByOrgan(string|array<string> $organ) Return ChildCategory objects filtered by the organ column
 * @psalm-method Collection&\Traversable<ChildCategory> findByOrgan(string|array<string> $organ) Return ChildCategory objects filtered by the organ column
 * @method     ChildCategory[]|Collection findByDate(string|array<string> $date) Return ChildCategory objects filtered by the date column
 * @psalm-method Collection&\Traversable<ChildCategory> findByDate(string|array<string> $date) Return ChildCategory objects filtered by the date column
 * @method     ChildCategory[]|Collection findByNum(int|array<int> $num) Return ChildCategory objects filtered by the num column
 * @psalm-method Collection&\Traversable<ChildCategory> findByNum(int|array<int> $num) Return ChildCategory objects filtered by the num column
 * @method     ChildCategory[]|Collection findByPost(string|array<string> $post) Return ChildCategory objects filtered by the post column
 * @psalm-method Collection&\Traversable<ChildCategory> findByPost(string|array<string> $post) Return ChildCategory objects filtered by the post column
 * @method     ChildCategory[]|Collection findByPlace(string|array<string> $place) Return ChildCategory objects filtered by the place column
 * @psalm-method Collection&\Traversable<ChildCategory> findByPlace(string|array<string> $place) Return ChildCategory objects filtered by the place column
 * @method     ChildCategory[]|Collection findByCategoryid(int|array<int> $categoryId) Return ChildCategory objects filtered by the categoryId column
 * @psalm-method Collection&\Traversable<ChildCategory> findByCategoryid(int|array<int> $categoryId) Return ChildCategory objects filtered by the categoryId column
 *
 * @method     ChildCategory[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildCategory> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class CategoryQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\CategoryQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Category', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCategoryQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCategoryQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildCategoryQuery) {
            return $criteria;
        }
        $query = new ChildCategoryQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildCategory|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CategoryTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CategoryTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCategory A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, teacherId, organ, date, num, post, place, categoryId FROM category WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildCategory $obj */
            $obj = new ChildCategory();
            $obj->hydrate($row);
            CategoryTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @return ChildCategory|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param array $keys Primary keys to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return Collection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param mixed $key Primary key to use for the query
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        $this->addUsingAlias(CategoryTableMap::COL_ID, $key, Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param array|int $keys The list of primary key to use for the query
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        $this->addUsingAlias(CategoryTableMap::COL_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterById($id = null, ?string $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CategoryTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CategoryTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CategoryTableMap::COL_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the teacherId column
     *
     * Example usage:
     * <code>
     * $query->filterByTeacherid(1234); // WHERE teacherId = 1234
     * $query->filterByTeacherid(array(12, 34)); // WHERE teacherId IN (12, 34)
     * $query->filterByTeacherid(array('min' => 12)); // WHERE teacherId > 12
     * </code>
     *
     * @see       filterByTeacher()
     *
     * @param mixed $teacherid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTeacherid($teacherid = null, ?string $comparison = null)
    {
        if (is_array($teacherid)) {
            $useMinMax = false;
            if (isset($teacherid['min'])) {
                $this->addUsingAlias(CategoryTableMap::COL_TEACHERID, $teacherid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($teacherid['max'])) {
                $this->addUsingAlias(CategoryTableMap::COL_TEACHERID, $teacherid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CategoryTableMap::COL_TEACHERID, $teacherid, $comparison);

        return $this;
    }

    /**
     * Filter the query on the organ column
     *
     * Example usage:
     * <code>
     * $query->filterByOrgan('fooValue');   // WHERE organ = 'fooValue'
     * $query->filterByOrgan('%fooValue%', Criteria::LIKE); // WHERE organ LIKE '%fooValue%'
     * $query->filterByOrgan(['foo', 'bar']); // WHERE organ IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $organ The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByOrgan($organ = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($organ)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CategoryTableMap::COL_ORGAN, $organ, $comparison);

        return $this;
    }

    /**
     * Filter the query on the date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE date > '2011-03-13'
     * </code>
     *
     * @param mixed $date The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDate($date = null, ?string $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(CategoryTableMap::COL_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(CategoryTableMap::COL_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CategoryTableMap::COL_DATE, $date, $comparison);

        return $this;
    }

    /**
     * Filter the query on the num column
     *
     * Example usage:
     * <code>
     * $query->filterByNum(1234); // WHERE num = 1234
     * $query->filterByNum(array(12, 34)); // WHERE num IN (12, 34)
     * $query->filterByNum(array('min' => 12)); // WHERE num > 12
     * </code>
     *
     * @param mixed $num The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNum($num = null, ?string $comparison = null)
    {
        if (is_array($num)) {
            $useMinMax = false;
            if (isset($num['min'])) {
                $this->addUsingAlias(CategoryTableMap::COL_NUM, $num['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($num['max'])) {
                $this->addUsingAlias(CategoryTableMap::COL_NUM, $num['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CategoryTableMap::COL_NUM, $num, $comparison);

        return $this;
    }

    /**
     * Filter the query on the post column
     *
     * Example usage:
     * <code>
     * $query->filterByPost('fooValue');   // WHERE post = 'fooValue'
     * $query->filterByPost('%fooValue%', Criteria::LIKE); // WHERE post LIKE '%fooValue%'
     * $query->filterByPost(['foo', 'bar']); // WHERE post IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $post The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPost($post = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($post)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CategoryTableMap::COL_POST, $post, $comparison);

        return $this;
    }

    /**
     * Filter the query on the place column
     *
     * Example usage:
     * <code>
     * $query->filterByPlace('fooValue');   // WHERE place = 'fooValue'
     * $query->filterByPlace('%fooValue%', Criteria::LIKE); // WHERE place LIKE '%fooValue%'
     * $query->filterByPlace(['foo', 'bar']); // WHERE place IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $place The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPlace($place = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($place)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CategoryTableMap::COL_PLACE, $place, $comparison);

        return $this;
    }

    /**
     * Filter the query on the categoryId column
     *
     * Example usage:
     * <code>
     * $query->filterByCategoryid(1234); // WHERE categoryId = 1234
     * $query->filterByCategoryid(array(12, 34)); // WHERE categoryId IN (12, 34)
     * $query->filterByCategoryid(array('min' => 12)); // WHERE categoryId > 12
     * </code>
     *
     * @see       filterByCategorylist()
     *
     * @param mixed $categoryid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCategoryid($categoryid = null, ?string $comparison = null)
    {
        if (is_array($categoryid)) {
            $useMinMax = false;
            if (isset($categoryid['min'])) {
                $this->addUsingAlias(CategoryTableMap::COL_CATEGORYID, $categoryid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($categoryid['max'])) {
                $this->addUsingAlias(CategoryTableMap::COL_CATEGORYID, $categoryid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CategoryTableMap::COL_CATEGORYID, $categoryid, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Teacher object
     *
     * @param \Teacher|ObjectCollection $teacher The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTeacher($teacher, ?string $comparison = null)
    {
        if ($teacher instanceof \Teacher) {
            return $this
                ->addUsingAlias(CategoryTableMap::COL_TEACHERID, $teacher->getId(), $comparison);
        } elseif ($teacher instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(CategoryTableMap::COL_TEACHERID, $teacher->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByTeacher() only accepts arguments of type \Teacher or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Teacher relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinTeacher(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Teacher');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Teacher');
        }

        return $this;
    }

    /**
     * Use the Teacher relation Teacher object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TeacherQuery A secondary query class using the current class as primary query
     */
    public function useTeacherQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTeacher($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Teacher', '\TeacherQuery');
    }

    /**
     * Use the Teacher relation Teacher object
     *
     * @param callable(\TeacherQuery):\TeacherQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withTeacherQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useTeacherQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Teacher table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \TeacherQuery The inner query object of the EXISTS statement
     */
    public function useTeacherExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \TeacherQuery */
        $q = $this->useExistsQuery('Teacher', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Teacher table for a NOT EXISTS query.
     *
     * @see useTeacherExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \TeacherQuery The inner query object of the NOT EXISTS statement
     */
    public function useTeacherNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TeacherQuery */
        $q = $this->useExistsQuery('Teacher', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Teacher table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \TeacherQuery The inner query object of the IN statement
     */
    public function useInTeacherQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \TeacherQuery */
        $q = $this->useInQuery('Teacher', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Teacher table for a NOT IN query.
     *
     * @see useTeacherInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \TeacherQuery The inner query object of the NOT IN statement
     */
    public function useNotInTeacherQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TeacherQuery */
        $q = $this->useInQuery('Teacher', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Categorylist object
     *
     * @param \Categorylist|ObjectCollection $categorylist The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCategorylist($categorylist, ?string $comparison = null)
    {
        if ($categorylist instanceof \Categorylist) {
            return $this
                ->addUsingAlias(CategoryTableMap::COL_CATEGORYID, $categorylist->getId(), $comparison);
        } elseif ($categorylist instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(CategoryTableMap::COL_CATEGORYID, $categorylist->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByCategorylist() only accepts arguments of type \Categorylist or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Categorylist relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinCategorylist(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Categorylist');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Categorylist');
        }

        return $this;
    }

    /**
     * Use the Categorylist relation Categorylist object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \CategorylistQuery A secondary query class using the current class as primary query
     */
    public function useCategorylistQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCategorylist($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Categorylist', '\CategorylistQuery');
    }

    /**
     * Use the Categorylist relation Categorylist object
     *
     * @param callable(\CategorylistQuery):\CategorylistQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withCategorylistQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useCategorylistQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Categorylist table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \CategorylistQuery The inner query object of the EXISTS statement
     */
    public function useCategorylistExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \CategorylistQuery */
        $q = $this->useExistsQuery('Categorylist', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Categorylist table for a NOT EXISTS query.
     *
     * @see useCategorylistExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \CategorylistQuery The inner query object of the NOT EXISTS statement
     */
    public function useCategorylistNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \CategorylistQuery */
        $q = $this->useExistsQuery('Categorylist', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Categorylist table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \CategorylistQuery The inner query object of the IN statement
     */
    public function useInCategorylistQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \CategorylistQuery */
        $q = $this->useInQuery('Categorylist', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Categorylist table for a NOT IN query.
     *
     * @see useCategorylistInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \CategorylistQuery The inner query object of the NOT IN statement
     */
    public function useNotInCategorylistQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \CategorylistQuery */
        $q = $this->useInQuery('Categorylist', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildCategory $category Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($category = null)
    {
        if ($category) {
            $this->addUsingAlias(CategoryTableMap::COL_ID, $category->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the category table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CategoryTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CategoryTableMap::clearInstancePool();
            CategoryTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CategoryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CategoryTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CategoryTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CategoryTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

}
