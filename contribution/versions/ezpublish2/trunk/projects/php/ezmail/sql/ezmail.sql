#
# Table structure for table 'eZMail_Mail'
#
DROP TABLE IF EXISTS eZMail_Mail;
CREATE TABLE eZMail_Mail (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11) default '0', 
  ToField varchar(100),
  FromField varchar(100),
  FromName varchar(100),
  Cc varchar(255),
  Bcc varchar(255),
  MessageID varchar(200),
  Reference varchar(100),
  ReplyTo varchar(100),
  Subject varchar(255),
  BodyText text,
  IsRead int(1) default '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZMail_Account'
#
DROP TABLE IF EXISTS eZMail_Account;
CREATE TABLE eZMail_Account (
  ID int(11) default '0' NOT NULL auto_increment,
  UserID int(11) default '0',
  Name varchar(200) default NULL,
  LoginName varchar(100),
  Password varchar(50),
  Server varchar(150),
  DeleteFromServer int(1) default '1',
  ServerType int(2), 
  IsActive int(1) default '0' NOT NULL,
  PRIMARY KEY (ID)
);


