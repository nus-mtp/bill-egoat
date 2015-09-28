DROP DATABASE IF EXISTS billDB;
DROP DATABASE IF EXISTS templateDB;
DROP DATABASE IF EXISTS userDB;

CREATE DATABASE userDB;

USE userDB;

CREATE table userDB.users
(
	userName varchar(100) NOT NULL,
    passwd varchar(41) NOT NULL,
    
    PRIMARY KEY (userName)
);

CREATE table userDB.userAccts
(
	userName varchar(100) NOT NULL,
    recoveryEmail varchar (255) UNIQUE NOT NULL,
    accType varchar (20) NOT NULL,
    failedLoginNo tinyint,
    isActivated boolean NOT NULL,
    activationCode varchar(100),
    
    PRIMARY KEY (userName),
    FOREIGN KEY (userName) REFERENCES userDB.users(userName)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE table userDB.userProfs
(
	userName varchar(100) NOT NULL,
    realName varchar(100) NOT NULL,
    
    PRIMARY KEY (userName),
    FOREIGN KEY (userName) REFERENCES userDB.users(userName)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE table userDB.emails
(
	userName varchar(100) NOT NULL,
    userEmail varchar(255) NOT NULL,
    
    PRIMARY KEY (userName, userEmail),
    FOREIGN KEY (userName) REFERENCES userDB.users(userName)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE table userDB.bankAccts
(
	userName varchar(100) NOT NULL,
    bankAcctID INTEGER AUTO_INCREMENT NOT NULL,
    bankAcctNo INTEGER,
    bankAcctName varchar (100),
    acctOrg varchar (100),
    acctBalance INTEGER,
    
    PRIMARY KEY (bankAcctID),
    FOREIGN KEY (userName) REFERENCES userDB.users(userName)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE table userDB.friends
(
	userName varchar(100) NOT NULL,
    friendName varchar(100) NOT NULL,
    
    PRIMARY KEY (userName, friendName),
    FOREIGN KEY (userName) REFERENCES userDB.users(userName)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    
    FOREIGN KEY (friendName) REFERENCES userDB.users(userName)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE DATABASE templateDB;

USE templateDB;

CREATE TABLE templateDB.templates
(
	templateID INTEGER AUTO_INCREMENT NOT NULL,
    billOrg varchar (100),
    creatorName varchar (100),
    fileImgPath varchar (255),
    fileCoordinatePath varchar (255),
    extraPayableMap varchar (100),
    
    PRIMARY KEY (templateID),
    FOREIGN KEY (creatorName) REFERENCES userDB.users(userName)
);

CREATE DATABASE billDB;

USE billDB;

CREATE table billDB.bills
(
	billID INTEGER AUTO_INCREMENT NOT NULL,
    submittedTimeStamp timestamp NOT NULL,
    billFilePath varchar (255) NOT NULL UNIQUE,
    userName varchar(100) NOT NULL,
    
    PRIMARY KEY (billID),
    FOREIGN KEY (userName) REFERENCES userDB.users(userName)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE table billDB.sharing
(
	billID INTEGER NOT NULL,
    userName varchar(100) NOT NULL,
    permissionType varchar(20) NOT NULL,
    
    PRIMARY KEY (billID,userName),
    FOREIGN KEY (userName) REFERENCES userDB.users(userName)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    
    FOREIGN KEY (billID) REFERENCES billDB.bills(billID)
    ON DELETE CASCADE
    ON UPDATE CASCADE
    
);

CREATE table billDB.billDetails
(
	billID INTEGER NOT NULL UNIQUE,
    templateID INTEGER,
    billSentDate date,
    billDueDate date,
    billIsComplete boolean NOT NULL,
    billIsCopy boolean NOT NULL,
	billCompleteDateTime datetime,
    billModifiedTimeStamp timestamp,
    amtPayable decimal(19,4),
    amtPaid decimal (19,4),
    amtBalance decimal (19,4),
    
    -- Optional fields for other payments, e.g breakdowns
    extraPayable1 decimal(19,4),
    extraPaid1 decimal (19,4),
    extraBalance decimal (19,4),
    -- End of optional fields
    
    tagString varchar (255),
    flags varchar (255),
    
    PRIMARY KEY (billID),
    FOREIGN KEY (billID) REFERENCES billDB.bills(billID)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    
    FOREIGN KEY (templateID) REFERENCES templateDB.templates(templateID)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

