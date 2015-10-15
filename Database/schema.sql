/**This schema builds the database structure for Bill.eGoat, 
  *and contains user, template and bill data in a normalised form.
*/

/**
  *@desc Initialisation, this section recreates the DB
*/
DROP DATABASE IF EXISTS billDB;
DROP DATABASE IF EXISTS templateDB;
DROP DATABASE IF EXISTS userDB;

CREATE DATABASE userDB;

USE userDB;

/**
  *@desc Username/password table, minimum requirements
  *for authentication/identification of an account
*/
CREATE TABLE userDB.users
(
	userID INTEGER AUTO_INCREMENT NOT NULL,
    passwd VARCHAR(41) NOT NULL,
	userEmail VARCHAR(255) NOT NULL,
    isPartnerOrg BOOLEAN,
    failedLoginNo TINYINT,
    isActivated BOOLEAN NOT NULL,
    activationCode VARCHAR(100),
    lastLoggedIn TIMESTAMP,
    
    PRIMARY KEY (userID)
);

/**
  *@desc User profile and preferences, including reminders
  *,preferred currency and actual name
*/
CREATE TABLE userDB.userPrefs
(
	userID INTEGER NOT NULL,
    realName VARCHAR(100) NOT NULL,
    isRemindInstant BOOLEAN,
    remindDaily TIME,
    remindWeeklyOnDay TINYINT,
    remindMonthlyOnDay TINYINT,
    defaultCurrency CHAR(3),
    
    PRIMARY KEY (userID),
    FOREIGN KEY (userID) REFERENCES userDB.Users(userID)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

/**
  *@desc Contains all user emails, including reminder
  *and recovery emails
*/
CREATE TABLE userDB.emails
(
	userID INTEGER NOT NULL,
    userEmail VARCHAR(255) NOT NULL,
    isReminderEmail BOOLEAN NOT NULL,
    isRecoveryEmail BOOLEAN NOT NULL,
    
    PRIMARY KEY (userID, userEmail),
    FOREIGN KEY (userID) REFERENCES userDB.Users(userID)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

/**
  *@desc Contains financial account information, users can
  *indicate which account paid for which expense.
*/
CREATE TABLE userDB.financeAccts
(
	userID INTEGER NOT NULL,
    acctNo INTEGER,
    acctName VARCHAR (100),
    acctOrg VARCHAR (100),
    acctBalance DECIMAL(19,4),
    
    PRIMARY KEY (userID, acctNo),
    FOREIGN KEY (userID) REFERENCES userDB.Users(userID)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

/**
  *@desc Friends list for auto-complete when trying to share
  *bills
*/
CREATE TABLE userDB.friends
(
	userID INTEGER NOT NULL,
    friendID INTEGER NOT NULL,
    
    PRIMARY KEY (userID, friendID),
    FOREIGN KEY (userID) REFERENCES userDB.Users(userID)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    
    FOREIGN KEY (friendID) REFERENCES userDB.Users(userID)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);


CREATE DATABASE templateDB;

USE templateDB;

/**
  *@desc Contains minimum data to identify a template
*/
CREATE TABLE templateDB.templates
(
	templateID INTEGER AUTO_INCREMENT NOT NULL,
    billOrg VARCHAR (100),
    creatorID INTEGER,
    fileImgPath VARCHAR (255),
    dateCreated DATETIME NOT NULL,
    dateModified TIMESTAMP,
    
    PRIMARY KEY (templateID),
    FOREIGN KEY (creatorID) REFERENCES userDB.users(userID)
);


/**
  *@desc Holds data for fields in template maps
*/
CREATE TABLE templateDB.dataFields
(
	templateID INTEGER,
    dataFieldLabel VARCHAR (100),
    coordinateLabelX VARCHAR (50),
    coordinateLabelY VARCHAR (50),
    
    PRIMARY KEY (templateID,dataFieldLabel),
    FOREIGN KEY (templateID) REFERENCES templateDB.templates(templateID)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE DATABASE billDB;

USE billDB;

/**
  *@desc Contains minimum data to identify a particular bill
*/
CREATE TABLE billDB.bills
(
	billID INTEGER AUTO_INCREMENT NOT NULL,
    submittedTimeStamp TIMESTAMP NOT NULL,
    billFilePath VARCHAR (255) NOT NULL UNIQUE,
    revisionNo INTEGER NOT NULL,
    
    PRIMARY KEY (billID)
);

/**
  *@desc Bill permissions linked to user names.
  *@params permissionType: 1)Owner 2)Editor 3)View
*/
CREATE TABLE billDB.sharing
(
	billID INTEGER NOT NULL,
    userID INTEGER NOT NULL,
    permissionType TINYINT NOT NULL,
    
    PRIMARY KEY (billID,userID),
    FOREIGN KEY (userID) REFERENCES userDB.Users(userID)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    
    FOREIGN KEY (billID) REFERENCES billDB.bills(billID)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

/**
  *@desc Common bill details such as dates, and
  *whether it has been cleared, or if it is a copy (ignored during
  *analysis)
*/
CREATE TABLE billDB.billDetails
(
	billID INTEGER NOT NULL UNIQUE,
    templateID INTEGER,
    billSentDate DATE,
    billDueDate DATE,
    billIsComplete BOOLEAN NOT NULL,
	billIsVerified BOOLEAN NOT NULL,
    billIsCopy BOOLEAN NOT NULL,
	billCompleteDateTime DATETIME,
    billModifiedTimeStamp TIMESTAMP,
    
    PRIMARY KEY (billID),
    FOREIGN KEY (billID) REFERENCES billDB.bills(billID)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    
    FOREIGN KEY (templateID) REFERENCES templateDB.templates(templateID)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

/**
  *@desc Holds all possible fields pertaining to monetary
  *values within a bill.
*/
CREATE TABLE billDB.billAmts
(
	billID INTEGER NOT NULL,
    amtLabel VARCHAR(100) NOT NULL,
    amt DECIMAL(19,4),
    currency CHAR(3),
    
    PRIMARY KEY (billID,amtLabel),
    
    FOREIGN KEY (billID) REFERENCES billDB.bills(billID)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

/**
  *@desc Holds commonly used tags for auto-completion
*/
CREATE TABLE billDB.commonTags
(
	tagID INTEGER AUTO_INCREMENT NOT NULL,
    tagName VARCHAR(50),
    
    PRIMARY KEY (tagID)
);

/**
  *@desc Holds all tags associated with a certain bill
*/
CREATE TABLE billDB.billTags
(
	billID INTEGER,
    tagName VARCHAR(50),
    
    PRIMARY KEY (billID,tagName),
    
    FOREIGN KEY (billID) REFERENCES billDB.bills(billID)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

/**
  *@desc Holds all flags associated with a certain bill, including
  *thresholds and triggers for reminders to be sent.
*/
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
    
    PRIMARY KEY (billID,flagName),
    
    FOREIGN KEY (billID) REFERENCES billDB.bills(billID)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    
    FOREIGN KEY (billID, amtLabel) REFERENCES billDB.billAmts(billID, amtLabel)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);