#
# Table structure for table 'eZBulkMail_Mail'
#
DROP TABLE IF EXISTS eZBulkMail_Mail;
CREATE TABLE eZBulkMail_Mail (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11) default '0', 
  FromField varchar(100),
  FromName varchar(100),
  ReplyTo varchar(100),
  Subject varchar(255),
  BodyText text,
  SentDate timestamp(14) NOT NULL,
  IsDraft int(1) NOT NULL default '0',
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZBulkMail_Category'
#
DROP TABLE IF EXISTS eZBulkMail_Category;
CREATE TABLE eZBulkMail_Category (
  ID int(11) default '0' NOT NULL auto_increment,
  Name varchar(200) default NULL,
  Description text,
  PRIMARY KEY (ID)
);


#
# Table structure for table 'eZBulkMail_MailCategoryLink'
#

DROP TABLE IF EXISTS eZBulkMail_MailCategoryLink;
CREATE TABLE eZBulkMail_MailCategoryLink (
  MailID int(11) NOT NULL default '0',
  CategoryID int(11) NOT NULL default '0',
  PRIMARY KEY (MailID,CategoryID)
) TYPE=MyISAM;

#
# Table structure for table 'eZBulkMail_MailTemplateLink'
#

DROP TABLE IF EXISTS eZBulkMail_MailTemplateLink;
CREATE TABLE eZBulkMail_MailTemplateLink (
  MailID int(11) NOT NULL default '0',
  TemplateID int(11) NOT NULL default '0',
  PRIMARY KEY (MailID)
) TYPE=MyISAM;


#
# Table structure for table 'eZBulkMail_Template'
#

DROP TABLE IF EXISTS eZBulkMail_Template;
CREATE TABLE eZBulkMail_Template (
  ID int(11) default '0' NOT NULL auto_increment,
  Name varchar(200) default NULL,
  Description text,
  Header text,
  Footer text,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZBulkMail_SubscriptionLink'
#

DROP TABLE IF EXISTS eZBulkMail_SubscriptionLink;
CREATE TABLE eZBulkMail_SubscriptionLink (
  CategoryID int(11) NOT NULL default '0',
  AddressID int(11) NOT NULL default '0',
  PRIMARY KEY (AddressID,CategoryID)
) TYPE=MyISAM;

#
# Table structure for table 'eZBulkMail_SubscriptionAddress'
#

DROP TABLE IF EXISTS eZBulkMail_SubscriptionAddress;
CREATE TABLE eZBulkMail_SubscriptionAddress (
  ID int(11) default '0' NOT NULL auto_increment,
  EMail varchar(255),
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZBulkMail_SentLog'
#

DROP TABLE IF EXISTS eZBulkMail_SentLog;
CREATE TABLE eZBulkMail_SentLog (
  ID int(11) default '0' NOT NULL auto_increment,
  MailID int(11) NOT NULL default '0',
  AddressID int(11) NOT NULL default '0',
  SentDate timestamp(14) NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

