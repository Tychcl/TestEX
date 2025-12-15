<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\Tasks as ChildTasks;
use Models\TasksQuery as ChildTasksQuery;
use Models\Map\TasksTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `Tasks` table.
 *
 * @method     ChildTasksQuery orderById($order = Criteria::ASC) Order by the Id column
 * @method     ChildTasksQuery orderByTitle($order = Criteria::ASC) Order by the Title column
 * @method     ChildTasksQuery orderByDescription($order = Criteria::ASC) Order by the Description column
 * @method     ChildTasksQuery orderByStatus($order = Criteria::ASC) Order by the Status column
 *
 * @method     ChildTasksQuery groupById() Group by the Id column
 * @method     ChildTasksQuery groupByTitle() Group by the Title column
 * @method     ChildTasksQuery groupByDescription() Group by the Description column
 * @method     ChildTasksQuery groupByStatus() Group by the Status column
 *
 * @method     ChildTasksQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildTasksQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildTasksQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildTasksQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildTasksQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildTasksQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildTasksQuery leftJoinStatuses($relationAlias = null) Adds a LEFT JOIN clause to the query using the Statuses relation
 * @method     ChildTasksQuery rightJoinStatuses($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Statuses relation
 * @method     ChildTasksQuery innerJoinStatuses($relationAlias = null) Adds a INNER JOIN clause to the query using the Statuses relation
 *
 * @method     ChildTasksQuery joinWithStatuses($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Statuses relation
 *
 * @method     ChildTasksQuery leftJoinWithStatuses() Adds a LEFT JOIN clause and with to the query using the Statuses relation
 * @method     ChildTasksQuery rightJoinWithStatuses() Adds a RIGHT JOIN clause and with to the query using the Statuses relation
 * @method     ChildTasksQuery innerJoinWithStatuses() Adds a INNER JOIN clause and with to the query using the Statuses relation
 *
 * @method     \Models\StatusesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildTasks|null findOne(?ConnectionInterface $con = null) Return the first ChildTasks matching the query
 * @method     ChildTasks findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildTasks matching the query, or a new ChildTasks object populated from the query conditions when no match is found
 *
 * @method     ChildTasks|null findOneById(int $Id) Return the first ChildTasks filtered by the Id column
 * @method     ChildTasks|null findOneByTitle(string $Title) Return the first ChildTasks filtered by the Title column
 * @method     ChildTasks|null findOneByDescription(string $Description) Return the first ChildTasks filtered by the Description column
 * @method     ChildTasks|null findOneByStatus(int $Status) Return the first ChildTasks filtered by the Status column
 *
 * @method     ChildTasks requirePk($key, ?ConnectionInterface $con = null) Return the ChildTasks by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTasks requireOne(?ConnectionInterface $con = null) Return the first ChildTasks matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTasks requireOneById(int $Id) Return the first ChildTasks filtered by the Id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTasks requireOneByTitle(string $Title) Return the first ChildTasks filtered by the Title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTasks requireOneByDescription(string $Description) Return the first ChildTasks filtered by the Description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTasks requireOneByStatus(int $Status) Return the first ChildTasks filtered by the Status column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTasks[]|Collection find(?ConnectionInterface $con = null) Return ChildTasks objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildTasks> find(?ConnectionInterface $con = null) Return ChildTasks objects based on current ModelCriteria
 *
 * @method     ChildTasks[]|Collection findById(int|array<int> $Id) Return ChildTasks objects filtered by the Id column
 * @psalm-method Collection&\Traversable<ChildTasks> findById(int|array<int> $Id) Return ChildTasks objects filtered by the Id column
 * @method     ChildTasks[]|Collection findByTitle(string|array<string> $Title) Return ChildTasks objects filtered by the Title column
 * @psalm-method Collection&\Traversable<ChildTasks> findByTitle(string|array<string> $Title) Return ChildTasks objects filtered by the Title column
 * @method     ChildTasks[]|Collection findByDescription(string|array<string> $Description) Return ChildTasks objects filtered by the Description column
 * @psalm-method Collection&\Traversable<ChildTasks> findByDescription(string|array<string> $Description) Return ChildTasks objects filtered by the Description column
 * @method     ChildTasks[]|Collection findByStatus(int|array<int> $Status) Return ChildTasks objects filtered by the Status column
 * @psalm-method Collection&\Traversable<ChildTasks> findByStatus(int|array<int> $Status) Return ChildTasks objects filtered by the Status column
 *
 * @method     ChildTasks[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildTasks> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class TasksQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\TasksQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Tasks', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildTasksQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildTasksQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildTasksQuery) {
            return $criteria;
        }
        $query = new ChildTasksQuery();
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
     * @return ChildTasks|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(TasksTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = TasksTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildTasks A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT Id, Title, Description, Status FROM Tasks WHERE Id = :p0';
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
            /** @var ChildTasks $obj */
            $obj = new ChildTasks();
            $obj->hydrate($row);
            TasksTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildTasks|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(TasksTableMap::COL_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(TasksTableMap::COL_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the Id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE Id = 1234
     * $query->filterById(array(12, 34)); // WHERE Id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE Id > 12
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
                $this->addUsingAlias(TasksTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(TasksTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(TasksTableMap::COL_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the Title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE Title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE Title LIKE '%fooValue%'
     * $query->filterByTitle(['foo', 'bar']); // WHERE Title IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $title The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTitle($title = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(TasksTableMap::COL_TITLE, $title, $comparison);

        return $this;
    }

    /**
     * Filter the query on the Description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE Description = 'fooValue'
     * $query->filterByDescription('%fooValue%', Criteria::LIKE); // WHERE Description LIKE '%fooValue%'
     * $query->filterByDescription(['foo', 'bar']); // WHERE Description IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $description The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDescription($description = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(TasksTableMap::COL_DESCRIPTION, $description, $comparison);

        return $this;
    }

    /**
     * Filter the query on the Status column
     *
     * Example usage:
     * <code>
     * $query->filterByStatus(1234); // WHERE Status = 1234
     * $query->filterByStatus(array(12, 34)); // WHERE Status IN (12, 34)
     * $query->filterByStatus(array('min' => 12)); // WHERE Status > 12
     * </code>
     *
     * @see       filterByStatuses()
     *
     * @param mixed $status The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStatus($status = null, ?string $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(TasksTableMap::COL_STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(TasksTableMap::COL_STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(TasksTableMap::COL_STATUS, $status, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Models\Statuses object
     *
     * @param \Models\Statuses|ObjectCollection $statuses The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStatuses($statuses, ?string $comparison = null)
    {
        if ($statuses instanceof \Models\Statuses) {
            return $this
                ->addUsingAlias(TasksTableMap::COL_STATUS, $statuses->getId(), $comparison);
        } elseif ($statuses instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(TasksTableMap::COL_STATUS, $statuses->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByStatuses() only accepts arguments of type \Models\Statuses or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Statuses relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinStatuses(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Statuses');

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
            $this->addJoinObject($join, 'Statuses');
        }

        return $this;
    }

    /**
     * Use the Statuses relation Statuses object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\StatusesQuery A secondary query class using the current class as primary query
     */
    public function useStatusesQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinStatuses($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Statuses', '\Models\StatusesQuery');
    }

    /**
     * Use the Statuses relation Statuses object
     *
     * @param callable(\Models\StatusesQuery):\Models\StatusesQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withStatusesQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useStatusesQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Statuses table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Models\StatusesQuery The inner query object of the EXISTS statement
     */
    public function useStatusesExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Models\StatusesQuery */
        $q = $this->useExistsQuery('Statuses', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Statuses table for a NOT EXISTS query.
     *
     * @see useStatusesExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Models\StatusesQuery The inner query object of the NOT EXISTS statement
     */
    public function useStatusesNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Models\StatusesQuery */
        $q = $this->useExistsQuery('Statuses', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Statuses table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Models\StatusesQuery The inner query object of the IN statement
     */
    public function useInStatusesQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Models\StatusesQuery */
        $q = $this->useInQuery('Statuses', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Statuses table for a NOT IN query.
     *
     * @see useStatusesInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Models\StatusesQuery The inner query object of the NOT IN statement
     */
    public function useNotInStatusesQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Models\StatusesQuery */
        $q = $this->useInQuery('Statuses', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildTasks $tasks Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($tasks = null)
    {
        if ($tasks) {
            $this->addUsingAlias(TasksTableMap::COL_ID, $tasks->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the Tasks table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TasksTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            TasksTableMap::clearInstancePool();
            TasksTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(TasksTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(TasksTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            TasksTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            TasksTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

}
