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
  Status int(1) default '0' NOT NULL,
  Size int(11) default '0',
  UDate int(15) default '0',
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
  ServerPort int(5) defaul '0',
  DeleteFromServer int(1) default '1',
  ServerType int(2), 
  IsActive int(1) default '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZMail_Folder'
#
DROP TABLE IF EXISTS eZMail_Folder;
CREATE TABLE eZMail_Folder (
  ID int(11) default '0' NOT NULL auto_increment,
  UserID int(11) default '0',
  ParentID int(11) default '0',
  Name varchar(200) default NULL,
  FolderType int(2), 
  PRIMARY KEY (ID)
);


#
# Table structure for table 'eZMail_MailFolderLink'
#

DROP TABLE IF EXISTS eZMail_MailFolderLink;
CREATE TABLE eZMail_MailFolderLink (
  MailID int(11) NOT NULL default '0',
  FolderID int(11) NOT NULL default '0',
  PRIMARY KEY (MailID,FolderID)
) TYPE=MyISAM;


#
# Table structure for table 'eZMail_MailAttachmentLink'
#

DROP TABLE IF EXISTS eZMail_MailAttachmentLink;
CREATE TABLE eZMail_MailAttachmentLink (
  MailID int(11) NOT NULL default '0',
  FileID int(11) NOT NULL default '0',
  PRIMARY KEY (MailID,FileID)
) TYPE=MyISAM;

#
# Table structure for table 'eZMail_MailImageLink'
#

DROP TABLE IF EXISTS eZMail_MailImageLink;
CREATE TABLE eZMail_MailImageLink (
  MailID int(11) NOT NULL default '0',
  ImageID int(11) NOT NULL default '0',
  PRIMARY KEY (MailID,ImageID)
) TYPE=MyISAM;
