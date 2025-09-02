<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\EventQuery as ChildEventQuery;
use Models\Eventawarddegree as ChildEventawarddegree;
use Models\EventawarddegreeQuery as ChildEventawarddegreeQuery;
use Models\Eventinfo as ChildEventinfo;
use Models\EventinfoQuery as ChildEventinfoQuery;
use Models\Eventrole as ChildEventrole;
use Models\EventroleQuery as ChildEventroleQuery;
use Models\Student as ChildStudent;
use Models\StudentQuery as ChildStudentQuery;
use Models\Teacher as ChildTeacher;
use Models\TeacherQuery as ChildTeacherQuery;
use Models\Map\EventTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

/**
 * Base class that represents a row from the 'event' table.
 *
 *
 *
 * @package    propel.generator.Models.Base
 */
abstract class Event implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Models\\Map\\EventTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var bool
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var bool
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = [];

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = [];

    /**
     * The value for the id field.
     * Идентификатор
     * @var        int
     */
    protected $id;

    /**
     * The value for the infoid field.
     * Информация
     * @var        int
     */
    protected $infoid;

    /**
     * The value for the teacherid field.
     * Преподаватель
     * @var        int
     */
    protected $teacherid;

    /**
     * The value for the teacherroleid field.
     * Роль участия
     * @var        int
     */
    protected $teacherroleid;

    /**
     * The value for the studentid field.
     * Студент
     * @var        int
     */
    protected $studentid;

    /**
     * The value for the awardid field.
     * Награда
     * @var        int
     */
    protected $awardid;

    /**
     * The value for the document field.
     * Документ
     * @var        string
     */
    protected $document;

    /**
     * @var        ChildEventinfo
     */
    protected $aEventinfo;

    /**
     * @var        ChildTeacher
     */
    protected $aTeacher;

    /**
     * @var        ChildEventrole
     */
    protected $aEventrole;

    /**
     * @var        ChildStudent
     */
    protected $aStudent;

    /**
     * @var        ChildEventawarddegree
     */
    protected $aEventawarddegree;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * Initializes internal state of Models\Base\Event object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return bool True if the object has been modified.
     */
    public function isModified(): bool
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param string $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return bool True if $col has been modified.
     */
    public function isColumnModified(string $col): bool
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns(): array
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return bool True, if the object has never been persisted.
     */
    public function isNew(): bool
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param bool $b the state of the object.
     */
    public function setNew(bool $b): void
    {
        $this->new = $b;
    }

    /**
     * Whether this object has been deleted.
     * @return bool The deleted state of this object.
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param bool $b The deleted state of this object.
     * @return void
     */
    public function setDeleted(bool $b): void
    {
        $this->deleted = $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified(?string $col = null): void
    {
        if (null !== $col) {
            unset($this->modifiedColumns[$col]);
        } else {
            $this->modifiedColumns = [];
        }
    }

    /**
     * Compares this with another <code>Event</code> instance.  If
     * <code>obj</code> is an instance of <code>Event</code>, delegates to
     * <code>equals(Event)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param mixed $obj The object to compare to.
     * @return bool Whether equal to the object specified.
     */
    public function equals($obj): bool
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns(): array
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param string $name The virtual column name
     * @return bool
     */
    public function hasVirtualColumn(string $name): bool
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param string $name The virtual column name
     * @return mixed
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getVirtualColumn(string $name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of nonexistent virtual column `%s`.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name The virtual column name
     * @param mixed $value The value to give to the virtual column
     *
     * @return $this The current object, for fluid interface
     */
    public function setVirtualColumn(string $name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param string $msg
     * @param int $priority One of the Propel::LOG_* logging levels
     * @return void
     */
    protected function log(string $msg, int $priority = Propel::LOG_INFO): void
    {
        Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param \Propel\Runtime\Parser\AbstractParser|string $parser An AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param bool $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @param string $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME, TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM. Defaults to TableMap::TYPE_PHPNAME.
     * @return string The exported data
     */
    public function exportTo($parser, bool $includeLazyLoadColumns = true, string $keyType = TableMap::TYPE_PHPNAME): string
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray($keyType, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     *
     * @return array<string>
     */
    public function __sleep(): array
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [id] column value.
     * Идентификатор
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [infoid] column value.
     * Информация
     * @return int
     */
    public function getInfoid()
    {
        return $this->infoid;
    }

    /**
     * Get the [teacherid] column value.
     * Преподаватель
     * @return int
     */
    public function getTeacherid()
    {
        return $this->teacherid;
    }

    /**
     * Get the [teacherroleid] column value.
     * Роль участия
     * @return int
     */
    public function getTeacherroleid()
    {
        return $this->teacherroleid;
    }

    /**
     * Get the [studentid] column value.
     * Студент
     * @return int
     */
    public function getStudentid()
    {
        return $this->studentid;
    }

    /**
     * Get the [awardid] column value.
     * Награда
     * @return int
     */
    public function getAwardid()
    {
        return $this->awardid;
    }

    /**
     * Get the [document] column value.
     * Документ
     * @return string
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Set the value of [id] column.
     * Идентификатор
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[EventTableMap::COL_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [infoid] column.
     * Информация
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setInfoid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->infoid !== $v) {
            $this->infoid = $v;
            $this->modifiedColumns[EventTableMap::COL_INFOID] = true;
        }

        if ($this->aEventinfo !== null && $this->aEventinfo->getId() !== $v) {
            $this->aEventinfo = null;
        }

        return $this;
    }

    /**
     * Set the value of [teacherid] column.
     * Преподаватель
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setTeacherid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->teacherid !== $v) {
            $this->teacherid = $v;
            $this->modifiedColumns[EventTableMap::COL_TEACHERID] = true;
        }

        if ($this->aTeacher !== null && $this->aTeacher->getId() !== $v) {
            $this->aTeacher = null;
        }

        return $this;
    }

    /**
     * Set the value of [teacherroleid] column.
     * Роль участия
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setTeacherroleid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->teacherroleid !== $v) {
            $this->teacherroleid = $v;
            $this->modifiedColumns[EventTableMap::COL_TEACHERROLEID] = true;
        }

        if ($this->aEventrole !== null && $this->aEventrole->getId() !== $v) {
            $this->aEventrole = null;
        }

        return $this;
    }

    /**
     * Set the value of [studentid] column.
     * Студент
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setStudentid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->studentid !== $v) {
            $this->studentid = $v;
            $this->modifiedColumns[EventTableMap::COL_STUDENTID] = true;
        }

        if ($this->aStudent !== null && $this->aStudent->getId() !== $v) {
            $this->aStudent = null;
        }

        return $this;
    }

    /**
     * Set the value of [awardid] column.
     * Награда
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setAwardid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->awardid !== $v) {
            $this->awardid = $v;
            $this->modifiedColumns[EventTableMap::COL_AWARDID] = true;
        }

        if ($this->aEventawarddegree !== null && $this->aEventawarddegree->getId() !== $v) {
            $this->aEventawarddegree = null;
        }

        return $this;
    }

    /**
     * Set the value of [document] column.
     * Документ
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setDocument($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->document !== $v) {
            $this->document = $v;
            $this->modifiedColumns[EventTableMap::COL_DOCUMENT] = true;
        }

        return $this;
    }

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return bool Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues(): bool
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    }

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by DataFetcher->fetch().
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param bool $rehydrate Whether this object is being re-hydrated from the database.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int next starting column
     * @throws \Propel\Runtime\Exception\PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate(array $row, int $startcol = 0, bool $rehydrate = false, string $indexType = TableMap::TYPE_NUM): int
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : EventTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : EventTableMap::translateFieldName('Infoid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->infoid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : EventTableMap::translateFieldName('Teacherid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->teacherid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : EventTableMap::translateFieldName('Teacherroleid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->teacherroleid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : EventTableMap::translateFieldName('Studentid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->studentid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : EventTableMap::translateFieldName('Awardid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->awardid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : EventTableMap::translateFieldName('Document', TableMap::TYPE_PHPNAME, $indexType)];
            $this->document = (null !== $col) ? (string) $col : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 7; // 7 = EventTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Models\\Event'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function ensureConsistency(): void
    {
        if ($this->aEventinfo !== null && $this->infoid !== $this->aEventinfo->getId()) {
            $this->aEventinfo = null;
        }
        if ($this->aTeacher !== null && $this->teacherid !== $this->aTeacher->getId()) {
            $this->aTeacher = null;
        }
        if ($this->aEventrole !== null && $this->teacherroleid !== $this->aEventrole->getId()) {
            $this->aEventrole = null;
        }
        if ($this->aStudent !== null && $this->studentid !== $this->aStudent->getId()) {
            $this->aStudent = null;
        }
        if ($this->aEventawarddegree !== null && $this->awardid !== $this->aEventawarddegree->getId()) {
            $this->aEventawarddegree = null;
        }
    }

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param bool $deep (optional) Whether to also de-associated any related objects.
     * @param ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload(bool $deep = false, ?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EventTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildEventQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aEventinfo = null;
            $this->aTeacher = null;
            $this->aEventrole = null;
            $this->aStudent = null;
            $this->aEventawarddegree = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see Event::setDeleted()
     * @see Event::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildEventQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param ConnectionInterface $con
     * @return int The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws \Propel\Runtime\Exception\PropelException
     * @see doSave()
     */
    public function save(?ConnectionInterface $con = null): int
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                EventTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param ConnectionInterface $con
     * @return int The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws \Propel\Runtime\Exception\PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con): int
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aEventinfo !== null) {
                if ($this->aEventinfo->isModified() || $this->aEventinfo->isNew()) {
                    $affectedRows += $this->aEventinfo->save($con);
                }
                $this->setEventinfo($this->aEventinfo);
            }

            if ($this->aTeacher !== null) {
                if ($this->aTeacher->isModified() || $this->aTeacher->isNew()) {
                    $affectedRows += $this->aTeacher->save($con);
                }
                $this->setTeacher($this->aTeacher);
            }

            if ($this->aEventrole !== null) {
                if ($this->aEventrole->isModified() || $this->aEventrole->isNew()) {
                    $affectedRows += $this->aEventrole->save($con);
                }
                $this->setEventrole($this->aEventrole);
            }

            if ($this->aStudent !== null) {
                if ($this->aStudent->isModified() || $this->aStudent->isNew()) {
                    $affectedRows += $this->aStudent->save($con);
                }
                $this->setStudent($this->aStudent);
            }

            if ($this->aEventawarddegree !== null) {
                if ($this->aEventawarddegree->isModified() || $this->aEventawarddegree->isNew()) {
                    $affectedRows += $this->aEventawarddegree->save($con);
                }
                $this->setEventawarddegree($this->aEventawarddegree);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    }

    /**
     * Insert the row in the database.
     *
     * @param ConnectionInterface $con
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con): void
    {
        $modifiedColumns = [];
        $index = 0;

        $this->modifiedColumns[EventTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . EventTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(EventTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(EventTableMap::COL_INFOID)) {
            $modifiedColumns[':p' . $index++]  = 'infoId';
        }
        if ($this->isColumnModified(EventTableMap::COL_TEACHERID)) {
            $modifiedColumns[':p' . $index++]  = 'teacherId';
        }
        if ($this->isColumnModified(EventTableMap::COL_TEACHERROLEID)) {
            $modifiedColumns[':p' . $index++]  = 'teacherRoleId';
        }
        if ($this->isColumnModified(EventTableMap::COL_STUDENTID)) {
            $modifiedColumns[':p' . $index++]  = 'studentId';
        }
        if ($this->isColumnModified(EventTableMap::COL_AWARDID)) {
            $modifiedColumns[':p' . $index++]  = 'awardId';
        }
        if ($this->isColumnModified(EventTableMap::COL_DOCUMENT)) {
            $modifiedColumns[':p' . $index++]  = 'document';
        }

        $sql = sprintf(
            'INSERT INTO event (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);

                        break;
                    case 'infoId':
                        $stmt->bindValue($identifier, $this->infoid, PDO::PARAM_INT);

                        break;
                    case 'teacherId':
                        $stmt->bindValue($identifier, $this->teacherid, PDO::PARAM_INT);

                        break;
                    case 'teacherRoleId':
                        $stmt->bindValue($identifier, $this->teacherroleid, PDO::PARAM_INT);

                        break;
                    case 'studentId':
                        $stmt->bindValue($identifier, $this->studentid, PDO::PARAM_INT);

                        break;
                    case 'awardId':
                        $stmt->bindValue($identifier, $this->awardid, PDO::PARAM_INT);

                        break;
                    case 'document':
                        $stmt->bindValue($identifier, $this->document, PDO::PARAM_STR);

                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param ConnectionInterface $con
     *
     * @return int Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con): int
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName(string $name, string $type = TableMap::TYPE_PHPNAME)
    {
        $pos = EventTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos Position in XML schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition(int $pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();

            case 1:
                return $this->getInfoid();

            case 2:
                return $this->getTeacherid();

            case 3:
                return $this->getTeacherroleid();

            case 4:
                return $this->getStudentid();

            case 5:
                return $this->getAwardid();

            case 6:
                return $this->getDocument();

            default:
                return null;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param string $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param bool $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param bool $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array An associative array containing the field names (as keys) and field values
     */
    public function toArray(string $keyType = TableMap::TYPE_PHPNAME, bool $includeLazyLoadColumns = true, array $alreadyDumpedObjects = [], bool $includeForeignObjects = false): array
    {
        if (isset($alreadyDumpedObjects['Event'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['Event'][$this->hashCode()] = true;
        $keys = EventTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getInfoid(),
            $keys[2] => $this->getTeacherid(),
            $keys[3] => $this->getTeacherroleid(),
            $keys[4] => $this->getStudentid(),
            $keys[5] => $this->getAwardid(),
            $keys[6] => $this->getDocument(),
        ];
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aEventinfo) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventinfo';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'eventInfo';
                        break;
                    default:
                        $key = 'Eventinfo';
                }

                $result[$key] = $this->aEventinfo->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aTeacher) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'teacher';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'teacher';
                        break;
                    default:
                        $key = 'Teacher';
                }

                $result[$key] = $this->aTeacher->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aEventrole) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventrole';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'eventRole';
                        break;
                    default:
                        $key = 'Eventrole';
                }

                $result[$key] = $this->aEventrole->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aStudent) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'student';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'student';
                        break;
                    default:
                        $key = 'Student';
                }

                $result[$key] = $this->aStudent->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aEventawarddegree) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventawarddegree';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'eventAwardDegree';
                        break;
                    default:
                        $key = 'Eventawarddegree';
                }

                $result[$key] = $this->aEventawarddegree->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this
     */
    public function setByName(string $name, $value, string $type = TableMap::TYPE_PHPNAME)
    {
        $pos = EventTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        $this->setByPosition($pos, $value);

        return $this;
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return $this
     */
    public function setByPosition(int $pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setInfoid($value);
                break;
            case 2:
                $this->setTeacherid($value);
                break;
            case 3:
                $this->setTeacherroleid($value);
                break;
            case 4:
                $this->setStudentid($value);
                break;
            case 5:
                $this->setAwardid($value);
                break;
            case 6:
                $this->setDocument($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param array $arr An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return $this
     */
    public function fromArray(array $arr, string $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = EventTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setInfoid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setTeacherid($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setTeacherroleid($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setStudentid($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setAwardid($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setDocument($arr[$keys[6]]);
        }

        return $this;
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this The current object, for fluid interface
     */
    public function importFrom($parser, string $data, string $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return \Propel\Runtime\ActiveQuery\Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria(): Criteria
    {
        $criteria = new Criteria(EventTableMap::DATABASE_NAME);

        if ($this->isColumnModified(EventTableMap::COL_ID)) {
            $criteria->add(EventTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(EventTableMap::COL_INFOID)) {
            $criteria->add(EventTableMap::COL_INFOID, $this->infoid);
        }
        if ($this->isColumnModified(EventTableMap::COL_TEACHERID)) {
            $criteria->add(EventTableMap::COL_TEACHERID, $this->teacherid);
        }
        if ($this->isColumnModified(EventTableMap::COL_TEACHERROLEID)) {
            $criteria->add(EventTableMap::COL_TEACHERROLEID, $this->teacherroleid);
        }
        if ($this->isColumnModified(EventTableMap::COL_STUDENTID)) {
            $criteria->add(EventTableMap::COL_STUDENTID, $this->studentid);
        }
        if ($this->isColumnModified(EventTableMap::COL_AWARDID)) {
            $criteria->add(EventTableMap::COL_AWARDID, $this->awardid);
        }
        if ($this->isColumnModified(EventTableMap::COL_DOCUMENT)) {
            $criteria->add(EventTableMap::COL_DOCUMENT, $this->document);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return \Propel\Runtime\ActiveQuery\Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria(): Criteria
    {
        $criteria = ChildEventQuery::create();
        $criteria->add(EventTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int|string Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param int|null $key Primary key.
     * @return void
     */
    public function setPrimaryKey(?int $key = null): void
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     *
     * @return bool
     */
    public function isPrimaryKeyNull(): bool
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of \Models\Event (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setInfoid($this->getInfoid());
        $copyObj->setTeacherid($this->getTeacherid());
        $copyObj->setTeacherroleid($this->getTeacherroleid());
        $copyObj->setStudentid($this->getStudentid());
        $copyObj->setAwardid($this->getAwardid());
        $copyObj->setDocument($this->getDocument());
        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Models\Event Clone of current object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function copy(bool $deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildEventinfo object.
     *
     * @param ChildEventinfo $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setEventinfo(ChildEventinfo $v = null)
    {
        if ($v === null) {
            $this->setInfoid(NULL);
        } else {
            $this->setInfoid($v->getId());
        }

        $this->aEventinfo = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildEventinfo object, it will not be re-added.
        if ($v !== null) {
            $v->addEvent($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildEventinfo object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildEventinfo The associated ChildEventinfo object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getEventinfo(?ConnectionInterface $con = null)
    {
        if ($this->aEventinfo === null && ($this->infoid != 0)) {
            $this->aEventinfo = ChildEventinfoQuery::create()->findPk($this->infoid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEventinfo->addEvents($this);
             */
        }

        return $this->aEventinfo;
    }

    /**
     * Declares an association between this object and a ChildTeacher object.
     *
     * @param ChildTeacher $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setTeacher(ChildTeacher $v = null)
    {
        if ($v === null) {
            $this->setTeacherid(NULL);
        } else {
            $this->setTeacherid($v->getId());
        }

        $this->aTeacher = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildTeacher object, it will not be re-added.
        if ($v !== null) {
            $v->addEvent($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildTeacher object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildTeacher The associated ChildTeacher object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getTeacher(?ConnectionInterface $con = null)
    {
        if ($this->aTeacher === null && ($this->teacherid != 0)) {
            $this->aTeacher = ChildTeacherQuery::create()->findPk($this->teacherid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aTeacher->addEvents($this);
             */
        }

        return $this->aTeacher;
    }

    /**
     * Declares an association between this object and a ChildEventrole object.
     *
     * @param ChildEventrole $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setEventrole(ChildEventrole $v = null)
    {
        if ($v === null) {
            $this->setTeacherroleid(NULL);
        } else {
            $this->setTeacherroleid($v->getId());
        }

        $this->aEventrole = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildEventrole object, it will not be re-added.
        if ($v !== null) {
            $v->addEvent($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildEventrole object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildEventrole The associated ChildEventrole object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getEventrole(?ConnectionInterface $con = null)
    {
        if ($this->aEventrole === null && ($this->teacherroleid != 0)) {
            $this->aEventrole = ChildEventroleQuery::create()->findPk($this->teacherroleid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEventrole->addEvents($this);
             */
        }

        return $this->aEventrole;
    }

    /**
     * Declares an association between this object and a ChildStudent object.
     *
     * @param ChildStudent $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setStudent(ChildStudent $v = null)
    {
        if ($v === null) {
            $this->setStudentid(NULL);
        } else {
            $this->setStudentid($v->getId());
        }

        $this->aStudent = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildStudent object, it will not be re-added.
        if ($v !== null) {
            $v->addEvent($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildStudent object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildStudent The associated ChildStudent object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getStudent(?ConnectionInterface $con = null)
    {
        if ($this->aStudent === null && ($this->studentid != 0)) {
            $this->aStudent = ChildStudentQuery::create()->findPk($this->studentid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aStudent->addEvents($this);
             */
        }

        return $this->aStudent;
    }

    /**
     * Declares an association between this object and a ChildEventawarddegree object.
     *
     * @param ChildEventawarddegree $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setEventawarddegree(ChildEventawarddegree $v = null)
    {
        if ($v === null) {
            $this->setAwardid(NULL);
        } else {
            $this->setAwardid($v->getId());
        }

        $this->aEventawarddegree = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildEventawarddegree object, it will not be re-added.
        if ($v !== null) {
            $v->addEvent($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildEventawarddegree object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildEventawarddegree The associated ChildEventawarddegree object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getEventawarddegree(?ConnectionInterface $con = null)
    {
        if ($this->aEventawarddegree === null && ($this->awardid != 0)) {
            $this->aEventawarddegree = ChildEventawarddegreeQuery::create()->findPk($this->awardid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEventawarddegree->addEvents($this);
             */
        }

        return $this->aEventawarddegree;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     *
     * @return $this
     */
    public function clear()
    {
        if (null !== $this->aEventinfo) {
            $this->aEventinfo->removeEvent($this);
        }
        if (null !== $this->aTeacher) {
            $this->aTeacher->removeEvent($this);
        }
        if (null !== $this->aEventrole) {
            $this->aEventrole->removeEvent($this);
        }
        if (null !== $this->aStudent) {
            $this->aStudent->removeEvent($this);
        }
        if (null !== $this->aEventawarddegree) {
            $this->aEventawarddegree->removeEvent($this);
        }
        $this->id = null;
        $this->infoid = null;
        $this->teacherid = null;
        $this->teacherroleid = null;
        $this->studentid = null;
        $this->awardid = null;
        $this->document = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);

        return $this;
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param bool $deep Whether to also clear the references on all referrer objects.
     * @return $this
     */
    public function clearAllReferences(bool $deep = false)
    {
        if ($deep) {
        } // if ($deep)

        $this->aEventinfo = null;
        $this->aTeacher = null;
        $this->aEventrole = null;
        $this->aStudent = null;
        $this->aEventawarddegree = null;
        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(EventTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preSave(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postSave(?ConnectionInterface $con = null): void
    {
            }

    /**
     * Code to be run before inserting to database
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preInsert(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postInsert(?ConnectionInterface $con = null): void
    {
            }

    /**
     * Code to be run before updating the object in database
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preUpdate(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postUpdate(?ConnectionInterface $con = null): void
    {
            }

    /**
     * Code to be run before deleting the object in database
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preDelete(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postDelete(?ConnectionInterface $con = null): void
    {
            }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);
            $inputData = $params[0];
            $keyType = $params[1] ?? TableMap::TYPE_PHPNAME;

            return $this->importFrom($format, $inputData, $keyType);
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = $params[0] ?? true;
            $keyType = $params[1] ?? TableMap::TYPE_PHPNAME;

            return $this->exportTo($format, $includeLazyLoadColumns, $keyType);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
