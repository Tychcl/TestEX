<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\Category as ChildCategory;
use Models\CategoryQuery as ChildCategoryQuery;
use Models\Event as ChildEvent;
use Models\EventQuery as ChildEventQuery;
use Models\Skill as ChildSkill;
use Models\SkillQuery as ChildSkillQuery;
use Models\Teacher as ChildTeacher;
use Models\TeacherQuery as ChildTeacherQuery;
use Models\Userrole as ChildUserrole;
use Models\UserroleQuery as ChildUserroleQuery;
use Models\Map\CategoryTableMap;
use Models\Map\EventTableMap;
use Models\Map\SkillTableMap;
use Models\Map\TeacherTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

/**
 * Base class that represents a row from the 'teacher' table.
 *
 * Преподаватели
 *
 * @package    propel.generator.Models.Base
 */
abstract class Teacher implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Models\\Map\\TeacherTableMap';


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
     * The value for the fio field.
     * ФИО
     * @var        string
     */
    protected $fio;

    /**
     * The value for the login field.
     * Логин
     * @var        string
     */
    protected $login;

    /**
     * The value for the password field.
     * Пароль
     * @var        string
     */
    protected $password;

    /**
     * The value for the roleid field.
     * роль
     * @var        int
     */
    protected $roleid;

    /**
     * The value for the needupdskill field.
     * Необходимость обновить данные об квалификации
     * Note: this column has a database default value of: true
     * @var        boolean
     */
    protected $needupdskill;

    /**
     * The value for the canupdcategory field.
     * Возможность обновить категорию
     * Note: this column has a database default value of: true
     * @var        boolean
     */
    protected $canupdcategory;

    /**
     * @var        ChildUserrole
     */
    protected $aUserrole;

    /**
     * @var        ObjectCollection|ChildCategory[] Collection to store aggregation of ChildCategory objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildCategory> Collection to store aggregation of ChildCategory objects.
     */
    protected $collCategories;
    protected $collCategoriesPartial;

    /**
     * @var        ObjectCollection|ChildEvent[] Collection to store aggregation of ChildEvent objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildEvent> Collection to store aggregation of ChildEvent objects.
     */
    protected $collEvents;
    protected $collEventsPartial;

    /**
     * @var        ObjectCollection|ChildSkill[] Collection to store aggregation of ChildSkill objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildSkill> Collection to store aggregation of ChildSkill objects.
     */
    protected $collSkills;
    protected $collSkillsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCategory[]
     * @phpstan-var ObjectCollection&\Traversable<ChildCategory>
     */
    protected $categoriesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildEvent[]
     * @phpstan-var ObjectCollection&\Traversable<ChildEvent>
     */
    protected $eventsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildSkill[]
     * @phpstan-var ObjectCollection&\Traversable<ChildSkill>
     */
    protected $skillsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues(): void
    {
        $this->needupdskill = true;
        $this->canupdcategory = true;
    }

    /**
     * Initializes internal state of Models\Base\Teacher object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
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
     * Compares this with another <code>Teacher</code> instance.  If
     * <code>obj</code> is an instance of <code>Teacher</code>, delegates to
     * <code>equals(Teacher)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [fio] column value.
     * ФИО
     * @return string
     */
    public function getFio()
    {
        return $this->fio;
    }

    /**
     * Get the [login] column value.
     * Логин
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Get the [password] column value.
     * Пароль
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the [roleid] column value.
     * роль
     * @return int
     */
    public function getRoleid()
    {
        return $this->roleid;
    }

    /**
     * Get the [needupdskill] column value.
     * Необходимость обновить данные об квалификации
     * @return boolean
     */
    public function getNeedupdskill()
    {
        return $this->needupdskill;
    }

    /**
     * Get the [needupdskill] column value.
     * Необходимость обновить данные об квалификации
     * @return boolean
     */
    public function isNeedupdskill()
    {
        return $this->getNeedupdskill();
    }

    /**
     * Get the [canupdcategory] column value.
     * Возможность обновить категорию
     * @return boolean
     */
    public function getCanupdcategory()
    {
        return $this->canupdcategory;
    }

    /**
     * Get the [canupdcategory] column value.
     * Возможность обновить категорию
     * @return boolean
     */
    public function isCanupdcategory()
    {
        return $this->getCanupdcategory();
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
            $this->modifiedColumns[TeacherTableMap::COL_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [fio] column.
     * ФИО
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFio($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->fio !== $v) {
            $this->fio = $v;
            $this->modifiedColumns[TeacherTableMap::COL_FIO] = true;
        }

        return $this;
    }

    /**
     * Set the value of [login] column.
     * Логин
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setLogin($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->login !== $v) {
            $this->login = $v;
            $this->modifiedColumns[TeacherTableMap::COL_LOGIN] = true;
        }

        return $this;
    }

    /**
     * Set the value of [password] column.
     * Пароль
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[TeacherTableMap::COL_PASSWORD] = true;
        }

        return $this;
    }

    /**
     * Set the value of [roleid] column.
     * роль
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setRoleid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->roleid !== $v) {
            $this->roleid = $v;
            $this->modifiedColumns[TeacherTableMap::COL_ROLEID] = true;
        }

        if ($this->aUserrole !== null && $this->aUserrole->getId() !== $v) {
            $this->aUserrole = null;
        }

        return $this;
    }

    /**
     * Sets the value of the [needupdskill] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * Необходимость обновить данные об квалификации
     * @param bool|integer|string $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setNeedupdskill($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->needupdskill !== $v) {
            $this->needupdskill = $v;
            $this->modifiedColumns[TeacherTableMap::COL_NEEDUPDSKILL] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [canupdcategory] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * Возможность обновить категорию
     * @param bool|integer|string $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setCanupdcategory($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->canupdcategory !== $v) {
            $this->canupdcategory = $v;
            $this->modifiedColumns[TeacherTableMap::COL_CANUPDCATEGORY] = true;
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
            if ($this->needupdskill !== true) {
                return false;
            }

            if ($this->canupdcategory !== true) {
                return false;
            }

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : TeacherTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : TeacherTableMap::translateFieldName('Fio', TableMap::TYPE_PHPNAME, $indexType)];
            $this->fio = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : TeacherTableMap::translateFieldName('Login', TableMap::TYPE_PHPNAME, $indexType)];
            $this->login = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : TeacherTableMap::translateFieldName('Password', TableMap::TYPE_PHPNAME, $indexType)];
            $this->password = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : TeacherTableMap::translateFieldName('Roleid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->roleid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : TeacherTableMap::translateFieldName('Needupdskill', TableMap::TYPE_PHPNAME, $indexType)];
            $this->needupdskill = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : TeacherTableMap::translateFieldName('Canupdcategory', TableMap::TYPE_PHPNAME, $indexType)];
            $this->canupdcategory = (null !== $col) ? (boolean) $col : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 7; // 7 = TeacherTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Models\\Teacher'), 0, $e);
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
        if ($this->aUserrole !== null && $this->roleid !== $this->aUserrole->getId()) {
            $this->aUserrole = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(TeacherTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildTeacherQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUserrole = null;
            $this->collCategories = null;

            $this->collEvents = null;

            $this->collSkills = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see Teacher::setDeleted()
     * @see Teacher::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(TeacherTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildTeacherQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(TeacherTableMap::DATABASE_NAME);
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
                TeacherTableMap::addInstanceToPool($this);
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

            if ($this->aUserrole !== null) {
                if ($this->aUserrole->isModified() || $this->aUserrole->isNew()) {
                    $affectedRows += $this->aUserrole->save($con);
                }
                $this->setUserrole($this->aUserrole);
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

            if ($this->categoriesScheduledForDeletion !== null) {
                if (!$this->categoriesScheduledForDeletion->isEmpty()) {
                    \Models\CategoryQuery::create()
                        ->filterByPrimaryKeys($this->categoriesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->categoriesScheduledForDeletion = null;
                }
            }

            if ($this->collCategories !== null) {
                foreach ($this->collCategories as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->eventsScheduledForDeletion !== null) {
                if (!$this->eventsScheduledForDeletion->isEmpty()) {
                    \Models\EventQuery::create()
                        ->filterByPrimaryKeys($this->eventsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->eventsScheduledForDeletion = null;
                }
            }

            if ($this->collEvents !== null) {
                foreach ($this->collEvents as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->skillsScheduledForDeletion !== null) {
                if (!$this->skillsScheduledForDeletion->isEmpty()) {
                    \Models\SkillQuery::create()
                        ->filterByPrimaryKeys($this->skillsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->skillsScheduledForDeletion = null;
                }
            }

            if ($this->collSkills !== null) {
                foreach ($this->collSkills as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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

        $this->modifiedColumns[TeacherTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . TeacherTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(TeacherTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(TeacherTableMap::COL_FIO)) {
            $modifiedColumns[':p' . $index++]  = 'fio';
        }
        if ($this->isColumnModified(TeacherTableMap::COL_LOGIN)) {
            $modifiedColumns[':p' . $index++]  = 'login';
        }
        if ($this->isColumnModified(TeacherTableMap::COL_PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = 'password';
        }
        if ($this->isColumnModified(TeacherTableMap::COL_ROLEID)) {
            $modifiedColumns[':p' . $index++]  = 'roleId';
        }
        if ($this->isColumnModified(TeacherTableMap::COL_NEEDUPDSKILL)) {
            $modifiedColumns[':p' . $index++]  = 'needUpdSkill';
        }
        if ($this->isColumnModified(TeacherTableMap::COL_CANUPDCATEGORY)) {
            $modifiedColumns[':p' . $index++]  = 'canUpdCategory';
        }

        $sql = sprintf(
            'INSERT INTO teacher (%s) VALUES (%s)',
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
                    case 'fio':
                        $stmt->bindValue($identifier, $this->fio, PDO::PARAM_STR);

                        break;
                    case 'login':
                        $stmt->bindValue($identifier, $this->login, PDO::PARAM_STR);

                        break;
                    case 'password':
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);

                        break;
                    case 'roleId':
                        $stmt->bindValue($identifier, $this->roleid, PDO::PARAM_INT);

                        break;
                    case 'needUpdSkill':
                        $stmt->bindValue($identifier, (int) $this->needupdskill, PDO::PARAM_INT);

                        break;
                    case 'canUpdCategory':
                        $stmt->bindValue($identifier, (int) $this->canupdcategory, PDO::PARAM_INT);

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
        $pos = TeacherTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getFio();

            case 2:
                return $this->getLogin();

            case 3:
                return $this->getPassword();

            case 4:
                return $this->getRoleid();

            case 5:
                return $this->getNeedupdskill();

            case 6:
                return $this->getCanupdcategory();

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
        if (isset($alreadyDumpedObjects['Teacher'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['Teacher'][$this->hashCode()] = true;
        $keys = TeacherTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getFio(),
            $keys[2] => $this->getLogin(),
            $keys[3] => $this->getPassword(),
            $keys[4] => $this->getRoleid(),
            $keys[5] => $this->getNeedupdskill(),
            $keys[6] => $this->getCanupdcategory(),
        ];
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aUserrole) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userrole';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'userRole';
                        break;
                    default:
                        $key = 'Userrole';
                }

                $result[$key] = $this->aUserrole->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collCategories) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'categories';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'categories';
                        break;
                    default:
                        $key = 'Categories';
                }

                $result[$key] = $this->collCategories->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEvents) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'events';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'events';
                        break;
                    default:
                        $key = 'Events';
                }

                $result[$key] = $this->collEvents->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSkills) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'skills';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'skills';
                        break;
                    default:
                        $key = 'Skills';
                }

                $result[$key] = $this->collSkills->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = TeacherTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setFio($value);
                break;
            case 2:
                $this->setLogin($value);
                break;
            case 3:
                $this->setPassword($value);
                break;
            case 4:
                $this->setRoleid($value);
                break;
            case 5:
                $this->setNeedupdskill($value);
                break;
            case 6:
                $this->setCanupdcategory($value);
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
        $keys = TeacherTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setFio($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setLogin($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setPassword($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setRoleid($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setNeedupdskill($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setCanupdcategory($arr[$keys[6]]);
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
        $criteria = new Criteria(TeacherTableMap::DATABASE_NAME);

        if ($this->isColumnModified(TeacherTableMap::COL_ID)) {
            $criteria->add(TeacherTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(TeacherTableMap::COL_FIO)) {
            $criteria->add(TeacherTableMap::COL_FIO, $this->fio);
        }
        if ($this->isColumnModified(TeacherTableMap::COL_LOGIN)) {
            $criteria->add(TeacherTableMap::COL_LOGIN, $this->login);
        }
        if ($this->isColumnModified(TeacherTableMap::COL_PASSWORD)) {
            $criteria->add(TeacherTableMap::COL_PASSWORD, $this->password);
        }
        if ($this->isColumnModified(TeacherTableMap::COL_ROLEID)) {
            $criteria->add(TeacherTableMap::COL_ROLEID, $this->roleid);
        }
        if ($this->isColumnModified(TeacherTableMap::COL_NEEDUPDSKILL)) {
            $criteria->add(TeacherTableMap::COL_NEEDUPDSKILL, $this->needupdskill);
        }
        if ($this->isColumnModified(TeacherTableMap::COL_CANUPDCATEGORY)) {
            $criteria->add(TeacherTableMap::COL_CANUPDCATEGORY, $this->canupdcategory);
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
        $criteria = ChildTeacherQuery::create();
        $criteria->add(TeacherTableMap::COL_ID, $this->id);

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
     * @param object $copyObj An object of \Models\Teacher (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setFio($this->getFio());
        $copyObj->setLogin($this->getLogin());
        $copyObj->setPassword($this->getPassword());
        $copyObj->setRoleid($this->getRoleid());
        $copyObj->setNeedupdskill($this->getNeedupdskill());
        $copyObj->setCanupdcategory($this->getCanupdcategory());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getCategories() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCategory($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEvents() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEvent($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSkills() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSkill($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

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
     * @return \Models\Teacher Clone of current object.
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
     * Declares an association between this object and a ChildUserrole object.
     *
     * @param ChildUserrole $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setUserrole(ChildUserrole $v = null)
    {
        if ($v === null) {
            $this->setRoleid(NULL);
        } else {
            $this->setRoleid($v->getId());
        }

        $this->aUserrole = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildUserrole object, it will not be re-added.
        if ($v !== null) {
            $v->addTeacher($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildUserrole object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildUserrole The associated ChildUserrole object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getUserrole(?ConnectionInterface $con = null)
    {
        if ($this->aUserrole === null && ($this->roleid != 0)) {
            $this->aUserrole = ChildUserroleQuery::create()->findPk($this->roleid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUserrole->addTeachers($this);
             */
        }

        return $this->aUserrole;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName): void
    {
        if ('Category' === $relationName) {
            $this->initCategories();
            return;
        }
        if ('Event' === $relationName) {
            $this->initEvents();
            return;
        }
        if ('Skill' === $relationName) {
            $this->initSkills();
            return;
        }
    }

    /**
     * Clears out the collCategories collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addCategories()
     */
    public function clearCategories()
    {
        $this->collCategories = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collCategories collection loaded partially.
     *
     * @return void
     */
    public function resetPartialCategories($v = true): void
    {
        $this->collCategoriesPartial = $v;
    }

    /**
     * Initializes the collCategories collection.
     *
     * By default this just sets the collCategories collection to an empty array (like clearcollCategories());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCategories(bool $overrideExisting = true): void
    {
        if (null !== $this->collCategories && !$overrideExisting) {
            return;
        }

        $collectionClassName = CategoryTableMap::getTableMap()->getCollectionClassName();

        $this->collCategories = new $collectionClassName;
        $this->collCategories->setModel('\Models\Category');
    }

    /**
     * Gets an array of ChildCategory objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildTeacher is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCategory[] List of ChildCategory objects
     * @phpstan-return ObjectCollection&\Traversable<ChildCategory> List of ChildCategory objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getCategories(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collCategoriesPartial && !$this->isNew();
        if (null === $this->collCategories || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collCategories) {
                    $this->initCategories();
                } else {
                    $collectionClassName = CategoryTableMap::getTableMap()->getCollectionClassName();

                    $collCategories = new $collectionClassName;
                    $collCategories->setModel('\Models\Category');

                    return $collCategories;
                }
            } else {
                $collCategories = ChildCategoryQuery::create(null, $criteria)
                    ->filterByTeacher($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCategoriesPartial && count($collCategories)) {
                        $this->initCategories(false);

                        foreach ($collCategories as $obj) {
                            if (false == $this->collCategories->contains($obj)) {
                                $this->collCategories->append($obj);
                            }
                        }

                        $this->collCategoriesPartial = true;
                    }

                    return $collCategories;
                }

                if ($partial && $this->collCategories) {
                    foreach ($this->collCategories as $obj) {
                        if ($obj->isNew()) {
                            $collCategories[] = $obj;
                        }
                    }
                }

                $this->collCategories = $collCategories;
                $this->collCategoriesPartial = false;
            }
        }

        return $this->collCategories;
    }

    /**
     * Sets a collection of ChildCategory objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $categories A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setCategories(Collection $categories, ?ConnectionInterface $con = null)
    {
        /** @var ChildCategory[] $categoriesToDelete */
        $categoriesToDelete = $this->getCategories(new Criteria(), $con)->diff($categories);


        $this->categoriesScheduledForDeletion = $categoriesToDelete;

        foreach ($categoriesToDelete as $categoryRemoved) {
            $categoryRemoved->setTeacher(null);
        }

        $this->collCategories = null;
        foreach ($categories as $category) {
            $this->addCategory($category);
        }

        $this->collCategories = $categories;
        $this->collCategoriesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Category objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Category objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countCategories(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collCategoriesPartial && !$this->isNew();
        if (null === $this->collCategories || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCategories) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCategories());
            }

            $query = ChildCategoryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTeacher($this)
                ->count($con);
        }

        return count($this->collCategories);
    }

    /**
     * Method called to associate a ChildCategory object to this object
     * through the ChildCategory foreign key attribute.
     *
     * @param ChildCategory $l ChildCategory
     * @return $this The current object (for fluent API support)
     */
    public function addCategory(ChildCategory $l)
    {
        if ($this->collCategories === null) {
            $this->initCategories();
            $this->collCategoriesPartial = true;
        }

        if (!$this->collCategories->contains($l)) {
            $this->doAddCategory($l);

            if ($this->categoriesScheduledForDeletion and $this->categoriesScheduledForDeletion->contains($l)) {
                $this->categoriesScheduledForDeletion->remove($this->categoriesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildCategory $category The ChildCategory object to add.
     */
    protected function doAddCategory(ChildCategory $category): void
    {
        $this->collCategories[]= $category;
        $category->setTeacher($this);
    }

    /**
     * @param ChildCategory $category The ChildCategory object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeCategory(ChildCategory $category)
    {
        if ($this->getCategories()->contains($category)) {
            $pos = $this->collCategories->search($category);
            $this->collCategories->remove($pos);
            if (null === $this->categoriesScheduledForDeletion) {
                $this->categoriesScheduledForDeletion = clone $this->collCategories;
                $this->categoriesScheduledForDeletion->clear();
            }
            $this->categoriesScheduledForDeletion[]= clone $category;
            $category->setTeacher(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Teacher is new, it will return
     * an empty collection; or if this Teacher has previously
     * been saved, it will retrieve related Categories from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Teacher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCategory[] List of ChildCategory objects
     * @phpstan-return ObjectCollection&\Traversable<ChildCategory}> List of ChildCategory objects
     */
    public function getCategoriesJoinCategorylist(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCategoryQuery::create(null, $criteria);
        $query->joinWith('Categorylist', $joinBehavior);

        return $this->getCategories($query, $con);
    }

    /**
     * Clears out the collEvents collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addEvents()
     */
    public function clearEvents()
    {
        $this->collEvents = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collEvents collection loaded partially.
     *
     * @return void
     */
    public function resetPartialEvents($v = true): void
    {
        $this->collEventsPartial = $v;
    }

    /**
     * Initializes the collEvents collection.
     *
     * By default this just sets the collEvents collection to an empty array (like clearcollEvents());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEvents(bool $overrideExisting = true): void
    {
        if (null !== $this->collEvents && !$overrideExisting) {
            return;
        }

        $collectionClassName = EventTableMap::getTableMap()->getCollectionClassName();

        $this->collEvents = new $collectionClassName;
        $this->collEvents->setModel('\Models\Event');
    }

    /**
     * Gets an array of ChildEvent objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildTeacher is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildEvent[] List of ChildEvent objects
     * @phpstan-return ObjectCollection&\Traversable<ChildEvent> List of ChildEvent objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getEvents(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collEventsPartial && !$this->isNew();
        if (null === $this->collEvents || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collEvents) {
                    $this->initEvents();
                } else {
                    $collectionClassName = EventTableMap::getTableMap()->getCollectionClassName();

                    $collEvents = new $collectionClassName;
                    $collEvents->setModel('\Models\Event');

                    return $collEvents;
                }
            } else {
                $collEvents = ChildEventQuery::create(null, $criteria)
                    ->filterByTeacher($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collEventsPartial && count($collEvents)) {
                        $this->initEvents(false);

                        foreach ($collEvents as $obj) {
                            if (false == $this->collEvents->contains($obj)) {
                                $this->collEvents->append($obj);
                            }
                        }

                        $this->collEventsPartial = true;
                    }

                    return $collEvents;
                }

                if ($partial && $this->collEvents) {
                    foreach ($this->collEvents as $obj) {
                        if ($obj->isNew()) {
                            $collEvents[] = $obj;
                        }
                    }
                }

                $this->collEvents = $collEvents;
                $this->collEventsPartial = false;
            }
        }

        return $this->collEvents;
    }

    /**
     * Sets a collection of ChildEvent objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $events A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setEvents(Collection $events, ?ConnectionInterface $con = null)
    {
        /** @var ChildEvent[] $eventsToDelete */
        $eventsToDelete = $this->getEvents(new Criteria(), $con)->diff($events);


        $this->eventsScheduledForDeletion = $eventsToDelete;

        foreach ($eventsToDelete as $eventRemoved) {
            $eventRemoved->setTeacher(null);
        }

        $this->collEvents = null;
        foreach ($events as $event) {
            $this->addEvent($event);
        }

        $this->collEvents = $events;
        $this->collEventsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Event objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Event objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countEvents(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collEventsPartial && !$this->isNew();
        if (null === $this->collEvents || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEvents) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEvents());
            }

            $query = ChildEventQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTeacher($this)
                ->count($con);
        }

        return count($this->collEvents);
    }

    /**
     * Method called to associate a ChildEvent object to this object
     * through the ChildEvent foreign key attribute.
     *
     * @param ChildEvent $l ChildEvent
     * @return $this The current object (for fluent API support)
     */
    public function addEvent(ChildEvent $l)
    {
        if ($this->collEvents === null) {
            $this->initEvents();
            $this->collEventsPartial = true;
        }

        if (!$this->collEvents->contains($l)) {
            $this->doAddEvent($l);

            if ($this->eventsScheduledForDeletion and $this->eventsScheduledForDeletion->contains($l)) {
                $this->eventsScheduledForDeletion->remove($this->eventsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildEvent $event The ChildEvent object to add.
     */
    protected function doAddEvent(ChildEvent $event): void
    {
        $this->collEvents[]= $event;
        $event->setTeacher($this);
    }

    /**
     * @param ChildEvent $event The ChildEvent object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeEvent(ChildEvent $event)
    {
        if ($this->getEvents()->contains($event)) {
            $pos = $this->collEvents->search($event);
            $this->collEvents->remove($pos);
            if (null === $this->eventsScheduledForDeletion) {
                $this->eventsScheduledForDeletion = clone $this->collEvents;
                $this->eventsScheduledForDeletion->clear();
            }
            $this->eventsScheduledForDeletion[]= clone $event;
            $event->setTeacher(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Teacher is new, it will return
     * an empty collection; or if this Teacher has previously
     * been saved, it will retrieve related Events from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Teacher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildEvent[] List of ChildEvent objects
     * @phpstan-return ObjectCollection&\Traversable<ChildEvent}> List of ChildEvent objects
     */
    public function getEventsJoinEventinfo(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildEventQuery::create(null, $criteria);
        $query->joinWith('Eventinfo', $joinBehavior);

        return $this->getEvents($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Teacher is new, it will return
     * an empty collection; or if this Teacher has previously
     * been saved, it will retrieve related Events from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Teacher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildEvent[] List of ChildEvent objects
     * @phpstan-return ObjectCollection&\Traversable<ChildEvent}> List of ChildEvent objects
     */
    public function getEventsJoinEventrole(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildEventQuery::create(null, $criteria);
        $query->joinWith('Eventrole', $joinBehavior);

        return $this->getEvents($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Teacher is new, it will return
     * an empty collection; or if this Teacher has previously
     * been saved, it will retrieve related Events from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Teacher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildEvent[] List of ChildEvent objects
     * @phpstan-return ObjectCollection&\Traversable<ChildEvent}> List of ChildEvent objects
     */
    public function getEventsJoinStudent(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildEventQuery::create(null, $criteria);
        $query->joinWith('Student', $joinBehavior);

        return $this->getEvents($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Teacher is new, it will return
     * an empty collection; or if this Teacher has previously
     * been saved, it will retrieve related Events from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Teacher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildEvent[] List of ChildEvent objects
     * @phpstan-return ObjectCollection&\Traversable<ChildEvent}> List of ChildEvent objects
     */
    public function getEventsJoinEventawarddegree(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildEventQuery::create(null, $criteria);
        $query->joinWith('Eventawarddegree', $joinBehavior);

        return $this->getEvents($query, $con);
    }

    /**
     * Clears out the collSkills collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addSkills()
     */
    public function clearSkills()
    {
        $this->collSkills = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collSkills collection loaded partially.
     *
     * @return void
     */
    public function resetPartialSkills($v = true): void
    {
        $this->collSkillsPartial = $v;
    }

    /**
     * Initializes the collSkills collection.
     *
     * By default this just sets the collSkills collection to an empty array (like clearcollSkills());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSkills(bool $overrideExisting = true): void
    {
        if (null !== $this->collSkills && !$overrideExisting) {
            return;
        }

        $collectionClassName = SkillTableMap::getTableMap()->getCollectionClassName();

        $this->collSkills = new $collectionClassName;
        $this->collSkills->setModel('\Models\Skill');
    }

    /**
     * Gets an array of ChildSkill objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildTeacher is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildSkill[] List of ChildSkill objects
     * @phpstan-return ObjectCollection&\Traversable<ChildSkill> List of ChildSkill objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getSkills(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collSkillsPartial && !$this->isNew();
        if (null === $this->collSkills || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collSkills) {
                    $this->initSkills();
                } else {
                    $collectionClassName = SkillTableMap::getTableMap()->getCollectionClassName();

                    $collSkills = new $collectionClassName;
                    $collSkills->setModel('\Models\Skill');

                    return $collSkills;
                }
            } else {
                $collSkills = ChildSkillQuery::create(null, $criteria)
                    ->filterByTeacher($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSkillsPartial && count($collSkills)) {
                        $this->initSkills(false);

                        foreach ($collSkills as $obj) {
                            if (false == $this->collSkills->contains($obj)) {
                                $this->collSkills->append($obj);
                            }
                        }

                        $this->collSkillsPartial = true;
                    }

                    return $collSkills;
                }

                if ($partial && $this->collSkills) {
                    foreach ($this->collSkills as $obj) {
                        if ($obj->isNew()) {
                            $collSkills[] = $obj;
                        }
                    }
                }

                $this->collSkills = $collSkills;
                $this->collSkillsPartial = false;
            }
        }

        return $this->collSkills;
    }

    /**
     * Sets a collection of ChildSkill objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $skills A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setSkills(Collection $skills, ?ConnectionInterface $con = null)
    {
        /** @var ChildSkill[] $skillsToDelete */
        $skillsToDelete = $this->getSkills(new Criteria(), $con)->diff($skills);


        $this->skillsScheduledForDeletion = $skillsToDelete;

        foreach ($skillsToDelete as $skillRemoved) {
            $skillRemoved->setTeacher(null);
        }

        $this->collSkills = null;
        foreach ($skills as $skill) {
            $this->addSkill($skill);
        }

        $this->collSkills = $skills;
        $this->collSkillsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Skill objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Skill objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countSkills(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collSkillsPartial && !$this->isNew();
        if (null === $this->collSkills || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSkills) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSkills());
            }

            $query = ChildSkillQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTeacher($this)
                ->count($con);
        }

        return count($this->collSkills);
    }

    /**
     * Method called to associate a ChildSkill object to this object
     * through the ChildSkill foreign key attribute.
     *
     * @param ChildSkill $l ChildSkill
     * @return $this The current object (for fluent API support)
     */
    public function addSkill(ChildSkill $l)
    {
        if ($this->collSkills === null) {
            $this->initSkills();
            $this->collSkillsPartial = true;
        }

        if (!$this->collSkills->contains($l)) {
            $this->doAddSkill($l);

            if ($this->skillsScheduledForDeletion and $this->skillsScheduledForDeletion->contains($l)) {
                $this->skillsScheduledForDeletion->remove($this->skillsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildSkill $skill The ChildSkill object to add.
     */
    protected function doAddSkill(ChildSkill $skill): void
    {
        $this->collSkills[]= $skill;
        $skill->setTeacher($this);
    }

    /**
     * @param ChildSkill $skill The ChildSkill object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeSkill(ChildSkill $skill)
    {
        if ($this->getSkills()->contains($skill)) {
            $pos = $this->collSkills->search($skill);
            $this->collSkills->remove($pos);
            if (null === $this->skillsScheduledForDeletion) {
                $this->skillsScheduledForDeletion = clone $this->collSkills;
                $this->skillsScheduledForDeletion->clear();
            }
            $this->skillsScheduledForDeletion[]= clone $skill;
            $skill->setTeacher(null);
        }

        return $this;
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
        if (null !== $this->aUserrole) {
            $this->aUserrole->removeTeacher($this);
        }
        $this->id = null;
        $this->fio = null;
        $this->login = null;
        $this->password = null;
        $this->roleid = null;
        $this->needupdskill = null;
        $this->canupdcategory = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
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
            if ($this->collCategories) {
                foreach ($this->collCategories as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEvents) {
                foreach ($this->collEvents as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSkills) {
                foreach ($this->collSkills as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collCategories = null;
        $this->collEvents = null;
        $this->collSkills = null;
        $this->aUserrole = null;
        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(TeacherTableMap::DEFAULT_STRING_FORMAT);
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
