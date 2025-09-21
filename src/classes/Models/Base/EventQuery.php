<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\Event as ChildEvent;
use Models\EventQuery as ChildEventQuery;
use Models\Map\EventTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `event` table.
 *
 * @method     ChildEventQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildEventQuery orderByInfoid($order = Criteria::ASC) Order by the infoId column
 * @method     ChildEventQuery orderByTeacherid($order = Criteria::ASC) Order by the teacherId column
 * @method     ChildEventQuery orderByTeacherroleid($order = Criteria::ASC) Order by the teacherRoleId column
 * @method     ChildEventQuery orderByStudentid($order = Criteria::ASC) Order by the studentId column
 * @method     ChildEventQuery orderByAwardid($order = Criteria::ASC) Order by the awardId column
 * @method     ChildEventQuery orderByDocument($order = Criteria::ASC) Order by the document column
 *
 * @method     ChildEventQuery groupById() Group by the id column
 * @method     ChildEventQuery groupByInfoid() Group by the infoId column
 * @method     ChildEventQuery groupByTeacherid() Group by the teacherId column
 * @method     ChildEventQuery groupByTeacherroleid() Group by the teacherRoleId column
 * @method     ChildEventQuery groupByStudentid() Group by the studentId column
 * @method     ChildEventQuery groupByAwardid() Group by the awardId column
 * @method     ChildEventQuery groupByDocument() Group by the document column
 *
 * @method     ChildEventQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildEventQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildEventQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildEventQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildEventQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildEventQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildEventQuery leftJoinEventinfo($relationAlias = null) Adds a LEFT JOIN clause to the query using the Eventinfo relation
 * @method     ChildEventQuery rightJoinEventinfo($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Eventinfo relation
 * @method     ChildEventQuery innerJoinEventinfo($relationAlias = null) Adds a INNER JOIN clause to the query using the Eventinfo relation
 *
 * @method     ChildEventQuery joinWithEventinfo($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Eventinfo relation
 *
 * @method     ChildEventQuery leftJoinWithEventinfo() Adds a LEFT JOIN clause and with to the query using the Eventinfo relation
 * @method     ChildEventQuery rightJoinWithEventinfo() Adds a RIGHT JOIN clause and with to the query using the Eventinfo relation
 * @method     ChildEventQuery innerJoinWithEventinfo() Adds a INNER JOIN clause and with to the query using the Eventinfo relation
 *
 * @method     ChildEventQuery leftJoinTeacher($relationAlias = null) Adds a LEFT JOIN clause to the query using the Teacher relation
 * @method     ChildEventQuery rightJoinTeacher($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Teacher relation
 * @method     ChildEventQuery innerJoinTeacher($relationAlias = null) Adds a INNER JOIN clause to the query using the Teacher relation
 *
 * @method     ChildEventQuery joinWithTeacher($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Teacher relation
 *
 * @method     ChildEventQuery leftJoinWithTeacher() Adds a LEFT JOIN clause and with to the query using the Teacher relation
 * @method     ChildEventQuery rightJoinWithTeacher() Adds a RIGHT JOIN clause and with to the query using the Teacher relation
 * @method     ChildEventQuery innerJoinWithTeacher() Adds a INNER JOIN clause and with to the query using the Teacher relation
 *
 * @method     ChildEventQuery leftJoinEventrole($relationAlias = null) Adds a LEFT JOIN clause to the query using the Eventrole relation
 * @method     ChildEventQuery rightJoinEventrole($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Eventrole relation
 * @method     ChildEventQuery innerJoinEventrole($relationAlias = null) Adds a INNER JOIN clause to the query using the Eventrole relation
 *
 * @method     ChildEventQuery joinWithEventrole($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Eventrole relation
 *
 * @method     ChildEventQuery leftJoinWithEventrole() Adds a LEFT JOIN clause and with to the query using the Eventrole relation
 * @method     ChildEventQuery rightJoinWithEventrole() Adds a RIGHT JOIN clause and with to the query using the Eventrole relation
 * @method     ChildEventQuery innerJoinWithEventrole() Adds a INNER JOIN clause and with to the query using the Eventrole relation
 *
 * @method     ChildEventQuery leftJoinStudent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Student relation
 * @method     ChildEventQuery rightJoinStudent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Student relation
 * @method     ChildEventQuery innerJoinStudent($relationAlias = null) Adds a INNER JOIN clause to the query using the Student relation
 *
 * @method     ChildEventQuery joinWithStudent($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Student relation
 *
 * @method     ChildEventQuery leftJoinWithStudent() Adds a LEFT JOIN clause and with to the query using the Student relation
 * @method     ChildEventQuery rightJoinWithStudent() Adds a RIGHT JOIN clause and with to the query using the Student relation
 * @method     ChildEventQuery innerJoinWithStudent() Adds a INNER JOIN clause and with to the query using the Student relation
 *
 * @method     ChildEventQuery leftJoinEventawarddegree($relationAlias = null) Adds a LEFT JOIN clause to the query using the Eventawarddegree relation
 * @method     ChildEventQuery rightJoinEventawarddegree($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Eventawarddegree relation
 * @method     ChildEventQuery innerJoinEventawarddegree($relationAlias = null) Adds a INNER JOIN clause to the query using the Eventawarddegree relation
 *
 * @method     ChildEventQuery joinWithEventawarddegree($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Eventawarddegree relation
 *
 * @method     ChildEventQuery leftJoinWithEventawarddegree() Adds a LEFT JOIN clause and with to the query using the Eventawarddegree relation
 * @method     ChildEventQuery rightJoinWithEventawarddegree() Adds a RIGHT JOIN clause and with to the query using the Eventawarddegree relation
 * @method     ChildEventQuery innerJoinWithEventawarddegree() Adds a INNER JOIN clause and with to the query using the Eventawarddegree relation
 *
 * @method     \Models\EventinfoQuery|\Models\TeacherQuery|\Models\EventroleQuery|\Models\StudentQuery|\Models\EventawarddegreeQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildEvent|null findOne(?ConnectionInterface $con = null) Return the first ChildEvent matching the query
 * @method     ChildEvent findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildEvent matching the query, or a new ChildEvent object populated from the query conditions when no match is found
 *
 * @method     ChildEvent|null findOneById(int $id) Return the first ChildEvent filtered by the id column
 * @method     ChildEvent|null findOneByInfoid(int $infoId) Return the first ChildEvent filtered by the infoId column
 * @method     ChildEvent|null findOneByTeacherid(int $teacherId) Return the first ChildEvent filtered by the teacherId column
 * @method     ChildEvent|null findOneByTeacherroleid(int $teacherRoleId) Return the first ChildEvent filtered by the teacherRoleId column
 * @method     ChildEvent|null findOneByStudentid(int $studentId) Return the first ChildEvent filtered by the studentId column
 * @method     ChildEvent|null findOneByAwardid(int $awardId) Return the first ChildEvent filtered by the awardId column
 * @method     ChildEvent|null findOneByDocument(string $document) Return the first ChildEvent filtered by the document column
 *
 * @method     ChildEvent requirePk($key, ?ConnectionInterface $con = null) Return the ChildEvent by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOne(?ConnectionInterface $con = null) Return the first ChildEvent matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEvent requireOneById(int $id) Return the first ChildEvent filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByInfoid(int $infoId) Return the first ChildEvent filtered by the infoId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByTeacherid(int $teacherId) Return the first ChildEvent filtered by the teacherId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByTeacherroleid(int $teacherRoleId) Return the first ChildEvent filtered by the teacherRoleId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByStudentid(int $studentId) Return the first ChildEvent filtered by the studentId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByAwardid(int $awardId) Return the first ChildEvent filtered by the awardId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByDocument(string $document) Return the first ChildEvent filtered by the document column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEvent[]|Collection find(?ConnectionInterface $con = null) Return ChildEvent objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildEvent> find(?ConnectionInterface $con = null) Return ChildEvent objects based on current ModelCriteria
 *
 * @method     ChildEvent[]|Collection findById(int|array<int> $id) Return ChildEvent objects filtered by the id column
 * @psalm-method Collection&\Traversable<ChildEvent> findById(int|array<int> $id) Return ChildEvent objects filtered by the id column
 * @method     ChildEvent[]|Collection findByInfoid(int|array<int> $infoId) Return ChildEvent objects filtered by the infoId column
 * @psalm-method Collection&\Traversable<ChildEvent> findByInfoid(int|array<int> $infoId) Return ChildEvent objects filtered by the infoId column
 * @method     ChildEvent[]|Collection findByTeacherid(int|array<int> $teacherId) Return ChildEvent objects filtered by the teacherId column
 * @psalm-method Collection&\Traversable<ChildEvent> findByTeacherid(int|array<int> $teacherId) Return ChildEvent objects filtered by the teacherId column
 * @method     ChildEvent[]|Collection findByTeacherroleid(int|array<int> $teacherRoleId) Return ChildEvent objects filtered by the teacherRoleId column
 * @psalm-method Collection&\Traversable<ChildEvent> findByTeacherroleid(int|array<int> $teacherRoleId) Return ChildEvent objects filtered by the teacherRoleId column
 * @method     ChildEvent[]|Collection findByStudentid(int|array<int> $studentId) Return ChildEvent objects filtered by the studentId column
 * @psalm-method Collection&\Traversable<ChildEvent> findByStudentid(int|array<int> $studentId) Return ChildEvent objects filtered by the studentId column
 * @method     ChildEvent[]|Collection findByAwardid(int|array<int> $awardId) Return ChildEvent objects filtered by the awardId column
 * @psalm-method Collection&\Traversable<ChildEvent> findByAwardid(int|array<int> $awardId) Return ChildEvent objects filtered by the awardId column
 * @method     ChildEvent[]|Collection findByDocument(string|array<string> $document) Return ChildEvent objects filtered by the document column
 * @psalm-method Collection&\Traversable<ChildEvent> findByDocument(string|array<string> $document) Return ChildEvent objects filtered by the document column
 *
 * @method     ChildEvent[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildEvent> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class EventQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\EventQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Event', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildEventQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildEventQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildEventQuery) {
            return $criteria;
        }
        $query = new ChildEventQuery();
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
     * @return ChildEvent|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EventTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = EventTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildEvent A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, infoId, teacherId, teacherRoleId, studentId, awardId, document FROM event WHERE id = :p0';
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
            /** @var ChildEvent $obj */
            $obj = new ChildEvent();
            $obj->hydrate($row);
            EventTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildEvent|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(EventTableMap::COL_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(EventTableMap::COL_ID, $keys, Criteria::IN);

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
                $this->addUsingAlias(EventTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EventTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the infoId column
     *
     * Example usage:
     * <code>
     * $query->filterByInfoid(1234); // WHERE infoId = 1234
     * $query->filterByInfoid(array(12, 34)); // WHERE infoId IN (12, 34)
     * $query->filterByInfoid(array('min' => 12)); // WHERE infoId > 12
     * </code>
     *
     * @see       filterByEventinfo()
     *
     * @param mixed $infoid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByInfoid($infoid = null, ?string $comparison = null)
    {
        if (is_array($infoid)) {
            $useMinMax = false;
            if (isset($infoid['min'])) {
                $this->addUsingAlias(EventTableMap::COL_INFOID, $infoid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($infoid['max'])) {
                $this->addUsingAlias(EventTableMap::COL_INFOID, $infoid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_INFOID, $infoid, $comparison);

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
                $this->addUsingAlias(EventTableMap::COL_TEACHERID, $teacherid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($teacherid['max'])) {
                $this->addUsingAlias(EventTableMap::COL_TEACHERID, $teacherid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_TEACHERID, $teacherid, $comparison);

        return $this;
    }

    /**
     * Filter the query on the teacherRoleId column
     *
     * Example usage:
     * <code>
     * $query->filterByTeacherroleid(1234); // WHERE teacherRoleId = 1234
     * $query->filterByTeacherroleid(array(12, 34)); // WHERE teacherRoleId IN (12, 34)
     * $query->filterByTeacherroleid(array('min' => 12)); // WHERE teacherRoleId > 12
     * </code>
     *
     * @see       filterByEventrole()
     *
     * @param mixed $teacherroleid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTeacherroleid($teacherroleid = null, ?string $comparison = null)
    {
        if (is_array($teacherroleid)) {
            $useMinMax = false;
            if (isset($teacherroleid['min'])) {
                $this->addUsingAlias(EventTableMap::COL_TEACHERROLEID, $teacherroleid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($teacherroleid['max'])) {
                $this->addUsingAlias(EventTableMap::COL_TEACHERROLEID, $teacherroleid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_TEACHERROLEID, $teacherroleid, $comparison);

        return $this;
    }

    /**
     * Filter the query on the studentId column
     *
     * Example usage:
     * <code>
     * $query->filterByStudentid(1234); // WHERE studentId = 1234
     * $query->filterByStudentid(array(12, 34)); // WHERE studentId IN (12, 34)
     * $query->filterByStudentid(array('min' => 12)); // WHERE studentId > 12
     * </code>
     *
     * @see       filterByStudent()
     *
     * @param mixed $studentid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStudentid($studentid = null, ?string $comparison = null)
    {
        if (is_array($studentid)) {
            $useMinMax = false;
            if (isset($studentid['min'])) {
                $this->addUsingAlias(EventTableMap::COL_STUDENTID, $studentid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($studentid['max'])) {
                $this->addUsingAlias(EventTableMap::COL_STUDENTID, $studentid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_STUDENTID, $studentid, $comparison);

        return $this;
    }

    /**
     * Filter the query on the awardId column
     *
     * Example usage:
     * <code>
     * $query->filterByAwardid(1234); // WHERE awardId = 1234
     * $query->filterByAwardid(array(12, 34)); // WHERE awardId IN (12, 34)
     * $query->filterByAwardid(array('min' => 12)); // WHERE awardId > 12
     * </code>
     *
     * @see       filterByEventawarddegree()
     *
     * @param mixed $awardid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAwardid($awardid = null, ?string $comparison = null)
    {
        if (is_array($awardid)) {
            $useMinMax = false;
            if (isset($awardid['min'])) {
                $this->addUsingAlias(EventTableMap::COL_AWARDID, $awardid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($awardid['max'])) {
                $this->addUsingAlias(EventTableMap::COL_AWARDID, $awardid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_AWARDID, $awardid, $comparison);

        return $this;
    }

    /**
     * Filter the query on the document column
     *
     * Example usage:
     * <code>
     * $query->filterByDocument('fooValue');   // WHERE document = 'fooValue'
     * $query->filterByDocument('%fooValue%', Criteria::LIKE); // WHERE document LIKE '%fooValue%'
     * $query->filterByDocument(['foo', 'bar']); // WHERE document IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $document The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDocument($document = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($document)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_DOCUMENT, $document, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Models\Eventinfo object
     *
     * @param \Models\Eventinfo|ObjectCollection $eventinfo The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEventinfo($eventinfo, ?string $comparison = null)
    {
        if ($eventinfo instanceof \Models\Eventinfo) {
            return $this
                ->addUsingAlias(EventTableMap::COL_INFOID, $eventinfo->getId(), $comparison);
        } elseif ($eventinfo instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(EventTableMap::COL_INFOID, $eventinfo->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByEventinfo() only accepts arguments of type \Models\Eventinfo or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Eventinfo relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinEventinfo(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Eventinfo');

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
            $this->addJoinObject($join, 'Eventinfo');
        }

        return $this;
    }

    /**
     * Use the Eventinfo relation Eventinfo object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\EventinfoQuery A secondary query class using the current class as primary query
     */
    public function useEventinfoQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventinfo($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Eventinfo', '\Models\EventinfoQuery');
    }

    /**
     * Use the Eventinfo relation Eventinfo object
     *
     * @param callable(\Models\EventinfoQuery):\Models\EventinfoQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withEventinfoQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useEventinfoQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Eventinfo table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Models\EventinfoQuery The inner query object of the EXISTS statement
     */
    public function useEventinfoExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Models\EventinfoQuery */
        $q = $this->useExistsQuery('Eventinfo', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Eventinfo table for a NOT EXISTS query.
     *
     * @see useEventinfoExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Models\EventinfoQuery The inner query object of the NOT EXISTS statement
     */
    public function useEventinfoNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Models\EventinfoQuery */
        $q = $this->useExistsQuery('Eventinfo', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Eventinfo table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Models\EventinfoQuery The inner query object of the IN statement
     */
    public function useInEventinfoQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Models\EventinfoQuery */
        $q = $this->useInQuery('Eventinfo', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Eventinfo table for a NOT IN query.
     *
     * @see useEventinfoInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Models\EventinfoQuery The inner query object of the NOT IN statement
     */
    public function useNotInEventinfoQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Models\EventinfoQuery */
        $q = $this->useInQuery('Eventinfo', $modelAlias, $queryClass, 'NOT IN');
        return $q;
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
                ->addUsingAlias(EventTableMap::COL_TEACHERID, $teacher->getId(), $comparison);
        } elseif ($teacher instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(EventTableMap::COL_TEACHERID, $teacher->toKeyValue('PrimaryKey', 'Id'), $comparison);

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
     * Filter the query by a related \Models\Eventrole object
     *
     * @param \Models\Eventrole|ObjectCollection $eventrole The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEventrole($eventrole, ?string $comparison = null)
    {
        if ($eventrole instanceof \Models\Eventrole) {
            return $this
                ->addUsingAlias(EventTableMap::COL_TEACHERROLEID, $eventrole->getId(), $comparison);
        } elseif ($eventrole instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(EventTableMap::COL_TEACHERROLEID, $eventrole->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByEventrole() only accepts arguments of type \Models\Eventrole or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Eventrole relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinEventrole(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Eventrole');

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
            $this->addJoinObject($join, 'Eventrole');
        }

        return $this;
    }

    /**
     * Use the Eventrole relation Eventrole object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\EventroleQuery A secondary query class using the current class as primary query
     */
    public function useEventroleQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventrole($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Eventrole', '\Models\EventroleQuery');
    }

    /**
     * Use the Eventrole relation Eventrole object
     *
     * @param callable(\Models\EventroleQuery):\Models\EventroleQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withEventroleQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useEventroleQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Eventrole table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Models\EventroleQuery The inner query object of the EXISTS statement
     */
    public function useEventroleExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Models\EventroleQuery */
        $q = $this->useExistsQuery('Eventrole', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Eventrole table for a NOT EXISTS query.
     *
     * @see useEventroleExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Models\EventroleQuery The inner query object of the NOT EXISTS statement
     */
    public function useEventroleNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Models\EventroleQuery */
        $q = $this->useExistsQuery('Eventrole', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Eventrole table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Models\EventroleQuery The inner query object of the IN statement
     */
    public function useInEventroleQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Models\EventroleQuery */
        $q = $this->useInQuery('Eventrole', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Eventrole table for a NOT IN query.
     *
     * @see useEventroleInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Models\EventroleQuery The inner query object of the NOT IN statement
     */
    public function useNotInEventroleQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Models\EventroleQuery */
        $q = $this->useInQuery('Eventrole', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Models\Student object
     *
     * @param \Models\Student|ObjectCollection $student The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStudent($student, ?string $comparison = null)
    {
        if ($student instanceof \Models\Student) {
            return $this
                ->addUsingAlias(EventTableMap::COL_STUDENTID, $student->getId(), $comparison);
        } elseif ($student instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(EventTableMap::COL_STUDENTID, $student->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByStudent() only accepts arguments of type \Models\Student or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Student relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinStudent(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Student');

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
            $this->addJoinObject($join, 'Student');
        }

        return $this;
    }

    /**
     * Use the Student relation Student object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\StudentQuery A secondary query class using the current class as primary query
     */
    public function useStudentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinStudent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Student', '\Models\StudentQuery');
    }

    /**
     * Use the Student relation Student object
     *
     * @param callable(\Models\StudentQuery):\Models\StudentQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withStudentQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useStudentQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Student table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Models\StudentQuery The inner query object of the EXISTS statement
     */
    public function useStudentExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Models\StudentQuery */
        $q = $this->useExistsQuery('Student', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Student table for a NOT EXISTS query.
     *
     * @see useStudentExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Models\StudentQuery The inner query object of the NOT EXISTS statement
     */
    public function useStudentNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Models\StudentQuery */
        $q = $this->useExistsQuery('Student', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Student table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Models\StudentQuery The inner query object of the IN statement
     */
    public function useInStudentQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Models\StudentQuery */
        $q = $this->useInQuery('Student', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Student table for a NOT IN query.
     *
     * @see useStudentInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Models\StudentQuery The inner query object of the NOT IN statement
     */
    public function useNotInStudentQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Models\StudentQuery */
        $q = $this->useInQuery('Student', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Models\Eventawarddegree object
     *
     * @param \Models\Eventawarddegree|ObjectCollection $eventawarddegree The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEventawarddegree($eventawarddegree, ?string $comparison = null)
    {
        if ($eventawarddegree instanceof \Models\Eventawarddegree) {
            return $this
                ->addUsingAlias(EventTableMap::COL_AWARDID, $eventawarddegree->getId(), $comparison);
        } elseif ($eventawarddegree instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(EventTableMap::COL_AWARDID, $eventawarddegree->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByEventawarddegree() only accepts arguments of type \Models\Eventawarddegree or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Eventawarddegree relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinEventawarddegree(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Eventawarddegree');

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
            $this->addJoinObject($join, 'Eventawarddegree');
        }

        return $this;
    }

    /**
     * Use the Eventawarddegree relation Eventawarddegree object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\EventawarddegreeQuery A secondary query class using the current class as primary query
     */
    public function useEventawarddegreeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventawarddegree($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Eventawarddegree', '\Models\EventawarddegreeQuery');
    }

    /**
     * Use the Eventawarddegree relation Eventawarddegree object
     *
     * @param callable(\Models\EventawarddegreeQuery):\Models\EventawarddegreeQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withEventawarddegreeQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useEventawarddegreeQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Eventawarddegree table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Models\EventawarddegreeQuery The inner query object of the EXISTS statement
     */
    public function useEventawarddegreeExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Models\EventawarddegreeQuery */
        $q = $this->useExistsQuery('Eventawarddegree', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Eventawarddegree table for a NOT EXISTS query.
     *
     * @see useEventawarddegreeExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Models\EventawarddegreeQuery The inner query object of the NOT EXISTS statement
     */
    public function useEventawarddegreeNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Models\EventawarddegreeQuery */
        $q = $this->useExistsQuery('Eventawarddegree', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Eventawarddegree table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Models\EventawarddegreeQuery The inner query object of the IN statement
     */
    public function useInEventawarddegreeQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Models\EventawarddegreeQuery */
        $q = $this->useInQuery('Eventawarddegree', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Eventawarddegree table for a NOT IN query.
     *
     * @see useEventawarddegreeInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Models\EventawarddegreeQuery The inner query object of the NOT IN statement
     */
    public function useNotInEventawarddegreeQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Models\EventawarddegreeQuery */
        $q = $this->useInQuery('Eventawarddegree', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildEvent $event Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($event = null)
    {
        if ($event) {
            $this->addUsingAlias(EventTableMap::COL_ID, $event->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the event table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            EventTableMap::clearInstancePool();
            EventTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(EventTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            EventTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            EventTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

}
