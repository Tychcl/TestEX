<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\Skill as ChildSkill;
use Models\SkillQuery as ChildSkillQuery;
use Models\Map\SkillTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `skill` table.
 *
 * Повышение квалификации действует 3 года
 *
 * @method     ChildSkillQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildSkillQuery orderByTeacherid($order = Criteria::ASC) Order by the teacherId column
 * @method     ChildSkillQuery orderByNum($order = Criteria::ASC) Order by the num column
 * @method     ChildSkillQuery orderByRegnum($order = Criteria::ASC) Order by the regNum column
 * @method     ChildSkillQuery orderByCity($order = Criteria::ASC) Order by the city column
 * @method     ChildSkillQuery orderByDate($order = Criteria::ASC) Order by the date column
 * @method     ChildSkillQuery orderByPlace($order = Criteria::ASC) Order by the place column
 * @method     ChildSkillQuery orderByStart($order = Criteria::ASC) Order by the start column
 * @method     ChildSkillQuery orderByEnd($order = Criteria::ASC) Order by the end column
 * @method     ChildSkillQuery orderByTheme($order = Criteria::ASC) Order by the theme column
 * @method     ChildSkillQuery orderByHours($order = Criteria::ASC) Order by the hours column
 * @method     ChildSkillQuery orderByDirector($order = Criteria::ASC) Order by the director column
 * @method     ChildSkillQuery orderBySecretary($order = Criteria::ASC) Order by the secretary column
 * @method     ChildSkillQuery orderByDocpath($order = Criteria::ASC) Order by the docPath column
 *
 * @method     ChildSkillQuery groupById() Group by the id column
 * @method     ChildSkillQuery groupByTeacherid() Group by the teacherId column
 * @method     ChildSkillQuery groupByNum() Group by the num column
 * @method     ChildSkillQuery groupByRegnum() Group by the regNum column
 * @method     ChildSkillQuery groupByCity() Group by the city column
 * @method     ChildSkillQuery groupByDate() Group by the date column
 * @method     ChildSkillQuery groupByPlace() Group by the place column
 * @method     ChildSkillQuery groupByStart() Group by the start column
 * @method     ChildSkillQuery groupByEnd() Group by the end column
 * @method     ChildSkillQuery groupByTheme() Group by the theme column
 * @method     ChildSkillQuery groupByHours() Group by the hours column
 * @method     ChildSkillQuery groupByDirector() Group by the director column
 * @method     ChildSkillQuery groupBySecretary() Group by the secretary column
 * @method     ChildSkillQuery groupByDocpath() Group by the docPath column
 *
 * @method     ChildSkillQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSkillQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSkillQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSkillQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildSkillQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildSkillQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildSkillQuery leftJoinTeacher($relationAlias = null) Adds a LEFT JOIN clause to the query using the Teacher relation
 * @method     ChildSkillQuery rightJoinTeacher($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Teacher relation
 * @method     ChildSkillQuery innerJoinTeacher($relationAlias = null) Adds a INNER JOIN clause to the query using the Teacher relation
 *
 * @method     ChildSkillQuery joinWithTeacher($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Teacher relation
 *
 * @method     ChildSkillQuery leftJoinWithTeacher() Adds a LEFT JOIN clause and with to the query using the Teacher relation
 * @method     ChildSkillQuery rightJoinWithTeacher() Adds a RIGHT JOIN clause and with to the query using the Teacher relation
 * @method     ChildSkillQuery innerJoinWithTeacher() Adds a INNER JOIN clause and with to the query using the Teacher relation
 *
 * @method     \Models\TeacherQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSkill|null findOne(?ConnectionInterface $con = null) Return the first ChildSkill matching the query
 * @method     ChildSkill findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildSkill matching the query, or a new ChildSkill object populated from the query conditions when no match is found
 *
 * @method     ChildSkill|null findOneById(int $id) Return the first ChildSkill filtered by the id column
 * @method     ChildSkill|null findOneByTeacherid(int $teacherId) Return the first ChildSkill filtered by the teacherId column
 * @method     ChildSkill|null findOneByNum(int $num) Return the first ChildSkill filtered by the num column
 * @method     ChildSkill|null findOneByRegnum(int $regNum) Return the first ChildSkill filtered by the regNum column
 * @method     ChildSkill|null findOneByCity(string $city) Return the first ChildSkill filtered by the city column
 * @method     ChildSkill|null findOneByDate(string $date) Return the first ChildSkill filtered by the date column
 * @method     ChildSkill|null findOneByPlace(string $place) Return the first ChildSkill filtered by the place column
 * @method     ChildSkill|null findOneByStart(string $start) Return the first ChildSkill filtered by the start column
 * @method     ChildSkill|null findOneByEnd(string $end) Return the first ChildSkill filtered by the end column
 * @method     ChildSkill|null findOneByTheme(string $theme) Return the first ChildSkill filtered by the theme column
 * @method     ChildSkill|null findOneByHours(int $hours) Return the first ChildSkill filtered by the hours column
 * @method     ChildSkill|null findOneByDirector(string $director) Return the first ChildSkill filtered by the director column
 * @method     ChildSkill|null findOneBySecretary(string $secretary) Return the first ChildSkill filtered by the secretary column
 * @method     ChildSkill|null findOneByDocpath(string $docPath) Return the first ChildSkill filtered by the docPath column
 *
 * @method     ChildSkill requirePk($key, ?ConnectionInterface $con = null) Return the ChildSkill by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSkill requireOne(?ConnectionInterface $con = null) Return the first ChildSkill matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSkill requireOneById(int $id) Return the first ChildSkill filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSkill requireOneByTeacherid(int $teacherId) Return the first ChildSkill filtered by the teacherId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSkill requireOneByNum(int $num) Return the first ChildSkill filtered by the num column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSkill requireOneByRegnum(int $regNum) Return the first ChildSkill filtered by the regNum column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSkill requireOneByCity(string $city) Return the first ChildSkill filtered by the city column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSkill requireOneByDate(string $date) Return the first ChildSkill filtered by the date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSkill requireOneByPlace(string $place) Return the first ChildSkill filtered by the place column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSkill requireOneByStart(string $start) Return the first ChildSkill filtered by the start column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSkill requireOneByEnd(string $end) Return the first ChildSkill filtered by the end column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSkill requireOneByTheme(string $theme) Return the first ChildSkill filtered by the theme column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSkill requireOneByHours(int $hours) Return the first ChildSkill filtered by the hours column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSkill requireOneByDirector(string $director) Return the first ChildSkill filtered by the director column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSkill requireOneBySecretary(string $secretary) Return the first ChildSkill filtered by the secretary column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSkill requireOneByDocpath(string $docPath) Return the first ChildSkill filtered by the docPath column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSkill[]|Collection find(?ConnectionInterface $con = null) Return ChildSkill objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildSkill> find(?ConnectionInterface $con = null) Return ChildSkill objects based on current ModelCriteria
 *
 * @method     ChildSkill[]|Collection findById(int|array<int> $id) Return ChildSkill objects filtered by the id column
 * @psalm-method Collection&\Traversable<ChildSkill> findById(int|array<int> $id) Return ChildSkill objects filtered by the id column
 * @method     ChildSkill[]|Collection findByTeacherid(int|array<int> $teacherId) Return ChildSkill objects filtered by the teacherId column
 * @psalm-method Collection&\Traversable<ChildSkill> findByTeacherid(int|array<int> $teacherId) Return ChildSkill objects filtered by the teacherId column
 * @method     ChildSkill[]|Collection findByNum(int|array<int> $num) Return ChildSkill objects filtered by the num column
 * @psalm-method Collection&\Traversable<ChildSkill> findByNum(int|array<int> $num) Return ChildSkill objects filtered by the num column
 * @method     ChildSkill[]|Collection findByRegnum(int|array<int> $regNum) Return ChildSkill objects filtered by the regNum column
 * @psalm-method Collection&\Traversable<ChildSkill> findByRegnum(int|array<int> $regNum) Return ChildSkill objects filtered by the regNum column
 * @method     ChildSkill[]|Collection findByCity(string|array<string> $city) Return ChildSkill objects filtered by the city column
 * @psalm-method Collection&\Traversable<ChildSkill> findByCity(string|array<string> $city) Return ChildSkill objects filtered by the city column
 * @method     ChildSkill[]|Collection findByDate(string|array<string> $date) Return ChildSkill objects filtered by the date column
 * @psalm-method Collection&\Traversable<ChildSkill> findByDate(string|array<string> $date) Return ChildSkill objects filtered by the date column
 * @method     ChildSkill[]|Collection findByPlace(string|array<string> $place) Return ChildSkill objects filtered by the place column
 * @psalm-method Collection&\Traversable<ChildSkill> findByPlace(string|array<string> $place) Return ChildSkill objects filtered by the place column
 * @method     ChildSkill[]|Collection findByStart(string|array<string> $start) Return ChildSkill objects filtered by the start column
 * @psalm-method Collection&\Traversable<ChildSkill> findByStart(string|array<string> $start) Return ChildSkill objects filtered by the start column
 * @method     ChildSkill[]|Collection findByEnd(string|array<string> $end) Return ChildSkill objects filtered by the end column
 * @psalm-method Collection&\Traversable<ChildSkill> findByEnd(string|array<string> $end) Return ChildSkill objects filtered by the end column
 * @method     ChildSkill[]|Collection findByTheme(string|array<string> $theme) Return ChildSkill objects filtered by the theme column
 * @psalm-method Collection&\Traversable<ChildSkill> findByTheme(string|array<string> $theme) Return ChildSkill objects filtered by the theme column
 * @method     ChildSkill[]|Collection findByHours(int|array<int> $hours) Return ChildSkill objects filtered by the hours column
 * @psalm-method Collection&\Traversable<ChildSkill> findByHours(int|array<int> $hours) Return ChildSkill objects filtered by the hours column
 * @method     ChildSkill[]|Collection findByDirector(string|array<string> $director) Return ChildSkill objects filtered by the director column
 * @psalm-method Collection&\Traversable<ChildSkill> findByDirector(string|array<string> $director) Return ChildSkill objects filtered by the director column
 * @method     ChildSkill[]|Collection findBySecretary(string|array<string> $secretary) Return ChildSkill objects filtered by the secretary column
 * @psalm-method Collection&\Traversable<ChildSkill> findBySecretary(string|array<string> $secretary) Return ChildSkill objects filtered by the secretary column
 * @method     ChildSkill[]|Collection findByDocpath(string|array<string> $docPath) Return ChildSkill objects filtered by the docPath column
 * @psalm-method Collection&\Traversable<ChildSkill> findByDocpath(string|array<string> $docPath) Return ChildSkill objects filtered by the docPath column
 *
 * @method     ChildSkill[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildSkill> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class SkillQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\SkillQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Skill', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSkillQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSkillQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildSkillQuery) {
            return $criteria;
        }
        $query = new ChildSkillQuery();
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
     * @return ChildSkill|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SkillTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = SkillTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildSkill A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, teacherId, num, regNum, city, date, place, start, end, theme, hours, director, secretary, docPath FROM skill WHERE id = :p0';
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
            /** @var ChildSkill $obj */
            $obj = new ChildSkill();
            $obj->hydrate($row);
            SkillTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildSkill|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(SkillTableMap::COL_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(SkillTableMap::COL_ID, $keys, Criteria::IN);

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
                $this->addUsingAlias(SkillTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SkillTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SkillTableMap::COL_ID, $id, $comparison);

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
                $this->addUsingAlias(SkillTableMap::COL_TEACHERID, $teacherid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($teacherid['max'])) {
                $this->addUsingAlias(SkillTableMap::COL_TEACHERID, $teacherid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SkillTableMap::COL_TEACHERID, $teacherid, $comparison);

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
                $this->addUsingAlias(SkillTableMap::COL_NUM, $num['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($num['max'])) {
                $this->addUsingAlias(SkillTableMap::COL_NUM, $num['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SkillTableMap::COL_NUM, $num, $comparison);

        return $this;
    }

    /**
     * Filter the query on the regNum column
     *
     * Example usage:
     * <code>
     * $query->filterByRegnum(1234); // WHERE regNum = 1234
     * $query->filterByRegnum(array(12, 34)); // WHERE regNum IN (12, 34)
     * $query->filterByRegnum(array('min' => 12)); // WHERE regNum > 12
     * </code>
     *
     * @param mixed $regnum The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByRegnum($regnum = null, ?string $comparison = null)
    {
        if (is_array($regnum)) {
            $useMinMax = false;
            if (isset($regnum['min'])) {
                $this->addUsingAlias(SkillTableMap::COL_REGNUM, $regnum['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($regnum['max'])) {
                $this->addUsingAlias(SkillTableMap::COL_REGNUM, $regnum['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SkillTableMap::COL_REGNUM, $regnum, $comparison);

        return $this;
    }

    /**
     * Filter the query on the city column
     *
     * Example usage:
     * <code>
     * $query->filterByCity('fooValue');   // WHERE city = 'fooValue'
     * $query->filterByCity('%fooValue%', Criteria::LIKE); // WHERE city LIKE '%fooValue%'
     * $query->filterByCity(['foo', 'bar']); // WHERE city IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $city The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCity($city = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($city)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SkillTableMap::COL_CITY, $city, $comparison);

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
                $this->addUsingAlias(SkillTableMap::COL_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(SkillTableMap::COL_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SkillTableMap::COL_DATE, $date, $comparison);

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

        $this->addUsingAlias(SkillTableMap::COL_PLACE, $place, $comparison);

        return $this;
    }

    /**
     * Filter the query on the start column
     *
     * Example usage:
     * <code>
     * $query->filterByStart('2011-03-14'); // WHERE start = '2011-03-14'
     * $query->filterByStart('now'); // WHERE start = '2011-03-14'
     * $query->filterByStart(array('max' => 'yesterday')); // WHERE start > '2011-03-13'
     * </code>
     *
     * @param mixed $start The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStart($start = null, ?string $comparison = null)
    {
        if (is_array($start)) {
            $useMinMax = false;
            if (isset($start['min'])) {
                $this->addUsingAlias(SkillTableMap::COL_START, $start['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($start['max'])) {
                $this->addUsingAlias(SkillTableMap::COL_START, $start['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SkillTableMap::COL_START, $start, $comparison);

        return $this;
    }

    /**
     * Filter the query on the end column
     *
     * Example usage:
     * <code>
     * $query->filterByEnd('2011-03-14'); // WHERE end = '2011-03-14'
     * $query->filterByEnd('now'); // WHERE end = '2011-03-14'
     * $query->filterByEnd(array('max' => 'yesterday')); // WHERE end > '2011-03-13'
     * </code>
     *
     * @param mixed $end The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEnd($end = null, ?string $comparison = null)
    {
        if (is_array($end)) {
            $useMinMax = false;
            if (isset($end['min'])) {
                $this->addUsingAlias(SkillTableMap::COL_END, $end['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($end['max'])) {
                $this->addUsingAlias(SkillTableMap::COL_END, $end['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SkillTableMap::COL_END, $end, $comparison);

        return $this;
    }

    /**
     * Filter the query on the theme column
     *
     * Example usage:
     * <code>
     * $query->filterByTheme('fooValue');   // WHERE theme = 'fooValue'
     * $query->filterByTheme('%fooValue%', Criteria::LIKE); // WHERE theme LIKE '%fooValue%'
     * $query->filterByTheme(['foo', 'bar']); // WHERE theme IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $theme The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTheme($theme = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($theme)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SkillTableMap::COL_THEME, $theme, $comparison);

        return $this;
    }

    /**
     * Filter the query on the hours column
     *
     * Example usage:
     * <code>
     * $query->filterByHours(1234); // WHERE hours = 1234
     * $query->filterByHours(array(12, 34)); // WHERE hours IN (12, 34)
     * $query->filterByHours(array('min' => 12)); // WHERE hours > 12
     * </code>
     *
     * @param mixed $hours The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByHours($hours = null, ?string $comparison = null)
    {
        if (is_array($hours)) {
            $useMinMax = false;
            if (isset($hours['min'])) {
                $this->addUsingAlias(SkillTableMap::COL_HOURS, $hours['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($hours['max'])) {
                $this->addUsingAlias(SkillTableMap::COL_HOURS, $hours['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SkillTableMap::COL_HOURS, $hours, $comparison);

        return $this;
    }

    /**
     * Filter the query on the director column
     *
     * Example usage:
     * <code>
     * $query->filterByDirector('fooValue');   // WHERE director = 'fooValue'
     * $query->filterByDirector('%fooValue%', Criteria::LIKE); // WHERE director LIKE '%fooValue%'
     * $query->filterByDirector(['foo', 'bar']); // WHERE director IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $director The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDirector($director = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($director)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SkillTableMap::COL_DIRECTOR, $director, $comparison);

        return $this;
    }

    /**
     * Filter the query on the secretary column
     *
     * Example usage:
     * <code>
     * $query->filterBySecretary('fooValue');   // WHERE secretary = 'fooValue'
     * $query->filterBySecretary('%fooValue%', Criteria::LIKE); // WHERE secretary LIKE '%fooValue%'
     * $query->filterBySecretary(['foo', 'bar']); // WHERE secretary IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $secretary The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySecretary($secretary = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($secretary)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SkillTableMap::COL_SECRETARY, $secretary, $comparison);

        return $this;
    }

    /**
     * Filter the query on the docPath column
     *
     * Example usage:
     * <code>
     * $query->filterByDocpath('fooValue');   // WHERE docPath = 'fooValue'
     * $query->filterByDocpath('%fooValue%', Criteria::LIKE); // WHERE docPath LIKE '%fooValue%'
     * $query->filterByDocpath(['foo', 'bar']); // WHERE docPath IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $docpath The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDocpath($docpath = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($docpath)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SkillTableMap::COL_DOCPATH, $docpath, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Models\Teacher object
     *
     * @param \Models\Teacher|ObjectCollection $teacher The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTeacher($teacher, ?string $comparison = null)
    {
        if ($teacher instanceof \Models\Teacher) {
            return $this
                ->addUsingAlias(SkillTableMap::COL_TEACHERID, $teacher->getId(), $comparison);
        } elseif ($teacher instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(SkillTableMap::COL_TEACHERID, $teacher->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByTeacher() only accepts arguments of type \Models\Teacher or Collection');
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
     * @return \Models\TeacherQuery A secondary query class using the current class as primary query
     */
    public function useTeacherQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTeacher($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Teacher', '\Models\TeacherQuery');
    }

    /**
     * Use the Teacher relation Teacher object
     *
     * @param callable(\Models\TeacherQuery):\Models\TeacherQuery $callable A function working on the related query
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
     * @return \Models\TeacherQuery The inner query object of the EXISTS statement
     */
    public function useTeacherExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Models\TeacherQuery */
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
     * @return \Models\TeacherQuery The inner query object of the NOT EXISTS statement
     */
    public function useTeacherNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Models\TeacherQuery */
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
     * @return \Models\TeacherQuery The inner query object of the IN statement
     */
    public function useInTeacherQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Models\TeacherQuery */
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
     * @return \Models\TeacherQuery The inner query object of the NOT IN statement
     */
    public function useNotInTeacherQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Models\TeacherQuery */
        $q = $this->useInQuery('Teacher', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildSkill $skill Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($skill = null)
    {
        if ($skill) {
            $this->addUsingAlias(SkillTableMap::COL_ID, $skill->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the skill table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SkillTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SkillTableMap::clearInstancePool();
            SkillTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SkillTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SkillTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SkillTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SkillTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

}
