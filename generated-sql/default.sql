
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- category
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
    `teacherId` INTEGER NOT NULL COMMENT 'Преподаватель',
    `organ` TEXT NOT NULL COMMENT 'Орган издавший приказ',
    `date` DATE NOT NULL COMMENT 'Дата',
    `num` INTEGER NOT NULL COMMENT 'Номер приказа',
    `post` TEXT NOT NULL COMMENT 'Должность',
    `place` TEXT NOT NULL COMMENT 'Образовательная организация',
    `categoryId` INTEGER NOT NULL COMMENT 'Присвоенная категория',
    PRIMARY KEY (`id`),
    INDEX `teacher` (`teacherId`),
    INDEX `category` (`categoryId`),
    CONSTRAINT `category_ibfk_1`
        FOREIGN KEY (`teacherId`)
        REFERENCES `teacher` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT `category_ibfk_2`
        FOREIGN KEY (`categoryId`)
        REFERENCES `categoryList` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- categoryList
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `categoryList`;

CREATE TABLE `categoryList`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
    `name` VARCHAR(65) NOT NULL COMMENT 'Наименование ',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- event
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `event`;

CREATE TABLE `event`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
    `infoId` INTEGER NOT NULL COMMENT 'Информация',
    `teacherId` INTEGER NOT NULL COMMENT 'Преподаватель',
    `teacherRoleId` INTEGER NOT NULL COMMENT 'Роль участия',
    `studentId` INTEGER NOT NULL COMMENT 'Студент',
    `awardId` INTEGER NOT NULL COMMENT 'Награда',
    `document` TEXT NOT NULL COMMENT 'Документ',
    PRIMARY KEY (`id`),
    INDEX `event_ibfk_1` (`infoId`),
    INDEX `teacher` (`teacherId`),
    INDEX `teacherRole` (`teacherRoleId`),
    INDEX `student` (`studentId`),
    INDEX `award` (`awardId`),
    CONSTRAINT `event_ibfk_1`
        FOREIGN KEY (`infoId`)
        REFERENCES `eventInfo` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT `event_ibfk_2`
        FOREIGN KEY (`teacherId`)
        REFERENCES `teacher` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT `event_ibfk_3`
        FOREIGN KEY (`teacherRoleId`)
        REFERENCES `eventRole` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT `event_ibfk_4`
        FOREIGN KEY (`studentId`)
        REFERENCES `student` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT `event_ibfk_5`
        FOREIGN KEY (`awardId`)
        REFERENCES `eventAwardDegree` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- eventAwardDegree
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `eventAwardDegree`;

CREATE TABLE `eventAwardDegree`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
    `name` VARCHAR(45) NOT NULL COMMENT 'Наименование ',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='Степени награждения';

-- ---------------------------------------------------------------------
-- eventInfo
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `eventInfo`;

CREATE TABLE `eventInfo`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
    `name` VARCHAR(255) NOT NULL COMMENT 'Наименование',
    `start` DATE NOT NULL COMMENT 'Дата начала ',
    `end` DATE NOT NULL COMMENT 'Дата окончания',
    `level` INTEGER COMMENT 'Уровень проведения',
    PRIMARY KEY (`id`),
    INDEX `level` (`level`),
    CONSTRAINT `eventinfo_ibfk_1`
        FOREIGN KEY (`level`)
        REFERENCES `eventLevel` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB COMMENT='Чемпионаты';

-- ---------------------------------------------------------------------
-- eventLevel
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `eventLevel`;

CREATE TABLE `eventLevel`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
    `name` VARCHAR(30) NOT NULL COMMENT 'Наименование',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='Уровни чемпионатов';

-- ---------------------------------------------------------------------
-- eventRole
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `eventRole`;

CREATE TABLE `eventRole`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
    `name` VARCHAR(45) NOT NULL COMMENT 'Наименование',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='Роль участия';

-- ---------------------------------------------------------------------
-- skill
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `skill`;

CREATE TABLE `skill`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
    `teacherId` INTEGER NOT NULL COMMENT 'Преподаватель',
    `num` INTEGER NOT NULL COMMENT 'Номер удостоверения',
    `regNum` INTEGER NOT NULL COMMENT 'Регистрационный номер',
    `city` VARCHAR(65) NOT NULL COMMENT 'Город',
    `date` DATE NOT NULL COMMENT 'Дата выдачи',
    `place` TEXT NOT NULL COMMENT 'Место прохождения',
    `start` DATE NOT NULL COMMENT 'Дата начала',
    `end` DATE NOT NULL COMMENT 'Дата окончания',
    `theme` TEXT NOT NULL COMMENT 'Тема',
    `hours` INTEGER NOT NULL COMMENT 'Объем в часах',
    `director` VARCHAR(65) NOT NULL COMMENT 'Руководитель',
    `secretary` VARCHAR(65) NOT NULL COMMENT 'Секретарь',
    `docPath` TEXT NOT NULL COMMENT 'Путь до документа',
    PRIMARY KEY (`id`),
    INDEX `teacher` (`teacherId`),
    CONSTRAINT `skill_ibfk_1`
        FOREIGN KEY (`teacherId`)
        REFERENCES `teacher` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB COMMENT='Повышение квалификации действует 3 года';

-- ---------------------------------------------------------------------
-- student
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `student`;

CREATE TABLE `student`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
    `surname` VARCHAR(45) NOT NULL COMMENT 'Фамилия',
    `name` VARCHAR(45) NOT NULL COMMENT 'Имя',
    `midname` VARCHAR(45) NOT NULL COMMENT 'Отчество',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='Студенты';

-- ---------------------------------------------------------------------
-- teacher
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `teacher`;

CREATE TABLE `teacher`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
    `surname` VARCHAR(45) NOT NULL COMMENT 'Фамилия',
    `name` VARCHAR(45) NOT NULL COMMENT 'Имя',
    `midname` VARCHAR(45) NOT NULL COMMENT 'Отчество',
    `login` TEXT NOT NULL COMMENT 'Логин',
    `password` TEXT NOT NULL COMMENT 'Пароль',
    `roleId` INTEGER NOT NULL COMMENT 'роль',
    `needUpdSkill` TINYINT(1) DEFAULT 1 NOT NULL COMMENT 'Необходимость обновить данные об квалификации',
    `canUpdCategory` TINYINT(1) DEFAULT 1 NOT NULL COMMENT 'Возможность обновить категорию',
    PRIMARY KEY (`id`),
    INDEX `role` (`roleId`),
    CONSTRAINT `teacher_ibfk_1`
        FOREIGN KEY (`roleId`)
        REFERENCES `userRole` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB COMMENT='Преподаватели';

-- ---------------------------------------------------------------------
-- userRole
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `userRole`;

CREATE TABLE `userRole`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
    `name` VARCHAR(10) NOT NULL COMMENT 'Роль',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
