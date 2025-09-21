<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\Teacher as ChildTeacher;
use Models\TeacherQuery as ChildTeacherQuery;
use Models\Map\TeacherTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `teacher` table.
 *
 * Преподаватели
 *
 * @method     ChildTeacherQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildTeacherQuery orderByFio($order = Criteria::ASC) Order by the fio column
 * @method     ChildTeacherQuery orderByLogin($order = Criteria::ASC) Order by the login column
 * @method     ChildTeacherQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method     ChildTeacherQuery orderByRoleid($order = Criteria::ASC) Order by the roleId column
 * @method     ChildTeacherQuery orderByNeedupdskill($order = Criteria::ASC) Order by the needUpdSkill column
 * @method     ChildTeacherQuery orderByCanupdcategory($order = Criteria::ASC) Order by the canUpdCategory column
 *
 * @method     ChildTeacherQuery groupById() Group by the id column
 * @method     ChildTeacherQuery groupByFio() Group by the fio column
 * @method     ChildTeacherQuery groupByLogin() Group by the login column
 * @method     ChildTeacherQuery groupByPassword() Group by the password column
 * @method     ChildTeacherQuery groupByRoleid() Group by the roleId column
 * @method     ChildTeacherQuery groupByNeedupdskill() Group by the needUpdSkill column
 * @method     ChildTeacherQuery groupByCanupdcategory() Group by the canUpdCategory column
 *
 * @method     ChildTeacherQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildTeacherQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildTeacherQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildTeacherQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildTeacherQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildTeacherQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildTeacherQuery leftJoinUserrole($relationAlias = null) Adds a LEFT JOIN clause to the query using the Userrole relation
 * @method     ChildTeacherQuery rightJoinUserrole($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Userrole relation
 * @method     ChildTeacherQuery innerJoinUserrole($relationAlias = null) Adds a INNER JOIN clause to the query using the Userrole relation
 *
 * @method     ChildTeacherQuery joinWithUserrole($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Userrole relation
 *
 * @method     ChildTeacherQuery leftJoinWithUserrole() Adds a LEFT JOIN clause and with to the query using the Userrole relation
 * @method     ChildTeacherQuery rightJoinWithUserrole() Adds a RIGHT JOIN clause and with to the query using the Userrole relation
 * @method     ChildTeacherQuery innerJoinWithUserrole() Adds a INNER JOIN clause and with to the query using the Userrole relation
 *
 * @method     ChildTeacherQuery leftJoinCategory($relationAlias = null) Adds a LEFT JOIN clause to the query using the Category relation
 * @method     ChildTeacherQuery rightJoinCategory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Category relation
 * @method     ChildTeacherQuery innerJoinCategory($relationAlias = null) Adds a INNER JOIN clause to the query using the Category relation
 *
 * @method     ChildTeacherQuery joinWithCategory($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Category relation
 *
 * @method     ChildTeacherQuery leftJoinWithCategory() Adds a LEFT JOIN clause and with to the query using the Category relation
 * @method     ChildTeacherQuery rightJoinWithCategory() Adds a RIGHT JOIN clause and with to the query using the Category relation
 * @method     ChildTeacherQuery innerJoinWithCategory() Adds a INNER JOIN clause and with to the query using the Category relation
 *
 * @method     ChildTeacherQuery leftJoinEvent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Event relation
 * @method     ChildTeacherQuery rightJoinEvent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Event relation
 * @method     ChildTeacherQuery innerJoinEvent($relationAlias = null) Adds a INNER JOIN clause to the query using the Event relation
 *
 * @method     ChildTeacherQuery joinWithEvent($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Event relation
 *
 * @method     ChildTeacherQuery leftJoinWithEvent() Adds a LEFT JOIN clause and with to the query using the Event relation
 * @method     ChildTeacherQuery rightJoinWithEvent() Adds a RIGHT JOIN clause and with to the query using the Event relation
 * @method     ChildTeacherQuery innerJoinWithEvent() Adds a INNER JOIN clause and with to the query using the Event relation
 *
 * @method     ChildTeacherQuery leftJoinSkill($relationAlias = null) Adds a LEFT JOIN clause to the query using the Skill relation
 * @method     ChildTeacherQuery rightJoinSkill($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Skill relation
 * @method     ChildTeacherQuery innerJoinSkill($relationAlias = null) Adds a INNER JOIN clause to the query using the Skill relation
 *
 * @method     ChildTeacherQuery joinWithSkill($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Skill relation
 *
 * @method     ChildTeacherQuery leftJoinWithSkill() Adds a LEFT JOIN clause and with to the query using the Skill relation
 * @method     ChildTeacherQuery rightJoinWithSkill() Adds a RIGHT JOIN clause and with to the query using the Skill relation
 * @method     ChildTeacherQuery innerJoinWithSkill() Adds a INNER JOIN clause and with to the query using the Skill relation
 *
 * @method     \Models\UserroleQuery|\Models\CategoryQuery|\Models\EventQuery|\Models\SkillQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildTeacher|null findOne(?ConnectionInterface $con = null) Return the first ChildTeacher matching the query
 * @method     ChildTeacher findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildTeacher matching the query, or a new ChildTeacher object populated from the query conditions when no match is found
 *
 * @method     ChildTeacher|null findOneById(int $id) Return the first ChildTeacher filtered by the id column
 * @method     ChildTeacher|null findOneByFio(string $fio) Return the first ChildTeacher filtered by the fio column
 * @method     ChildTeacher|null findOneByLogin(string $login) Return the first ChildTeacher filtered by the login column
 * @method     ChildTeacher|null findOneByPassword(string $password) Return the first ChildTeacher filtered by the password column
 * @method     ChildTeacher|null findOneByRoleid(int $roleId) Return the first ChildTeacher filtered by the roleId column
 * @method     ChildTeacher|null findOneByNeedupdskill(boolean $needUpdSkill) Return the first ChildTeacher filtered by the needUpdSkill column
 * @method     ChildTeacher|null findOneByCanupdcategory(boolean $canUpdCategory) Return the first ChildTeacher filtered by the canUpdCategory column
 *
 * @method     ChildTeacher requirePk($key, ?ConnectionInterface $con = null) Return the ChildTeacher by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTeacher requireOne(?ConnectionInterface $con = null) Return the first ChildTeacher matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTeacher requireOneById(int $id) Return the first ChildTeacher filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTeacher requireOneByFio(string $fio) Return the first ChildTeacher filtered by the fio column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTeacher requireOneByLogin(string $login) Return the first ChildTeacher filtered by the login column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTeacher requireOneByPassword(string $password) Return the first ChildTeacher filtered by the password column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTeacher requireOneByRoleid(int $roleId) Return the first ChildTeacher filtered by the roleId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTeacher requireOneByNeedupdskill(boolean $needUpdSkill) Return the first ChildTeacher filtered by the needUpdSkill column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTeacher requireOneByCanupdcategory(boolean $canUpdCategory) Return the first ChildTeacher filtered by the canUpdCategory column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTeacher[]|Collection find(?ConnectionInterface $con = null) Return ChildTeacher objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildTeacher> find(?ConnectionInterface $con = null) Return ChildTeacher objects based on current ModelCriteria
 *
 * @method     ChildTeacher[]|Collection findById(int|array<int> $id) Return ChildTeacher objects filtered by the id column
 * @psalm-method Collection&\Traversable<ChildTeacher> findById(int|array<int> $id) Return ChildTeacher objects filtered by the id column
 * @method     ChildTeacher[]|Collection findByFio(string|array<string> $fio) Return ChildTeacher objects filtered by the fio column
 * @psalm-method Collection&\Traversable<ChildTeacher> findByFio(string|array<string> $fio) Return ChildTeacher objects filtered by the fio column
 * @method     ChildTeacher[]|Collection findByLogin(string|array<string> $login) Return ChildTeacher objects filtered by the login column
 * @psalm-method Collection&\Traversable<ChildTeacher> findByLogin(string|array<string> $login) Return ChildTeacher objects filtered by the login column
 * @method     ChildTeacher[]|Collection findByPassword(string|array<string> $password) Return ChildTeacher objects filtered by the password column
 * @psalm-method Collection&\Traversable<ChildTeacher> findByPassword(string|array<string> $password) Return ChildTeacher objects filtered by the password column
 * @method     ChildTeacher[]|Collection findByRoleid(int|array<int> $roleId) Return ChildTeacher objects filtered by the roleId column
 * @psalm-method Collection&\Traversable<ChildTeacher> findByRoleid(int|array<int> $roleId) Return ChildTeacher objects filtered by the roleId column
 * @method     ChildTeacher[]|Collection findByNeedupdskill(boolean|array<boolean> $needUpdSkill) Return ChildTeacher objects filtered by the needUpdSkill column
 * @psalm-method Collection&\Traversable<ChildTeacher> findByNeedupdskill(boolean|array<boolean> $needUpdSkill) Return ChildTeacher objects filtered by the needUpdSkill column
 * @method     ChildTeacher[]|Collection findByCanupdcategory(boolean|array<boolean> $canUpdCategory) Return ChildTeacher objects filtered by the canUpdCategory column
 * @psalm-method Collection&\Traversable<ChildTeacher> findByCanupdcategory(boolean|array<boolean> $canUpdCategory) Return ChildTeacher objects filtered by the canUpdCategory column
 *
 * @method     ChildTeacher[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildTeacher> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class TeacherQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\TeacherQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Teacher', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildTeacherQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildTeacherQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildTeacherQuery) {
            return $criteria;
        }
        $query = new ChildTeacherQuery();
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
     * @return ChildTeacher|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(TeacherTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = TeacherTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildTeacher A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, fio, login, password, roleId, needUpdSkill, canUpdCategory FROM teacher WHERE id = :p0';
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
            /** @var ChildTeacher $obj */
            $obj = new ChildTeacher();
            $obj->hydrate($row);
            TeacherTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildTeacher|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(TeacherTableMap::COL_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(TeacherTableMap::COL_ID, $keys, Criteria::IN);

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
                $this->addUsingAlias(TeacherTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(TeacherTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(TeacherTableMap::COL_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the fio column
     *
     * Example usage:
     * <code>
     * $query->filterByFio('fooValue');   // WHERE fio = 'fooValue'
     * $query->filterByFio('%fooValue%', Criteria::LIKE); // WHERE fio LIKE '%fooValue%'
     * $query->filterByFio(['foo', 'bar']); // WHERE fio IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $fio The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFio($fio = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fio)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(TeacherTableMap::COL_FIO, $fio, $comparison);

        return $this;
    }

    /**
     * Filter the query on the login column
     *
     * Example usage:
     * <code>
     * $query->filterByLogin('fooValue');   // WHERE login = 'fooValue'
     * $query->filterByLogin('%fooValue%', Criteria::LIKE); // WHERE login LIKE '%fooValue%'
     * $query->filterByLogin(['foo', 'bar']); // WHERE login IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $login The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLogin($login = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($login)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(TeacherTableMap::COL_LOGIN, $login, $comparison);

        return $this;
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%', Criteria::LIKE); // WHERE password LIKE '%fooValue%'
     * $query->filterByPassword(['foo', 'bar']); // WHERE password IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $password The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPassword($password = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(TeacherTableMap::COL_PASSWORD, $password, $comparison);

        return $this;
    }

    /**
     * Filter the query on the roleId column
     *
     * Example usage:
     * <code>
     * $query->filterByRoleid(1234); // WHERE roleId = 1234
     * $query->filterByRoleid(array(12, 34)); // WHERE roleId IN (12, 34)
     * $query->filterByRoleid(array('min' => 12)); // WHERE roleId > 12
     * </code>
     *
     * @see       filterByUserrole()
     *
     * @param mixed $roleid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByRoleid($roleid = null, ?string $comparison = null)
    {
        if (is_array($roleid)) {
            $useMinMax = false;
            if (isset($roleid['min'])) {
                $this->addUsingAlias(TeacherTableMap::COL_ROLEID, $roleid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($roleid['max'])) {
                $this->addUsingAlias(TeacherTableMap::COL_ROLEID, $roleid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(TeacherTableMap::COL_ROLEID, $roleid, $comparison);

        return $this;
    }

    /**
     * Filter the query on the needUpdSkill column
     *
     * Example usage:
     * <code>
     * $query->filterByNeedupdskill(true); // WHERE needUpdSkill = true
     * $query->filterByNeedupdskill('yes'); // WHERE needUpdSkill = true
     * </code>
     *
     * @param bool|string $needupdskill The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNeedupdskill($needupdskill = null, ?string $comparison = null)
    {
        if (is_string($needupdskill)) {
            $needupdskill = in_array(strtolower($needupdskill), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(TeacherTableMap::COL_NEEDUPDSKILL, $needupdskill, $comparison);

        return $this;
    }

    /**
     * Filter the query on the canUpdCategory column
     *
     * Example usage:
     * <code>
     * $query->filterByCanupdcategory(true); // WHERE canUpdCategory = true
     * $query->filterByCanupdcategory('yes'); // WHERE canUpdCategory = true
     * </code>
     *
     * @param bool|string $canupdcategory The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCanupdcategory($canupdcategory = null, ?string $comparison = null)
    {
        if (is_string($canupdcategory)) {
            $canupdcategory = in_array(strtolower($canupdcategory), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(TeacherTableMap::COL_CANUPDCATEGORY, $canupdcategory, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Models\Userrole object
     *
     * @param \Models\Userrole|ObjectCollection $userrole The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUserrole($userrole, ?string $comparison = null)
    {
        if ($userrole instanceof \Models\Userrole) {
            return $this
                ->addUsingAlias(TeacherTableMap::COL_ROLEID, $userrole->getId(), $comparison);
        } elseif ($userrole instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(TeacherTableMap::COL_ROLEID, $userrole->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByUserrole() only accepts arguments of type \Models\Userrole or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Userrole relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinUserrole(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Userrole');

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
            $this->addJoinObject($join, 'Userrole');
        }

        return $this;
    }

    /**
     * Use the Userrole relation Userrole object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\UserroleQuery A secondary query class using the current class as primary query
     */
    public function useUserroleQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserrole($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Userrole', '\Models\UserroleQuery');
    }

    /**
     * Use the Userrole relation Userrole object
     *
     * @param callable(\Models\UserroleQuery):\Models\UserroleQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withUserroleQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useUserroleQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Userrole table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Models\UserroleQuery The inner query object of the EXISTS statement
     */
    public function useUserroleExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Models\UserroleQuery */
        $q = $this->useExistsQuery('Userrole', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Userrole table for a NOT EXISTS query.
     *
     * @see useUserroleExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Models\UserroleQuery The inner query object of the NOT EXISTS statement
     */
    public function useUserroleNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Models\UserroleQuery */
        $q = $this->useExistsQuery('Userrole', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Userrole table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Models\UserroleQuery The inner query object of the IN statement
     */
    public function useInUserroleQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Models\UserroleQuery */
        $q = $this->useInQuery('Userrole', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Userrole table for a NOT IN query.
     *
     * @see useUserroleInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Models\UserroleQuery The inner query object of the NOT IN statement
     */
    public function useNotInUserroleQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Models\UserroleQuery */
        $q = $this->useInQuery('Userrole', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Models\Category object
     *
     * @param \Models\Category|ObjectCollection $category the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCategory($category, ?string $comparison = null)
    {
        if ($category instanceof \Models\Category) {
            $this
                ->addUsingAlias(TeacherTableMap::COL_ID, $category->getTeacherid(), $comparison);

            return $this;
        } elseif ($category instanceof ObjectCollection) {
            $this
                ->useCategoryQuery()
                ->filterByPrimaryKeys($category->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByCategory() only accepts arguments of type \Models\Category or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Category relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinCategory(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Category');

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
            $this->addJoinObject($join, 'Category');
        }

        return $this;
    }

    /**
     * Use the Category relation Category object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\CategoryQuery A secondary query class using the current class as primary query
     */
    public function useCategoryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCategory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Category', '\Models\CategoryQuery');
    }

    /**
     * Use the Category relation Category object
     *
     * @param callable(\Models\CategoryQuery):\Models\CategoryQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withCategoryQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useCategoryQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Category table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Models\CategoryQuery The inner query object of the EXISTS statement
     */
    public function useCategoryExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Models\CategoryQuery */
        $q = $this->useExistsQuery('Category', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Category table for a NOT EXISTS query.
     *
     * @see useCategoryExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Models\CategoryQuery The inner query object of the NOT EXISTS statement
     */
    public function useCategoryNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Models\CategoryQuery */
        $q = $this->useExistsQuery('Category', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Category table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Models\CategoryQuery The inner query object of the IN statement
     */
    public function useInCategoryQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Models\CategoryQuery */
        $q = $this->useInQuery('Category', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Category table for a NOT IN query.
     *
     * @see useCategoryInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Models\CategoryQuery The inner query object of the NOT IN statement
     */
    public function useNotInCategoryQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Models\CategoryQuery */
        $q = $this->useInQuery('Category', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Models\Event object
     *
     * @param \Models\Event|ObjectCollection $event the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEvent($event, ?string $comparison = null)
    {
        if ($event instanceof \Models\Event) {
            $this
                ->addUsingAlias(TeacherTableMap::COL_ID, $event->getTeacherid(), $comparison);

            return $this;
        } elseif ($event instanceof ObjectCollection) {
            $this
                ->useEventQuery()
                ->filterByPrimaryKeys($event->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByEvent() only accepts arguments of type \Models\Event or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Event relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinEvent(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Event');

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
            $this->addJoinObject($join, 'Event');
        }

        return $this;
    }

    /**
     * Use the Event relation Event object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\EventQuery A secondary query class using the current class as primary query
     */
    public function useEventQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEvent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Event', '\Models\EventQuery');
    }

    /**
     * Use the Event relation Event object
     *
     * @param callable(\Models\EventQuery):\Models\EventQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withEventQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useEventQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Event table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Models\EventQuery The inner query object of the EXISTS statement
     */
    public function useEventExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Models\EventQuery */
        $q = $this->useExistsQuery('Event', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Event table for a NOT EXISTS query.
     *
     * @see useEventExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Models\EventQuery The inner query object of the NOT EXISTS statement
     */
    public function useEventNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Models\EventQuery */
        $q = $this->useExistsQuery('Event', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Event table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Models\EventQuery The inner query object of the IN statement
     */
    public function useInEventQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Models\EventQuery */
        $q = $this->useInQuery('Event', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Event table for a NOT IN query.
     *
     * @see useEventInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Models\EventQuery The inner query object of the NOT IN statement
     */
    public function useNotInEventQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Models\EventQuery */
        $q = $this->useInQuery('Event', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Models\Skill object
     *
     * @param \Models\Skill|ObjectCollection $skill the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySkill($skill, ?string $comparison = null)
    {
        if ($skill instanceof \Models\Skill) {
            $this
                ->addUsingAlias(TeacherTableMap::COL_ID, $skill->getTeacherid(), $comparison);

            return $this;
        } elseif ($skill instanceof ObjectCollection) {
            $this
                ->useSkillQuery()
                ->filterByPrimaryKeys($skill->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterBySkill() only accepts arguments of type \Models\Skill or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Skill relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinSkill(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Skill');

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
            $this->addJoinObject($join, 'Skill');
        }

        return $this;
    }

    /**
     * Use the Skill relation Skill object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\SkillQuery A secondary query class using the current class as primary query
     */
    public function useSkillQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSkill($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Skill', '\Models\SkillQuery');
    }

    /**
     * Use the Skill relation Skill object
     *
     * @param callable(\Models\SkillQuery):\Models\SkillQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withSkillQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useSkillQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Skill table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Models\SkillQuery The inner query object of the EXISTS statement
     */
    public function useSkillExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Models\SkillQuery */
        $q = $this->useExistsQuery('Skill', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Skill table for a NOT EXISTS query.
     *
     * @see useSkillExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Models\SkillQuery The inner query object of the NOT EXISTS statement
     */
    public function useSkillNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Models\SkillQuery */
        $q = $this->useExistsQuery('Skill', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Skill table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Models\SkillQuery The inner query object of the IN statement
     */
    public function useInSkillQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Models\SkillQuery */
        $q = $this->useInQuery('Skill', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Skill table for a NOT IN query.
     *
     * @see useSkillInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Models\SkillQuery The inner query object of the NOT IN statement
     */
    public function useNotInSkillQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Models\SkillQuery */
        $q = $this->useInQuery('Skill', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildTeacher $teacher Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($teacher = null)
    {
        if ($teacher) {
            $this->addUsingAlias(TeacherTableMap::COL_ID, $teacher->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the teacher table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TeacherTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            TeacherTableMap::clearInstancePool();
            TeacherTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(TeacherTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(TeacherTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            TeacherTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            TeacherTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

}
