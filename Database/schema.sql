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
    FOREIGN KEY (userName) REFERENCES Users(userName)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE table userDB.userProfs
(
	userName varchar(100) NOT NULL,
    realName varchar(100) NOT NULL,
    
    PRIMARY KEY (userName),
    FOREIGN KEY (userName) REFERENCES Users(userName)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE table userDB.emails
(
	userName varchar(100) NOT NULL,
    userEmail varchar(255) NOT NULL,
    
    PRIMARY KEY (userName, userEmail),
    FOREIGN KEY (userName) REFERENCES Users(userName)
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
    FOREIGN KEY (userName) REFERENCES Users(userName)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE table userDB.friends
(
	userName varchar(100) NOT NULL,
    friendName varchar(100) NOT NULL,
    
    PRIMARY KEY (userName, friendName),
    FOREIGN KEY (userName) REFERENCES Users(userName)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    
    FOREIGN KEY (friendName) REFERENCES Users(userName)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);