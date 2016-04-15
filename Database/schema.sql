/*This schema builds the database structure for Bill.eGoat, 
  *and contains user, template and bill data in a normalised form.
  * @author Daryl Lim
*/

/*
  *@desc Initialisation, this section recreates the DB
*/
DROP DATABASE IF EXISTS billDB;
DROP DATABASE IF EXISTS templateDB;
DROP DATABASE IF EXISTS userDB;

CREATE DATABASE userDB;

USE userDB;

/*
  *@desc Username/password table, minimum requirements
  *for authentication/identification of an account
*/
CREATE TABLE userDB.users
(
	userID INTEGER AUTO_INCREMENT NOT NULL,
    passwd VARCHAR(41) NOT NULL,
	userEmail VARCHAR(255) NOT NULL,
	
	-- Fields used but feature unimplemented
    isPartnerOrg BOOLEAN,
    failedLoginNo TINYINT,
    isActivated BOOLEAN NOT NULL,
    activationCode VARCHAR(100),
    lastLoggedIn TIMESTAMP,
    
    PRIMARY KEY (userID)
);

CREATE DATABASE templateDB;

USE templateDB;

/*
  *@desc Contains minimum data to identify a template
*/
CREATE TABLE templateDB.templates
(
	templateID INTEGER AUTO_INCREMENT NOT NULL,
    billOrg VARCHAR (100),
    creatorID INTEGER,
    fileImgPath VARCHAR (255),
    dateCreated DATETIME,
    dateModified TIMESTAMP,
    
    PRIMARY KEY (templateID)
);


/*
  *@desc Holds data for fields in template maps
*/
CREATE TABLE templateDB.dataFields
(
	templateID INTEGER,
    dataFieldLabel VARCHAR (100),
    coordinateLabelX FLOAT,
    coordinateLabelY FLOAT,
	coordinateLabelX2 FLOAT,
    coordinateLabelY2 FLOAT,
    
    PRIMARY KEY (templateID,dataFieldLabel)
);

CREATE DATABASE billDB;

USE billDB;

/*
  *@desc Contains minimum data to identify a particular bill
*/
CREATE TABLE billDB.bills
(
	billID INTEGER AUTO_INCREMENT NOT NULL,
	userID INTEGER NOT NULL,
	billOrg VARCHAR (100),
    submittedTimeStamp DATETIME NOT NULL,
    billFilePath VARCHAR (255) UNIQUE,
    revisionNo INTEGER NOT NULL,
	templateID INTEGER,
    billSentDate DATE,
    billDueDate DATE,
	totalAmt DECIMAL (19,4),
    billIsComplete BOOLEAN NOT NULL,
	billIsVerified BOOLEAN NOT NULL,
    billIsCopy BOOLEAN NOT NULL,
	billCompleteDateTime DATE,
    billModifiedTimeStamp TIMESTAMP,   
    PRIMARY KEY (billID)
);

/*
  *@desc Holds all tags associated with a certain bill
*/
CREATE TABLE billDB.billTags
(
	billID INTEGER,
    tagName VARCHAR(50),
    
    PRIMARY KEY (billID,tagName)
);

-- Unused tables, pending implementation of new features in further work

/*

/**
  *@desc Contains all user emails, including reminder
  *and recovery emails

CREATE TABLE userDB.emails
(
	userID INTEGER NOT NULL,
    userEmail VARCHAR(255) NOT NULL,
    isReminderEmail BOOLEAN NOT NULL,
    isRecoveryEmail BOOLEAN NOT NULL,
    
    PRIMARY KEY (userID, userEmail)
);

/**
  *@desc Friends list for auto-complete when trying to share
  *bills

CREATE TABLE userDB.friends
(
	userID INTEGER NOT NULL,
    friendID INTEGER NOT NULL,
    
    PRIMARY KEY (userID, friendID)

);

/**
  *@desc Holds all possible fields pertaining to monetary
  *values within a bill.

CREATE TABLE billDB.billAmts
(
	billID INTEGER NOT NULL,
    amtLabel VARCHAR(100) NOT NULL,
    amt DECIMAL(19,4),
    currency CHAR(3),
    
    PRIMARY KEY (billID,amtLabel)
);

/**
  *@desc Contains financial account information, users can
  *indicate which account paid for which expense.

CREATE TABLE userDB.financeAccts
(
	userID INTEGER NOT NULL,
    acctNo INTEGER,
    acctName VARCHAR (100),
    acctOrg VARCHAR (100),
    acctBalance DECIMAL(19,4),
    
    PRIMARY KEY (userID, acctNo)
);

/**
  *@desc User profile and preferences, including reminders
  *,preferred currency and actual name

CREATE TABLE userDB.userPrefs
(
	userID INTEGER NOT NULL,
    realName VARCHAR(100) NOT NULL,
    isRemindInstant BOOLEAN,
    remindDaily TIME,
    remindWeeklyOnDay TINYINT,
    remindMonthlyOnDay TINYINT,
    defaultCurrency CHAR(3),
    
    PRIMARY KEY (userID)
);

/**
  *@desc Bill permissions linked to user names.
  *@params permissionType: 1)Owner 2)Editor 3)View
*
CREATE TABLE billDB.sharing
(
	billID INTEGER NOT NULL,
    userID INTEGER NOT NULL,
    permissionType TINYINT NOT NULL,
    
    PRIMARY KEY (billID,userID)
);

/**
  *@desc Holds commonly used tags for auto-completion
*
CREATE TABLE billDB.commonTags
(
	tagID INTEGER AUTO_INCREMENT NOT NULL,
    tagName VARCHAR(50),
    
    PRIMARY KEY (tagID)
);

/**
  *@desc Holds all flags associated with a certain bill, including
  *thresholds and triggers for reminders to be sent.
*
CREATE TABLE billDB.billFlags
(
	billID INTEGER,
    flagName VARCHAR (50) NOT NULL,
    isFlagged BOOLEAN NOT NULL,
    isReminderActive BOOLEAN NOT NULL,
    isAmtTriggered BOOLEAN,
    isDateTriggered BOOLEAN,
    
    triggerDateTime DATETIME,
    
    amtLabel VARCHAR (100),
    thresholdAmt DECIMAL(19,4),
    comparator VARCHAR (2),
    
    PRIMARY KEY (billID,flagName)
);
*/