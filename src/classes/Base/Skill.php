<?php

namespace Base;

use \SkillQuery as ChildSkillQuery;
use \Teacher as ChildTeacher;
use \TeacherQuery as ChildTeacherQuery;
use \DateTime;
use \Exception;
use \PDO;
use Map\SkillTableMap;
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
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'skill' table.
 *
 * Повышение квалификации действует 3 года
 *
 * @package    propel.generator..Base
 */
abstract class Skill implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Map\\SkillTableMap';


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
     * The value for the teacherid field.
     * Преподаватель
     * @var        int
     */
    protected $teacherid;

    /**
     * The value for the num field.
     * Номер удостоверения
     * @var        int
     */
    protected $num;

    /**
     * The value for the regnum field.
     * Регистрационный номер
     * @var        int
     */
    protected $regnum;

    /**
     * The value for the city field.
     * Город
     * @var        string
     */
    protected $city;

    /**
     * The value for the date field.
     * Дата выдачи
     * @var        DateTime
     */
    protected $date;

    /**
     * The value for the place field.
     * Место прохождения
     * @var        string
     */
    protected $place;

    /**
     * The value for the start field.
     * Дата начала
     * @var        DateTime
     */
    protected $start;

    /**
     * The value for the end field.
     * Дата окончания
     * @var        DateTime
     */
    protected $end;

    /**
     * The value for the theme field.
     * Тема
     * @var        string
     */
    protected $theme;

    /**
     * The value for the hours field.
     * Объем в часах
     * @var        int
     */
    protected $hours;

    /**
     * The value for the director field.
     * Руководитель
     * @var        string
     */
    protected $director;

    /**
     * The value for the secretary field.
     * Секретарь
     * @var        string
     */
    protected $secretary;

    /**
     * The value for the docpath field.
     * Путь до документа
     * @var        string
     */
    protected $docpath;

    /**
     * @var        ChildTeacher
     */
    protected $aTeacher;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * Initializes internal state of Base\Skill object.
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
     * Compares this with another <code>Skill</code> instance.  If
     * <code>obj</code> is an instance of <code>Skill</code>, delegates to
     * <code>equals(Skill)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [teacherid] column value.
     * Преподаватель
     * @return int
     */
    public function getTeacherid()
    {
        return $this->teacherid;
    }

    /**
     * Get the [num] column value.
     * Номер удостоверения
     * @return int
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Get the [regnum] column value.
     * Регистрационный номер
     * @return int
     */
    public function getRegnum()
    {
        return $this->regnum;
    }

    /**
     * Get the [city] column value.
     * Город
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Get the [optionally formatted] temporal [date] column value.
     * Дата выдачи
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), and 0 if column value is 0000-00-00.
     *
     * @throws \Propel\Runtime\Exception\PropelException - if unable to parse/validate the date/time value.
     *
     * @psalm-return ($format is null ? DateTime : string)
     */
    public function getDate($format = null)
    {
        if ($format === null) {
            return $this->date;
        } else {
            return $this->date instanceof \DateTimeInterface ? $this->date->format($format) : null;
        }
    }

    /**
     * Get the [place] column value.
     * Место прохождения
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Get the [optionally formatted] temporal [start] column value.
     * Дата начала
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), and 0 if column value is 0000-00-00.
     *
     * @throws \Propel\Runtime\Exception\PropelException - if unable to parse/validate the date/time value.
     *
     * @psalm-return ($format is null ? DateTime : string)
     */
    public function getStart($format = null)
    {
        if ($format === null) {
            return $this->start;
        } else {
            return $this->start instanceof \DateTimeInterface ? $this->start->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [end] column value.
     * Дата окончания
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), and 0 if column value is 0000-00-00.
     *
     * @throws \Propel\Runtime\Exception\PropelException - if unable to parse/validate the date/time value.
     *
     * @psalm-return ($format is null ? DateTime : string)
     */
    public function getEnd($format = null)
    {
        if ($format === null) {
            return $this->end;
        } else {
            return $this->end instanceof \DateTimeInterface ? $this->end->format($format) : null;
        }
    }

    /**
     * Get the [theme] column value.
     * Тема
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Get the [hours] column value.
     * Объем в часах
     * @return int
     */
    public function getHours()
    {
        return $this->hours;
    }

    /**
     * Get the [director] column value.
     * Руководитель
     * @return string
     */
    public function getDirector()
    {
        return $this->director;
    }

    /**
     * Get the [secretary] column value.
     * Секретарь
     * @return string
     */
    public function getSecretary()
    {
        return $this->secretary;
    }

    /**
     * Get the [docpath] column value.
     * Путь до документа
     * @return string
     */
    public function getDocpath()
    {
        return $this->docpath;
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
            $this->modifiedColumns[SkillTableMap::COL_ID] = true;
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
            $this->modifiedColumns[SkillTableMap::COL_TEACHERID] = true;
        }

        if ($this->aTeacher !== null && $this->aTeacher->getId() !== $v) {
            $this->aTeacher = null;
        }

        return $this;
    }

    /**
     * Set the value of [num] column.
     * Номер удостоверения
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setNum($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->num !== $v) {
            $this->num = $v;
            $this->modifiedColumns[SkillTableMap::COL_NUM] = true;
        }

        return $this;
    }

    /**
     * Set the value of [regnum] column.
     * Регистрационный номер
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setRegnum($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->regnum !== $v) {
            $this->regnum = $v;
            $this->modifiedColumns[SkillTableMap::COL_REGNUM] = true;
        }

        return $this;
    }

    /**
     * Set the value of [city] column.
     * Город
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCity($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->city !== $v) {
            $this->city = $v;
            $this->modifiedColumns[SkillTableMap::COL_CITY] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [date] column to a normalized version of the date/time value specified.
     * Дата выдачи
     * @param string|integer|\DateTimeInterface $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->date !== null || $dt !== null) {
            if ($this->date === null || $dt === null || $dt->format("Y-m-d") !== $this->date->format("Y-m-d")) {
                $this->date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[SkillTableMap::COL_DATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Set the value of [place] column.
     * Место прохождения
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPlace($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->place !== $v) {
            $this->place = $v;
            $this->modifiedColumns[SkillTableMap::COL_PLACE] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [start] column to a normalized version of the date/time value specified.
     * Дата начала
     * @param string|integer|\DateTimeInterface $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setStart($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->start !== null || $dt !== null) {
            if ($this->start === null || $dt === null || $dt->format("Y-m-d") !== $this->start->format("Y-m-d")) {
                $this->start = $dt === null ? null : clone $dt;
                $this->modifiedColumns[SkillTableMap::COL_START] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [end] column to a normalized version of the date/time value specified.
     * Дата окончания
     * @param string|integer|\DateTimeInterface $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setEnd($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->end !== null || $dt !== null) {
            if ($this->end === null || $dt === null || $dt->format("Y-m-d") !== $this->end->format("Y-m-d")) {
                $this->end = $dt === null ? null : clone $dt;
                $this->modifiedColumns[SkillTableMap::COL_END] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Set the value of [theme] column.
     * Тема
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setTheme($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->theme !== $v) {
            $this->theme = $v;
            $this->modifiedColumns[SkillTableMap::COL_THEME] = true;
        }

        return $this;
    }

    /**
     * Set the value of [hours] column.
     * Объем в часах
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setHours($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->hours !== $v) {
            $this->hours = $v;
            $this->modifiedColumns[SkillTableMap::COL_HOURS] = true;
        }

        return $this;
    }

    /**
     * Set the value of [director] column.
     * Руководитель
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setDirector($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->director !== $v) {
            $this->director = $v;
            $this->modifiedColumns[SkillTableMap::COL_DIRECTOR] = true;
        }

        return $this;
    }

    /**
     * Set the value of [secretary] column.
     * Секретарь
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSecretary($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->secretary !== $v) {
            $this->secretary = $v;
            $this->modifiedColumns[SkillTableMap::COL_SECRETARY] = true;
        }

        return $this;
    }

    /**
     * Set the value of [docpath] column.
     * Путь до документа
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setDocpath($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->docpath !== $v) {
            $this->docpath = $v;
            $this->modifiedColumns[SkillTableMap::COL_DOCPATH] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : SkillTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : SkillTableMap::translateFieldName('Teacherid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->teacherid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : SkillTableMap::translateFieldName('Num', TableMap::TYPE_PHPNAME, $indexType)];
            $this->num = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : SkillTableMap::translateFieldName('Regnum', TableMap::TYPE_PHPNAME, $indexType)];
            $this->regnum = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : SkillTableMap::translateFieldName('City', TableMap::TYPE_PHPNAME, $indexType)];
            $this->city = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : SkillTableMap::translateFieldName('Date', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00') {
                $col = null;
            }
            $this->date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : SkillTableMap::translateFieldName('Place', TableMap::TYPE_PHPNAME, $indexType)];
            $this->place = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : SkillTableMap::translateFieldName('Start', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00') {
                $col = null;
            }
            $this->start = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : SkillTableMap::translateFieldName('End', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00') {
                $col = null;
            }
            $this->end = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : SkillTableMap::translateFieldName('Theme', TableMap::TYPE_PHPNAME, $indexType)];
            $this->theme = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : SkillTableMap::translateFieldName('Hours', TableMap::TYPE_PHPNAME, $indexType)];
            $this->hours = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : SkillTableMap::translateFieldName('Director', TableMap::TYPE_PHPNAME, $indexType)];
            $this->director = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : SkillTableMap::translateFieldName('Secretary', TableMap::TYPE_PHPNAME, $indexType)];
            $this->secretary = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : SkillTableMap::translateFieldName('Docpath', TableMap::TYPE_PHPNAME, $indexType)];
            $this->docpath = (null !== $col) ? (string) $col : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 14; // 14 = SkillTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Skill'), 0, $e);
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
        if ($this->aTeacher !== null && $this->teacherid !== $this->aTeacher->getId()) {
            $this->aTeacher = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(SkillTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildSkillQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aTeacher = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see Skill::setDeleted()
     * @see Skill::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(SkillTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildSkillQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(SkillTableMap::DATABASE_NAME);
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
                SkillTableMap::addInstanceToPool($this);
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

            if ($this->aTeacher !== null) {
                if ($this->aTeacher->isModified() || $this->aTeacher->isNew()) {
                    $affectedRows += $this->aTeacher->save($con);
                }
                $this->setTeacher($this->aTeacher);
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

        $this->modifiedColumns[SkillTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . SkillTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(SkillTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(SkillTableMap::COL_TEACHERID)) {
            $modifiedColumns[':p' . $index++]  = 'teacherId';
        }
        if ($this->isColumnModified(SkillTableMap::COL_NUM)) {
            $modifiedColumns[':p' . $index++]  = 'num';
        }
        if ($this->isColumnModified(SkillTableMap::COL_REGNUM)) {
            $modifiedColumns[':p' . $index++]  = 'regNum';
        }
        if ($this->isColumnModified(SkillTableMap::COL_CITY)) {
            $modifiedColumns[':p' . $index++]  = 'city';
        }
        if ($this->isColumnModified(SkillTableMap::COL_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'date';
        }
        if ($this->isColumnModified(SkillTableMap::COL_PLACE)) {
            $modifiedColumns[':p' . $index++]  = 'place';
        }
        if ($this->isColumnModified(SkillTableMap::COL_START)) {
            $modifiedColumns[':p' . $index++]  = 'start';
        }
        if ($this->isColumnModified(SkillTableMap::COL_END)) {
            $modifiedColumns[':p' . $index++]  = 'end';
        }
        if ($this->isColumnModified(SkillTableMap::COL_THEME)) {
            $modifiedColumns[':p' . $index++]  = 'theme';
        }
        if ($this->isColumnModified(SkillTableMap::COL_HOURS)) {
            $modifiedColumns[':p' . $index++]  = 'hours';
        }
        if ($this->isColumnModified(SkillTableMap::COL_DIRECTOR)) {
            $modifiedColumns[':p' . $index++]  = 'director';
        }
        if ($this->isColumnModified(SkillTableMap::COL_SECRETARY)) {
            $modifiedColumns[':p' . $index++]  = 'secretary';
        }
        if ($this->isColumnModified(SkillTableMap::COL_DOCPATH)) {
            $modifiedColumns[':p' . $index++]  = 'docPath';
        }

        $sql = sprintf(
            'INSERT INTO skill (%s) VALUES (%s)',
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
                    case 'teacherId':
                        $stmt->bindValue($identifier, $this->teacherid, PDO::PARAM_INT);

                        break;
                    case 'num':
                        $stmt->bindValue($identifier, $this->num, PDO::PARAM_INT);

                        break;
                    case 'regNum':
                        $stmt->bindValue($identifier, $this->regnum, PDO::PARAM_INT);

                        break;
                    case 'city':
                        $stmt->bindValue($identifier, $this->city, PDO::PARAM_STR);

                        break;
                    case 'date':
                        $stmt->bindValue($identifier, $this->date ? $this->date->format("Y-m-d") : null, PDO::PARAM_STR);

                        break;
                    case 'place':
                        $stmt->bindValue($identifier, $this->place, PDO::PARAM_STR);

                        break;
                    case 'start':
                        $stmt->bindValue($identifier, $this->start ? $this->start->format("Y-m-d") : null, PDO::PARAM_STR);

                        break;
                    case 'end':
                        $stmt->bindValue($identifier, $this->end ? $this->end->format("Y-m-d") : null, PDO::PARAM_STR);

                        break;
                    case 'theme':
                        $stmt->bindValue($identifier, $this->theme, PDO::PARAM_STR);

                        break;
                    case 'hours':
                        $stmt->bindValue($identifier, $this->hours, PDO::PARAM_INT);

                        break;
                    case 'director':
                        $stmt->bindValue($identifier, $this->director, PDO::PARAM_STR);

                        break;
                    case 'secretary':
                        $stmt->bindValue($identifier, $this->secretary, PDO::PARAM_STR);

                        break;
                    case 'docPath':
                        $stmt->bindValue($identifier, $this->docpath, PDO::PARAM_STR);

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
        $pos = SkillTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getTeacherid();

            case 2:
                return $this->getNum();

            case 3:
                return $this->getRegnum();

            case 4:
                return $this->getCity();

            case 5:
                return $this->getDate();

            case 6:
                return $this->getPlace();

            case 7:
                return $this->getStart();

            case 8:
                return $this->getEnd();

            case 9:
                return $this->getTheme();

            case 10:
                return $this->getHours();

            case 11:
                return $this->getDirector();

            case 12:
                return $this->getSecretary();

            case 13:
                return $this->getDocpath();

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
        if (isset($alreadyDumpedObjects['Skill'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['Skill'][$this->hashCode()] = true;
        $keys = SkillTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getTeacherid(),
            $keys[2] => $this->getNum(),
            $keys[3] => $this->getRegnum(),
            $keys[4] => $this->getCity(),
            $keys[5] => $this->getDate(),
            $keys[6] => $this->getPlace(),
            $keys[7] => $this->getStart(),
            $keys[8] => $this->getEnd(),
            $keys[9] => $this->getTheme(),
            $keys[10] => $this->getHours(),
            $keys[11] => $this->getDirector(),
            $keys[12] => $this->getSecretary(),
            $keys[13] => $this->getDocpath(),
        ];
        if ($result[$keys[5]] instanceof \DateTimeInterface) {
            $result[$keys[5]] = $result[$keys[5]]->format('Y-m-d');
        }

        if ($result[$keys[7]] instanceof \DateTimeInterface) {
            $result[$keys[7]] = $result[$keys[7]]->format('Y-m-d');
        }

        if ($result[$keys[8]] instanceof \DateTimeInterface) {
            $result[$keys[8]] = $result[$keys[8]]->format('Y-m-d');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
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
        $pos = SkillTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setTeacherid($value);
                break;
            case 2:
                $this->setNum($value);
                break;
            case 3:
                $this->setRegnum($value);
                break;
            case 4:
                $this->setCity($value);
                break;
            case 5:
                $this->setDate($value);
                break;
            case 6:
                $this->setPlace($value);
                break;
            case 7:
                $this->setStart($value);
                break;
            case 8:
                $this->setEnd($value);
                break;
            case 9:
                $this->setTheme($value);
                break;
            case 10:
                $this->setHours($value);
                break;
            case 11:
                $this->setDirector($value);
                break;
            case 12:
                $this->setSecretary($value);
                break;
            case 13:
                $this->setDocpath($value);
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
        $keys = SkillTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setTeacherid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setNum($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setRegnum($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setCity($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setDate($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setPlace($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setStart($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setEnd($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setTheme($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setHours($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setDirector($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setSecretary($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setDocpath($arr[$keys[13]]);
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
        $criteria = new Criteria(SkillTableMap::DATABASE_NAME);

        if ($this->isColumnModified(SkillTableMap::COL_ID)) {
            $criteria->add(SkillTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(SkillTableMap::COL_TEACHERID)) {
            $criteria->add(SkillTableMap::COL_TEACHERID, $this->teacherid);
        }
        if ($this->isColumnModified(SkillTableMap::COL_NUM)) {
            $criteria->add(SkillTableMap::COL_NUM, $this->num);
        }
        if ($this->isColumnModified(SkillTableMap::COL_REGNUM)) {
            $criteria->add(SkillTableMap::COL_REGNUM, $this->regnum);
        }
        if ($this->isColumnModified(SkillTableMap::COL_CITY)) {
            $criteria->add(SkillTableMap::COL_CITY, $this->city);
        }
        if ($this->isColumnModified(SkillTableMap::COL_DATE)) {
            $criteria->add(SkillTableMap::COL_DATE, $this->date);
        }
        if ($this->isColumnModified(SkillTableMap::COL_PLACE)) {
            $criteria->add(SkillTableMap::COL_PLACE, $this->place);
        }
        if ($this->isColumnModified(SkillTableMap::COL_START)) {
            $criteria->add(SkillTableMap::COL_START, $this->start);
        }
        if ($this->isColumnModified(SkillTableMap::COL_END)) {
            $criteria->add(SkillTableMap::COL_END, $this->end);
        }
        if ($this->isColumnModified(SkillTableMap::COL_THEME)) {
            $criteria->add(SkillTableMap::COL_THEME, $this->theme);
        }
        if ($this->isColumnModified(SkillTableMap::COL_HOURS)) {
            $criteria->add(SkillTableMap::COL_HOURS, $this->hours);
        }
        if ($this->isColumnModified(SkillTableMap::COL_DIRECTOR)) {
            $criteria->add(SkillTableMap::COL_DIRECTOR, $this->director);
        }
        if ($this->isColumnModified(SkillTableMap::COL_SECRETARY)) {
            $criteria->add(SkillTableMap::COL_SECRETARY, $this->secretary);
        }
        if ($this->isColumnModified(SkillTableMap::COL_DOCPATH)) {
            $criteria->add(SkillTableMap::COL_DOCPATH, $this->docpath);
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
        $criteria = ChildSkillQuery::create();
        $criteria->add(SkillTableMap::COL_ID, $this->id);

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
     * @param object $copyObj An object of \Skill (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setTeacherid($this->getTeacherid());
        $copyObj->setNum($this->getNum());
        $copyObj->setRegnum($this->getRegnum());
        $copyObj->setCity($this->getCity());
        $copyObj->setDate($this->getDate());
        $copyObj->setPlace($this->getPlace());
        $copyObj->setStart($this->getStart());
        $copyObj->setEnd($this->getEnd());
        $copyObj->setTheme($this->getTheme());
        $copyObj->setHours($this->getHours());
        $copyObj->setDirector($this->getDirector());
        $copyObj->setSecretary($this->getSecretary());
        $copyObj->setDocpath($this->getDocpath());
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
     * @return \Skill Clone of current object.
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
            $v->addSkill($this);
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
                $this->aTeacher->addSkills($this);
             */
        }

        return $this->aTeacher;
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
        if (null !== $this->aTeacher) {
            $this->aTeacher->removeSkill($this);
        }
        $this->id = null;
        $this->teacherid = null;
        $this->num = null;
        $this->regnum = null;
        $this->city = null;
        $this->date = null;
        $this->place = null;
        $this->start = null;
        $this->end = null;
        $this->theme = null;
        $this->hours = null;
        $this->director = null;
        $this->secretary = null;
        $this->docpath = null;
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

        $this->aTeacher = null;
        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(SkillTableMap::DEFAULT_STRING_FORMAT);
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
