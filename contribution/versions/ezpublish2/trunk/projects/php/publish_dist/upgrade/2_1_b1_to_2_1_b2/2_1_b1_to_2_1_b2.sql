create table eZURLTranslator_URL( ID int primary key auto_increment, Source char(200), Dest char(200) );
alter table eZURLTranslator_URL add Created timestamp;

alter table eZBulkMail_SubscriptionAddress add Password varchar(50) default '' not null;
#
# Table structure for table 'eZBulkMail_Forgot'
#
DROP TABLE IF EXISTS eZBulkMail_Forgot;
CREATE TABLE eZBulkMail_Forgot (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Mail varchar(255) DEFAULT '' NOT NULL,
  Password varchar(50) DEFAULT '' NOT NULL,
  Hash char(33),
  Time timestamp(14),
  PRIMARY KEY (ID)
);

