ALTER TABLE eZTrade_PreOrder CHANGE Verified Pnutr int NOT NULL default '0';
ALTER TABLE eZTrade_PreOrder ADD Utref varchar(255) default NULL;
ALTER TABLE eZTrade_PreOrder ADD Payco int NOT NULL default '0';
ALTER TABLE eZTrade_PreOrder ADD Totam int NOT NULL default '0';
ALTER TABLE eZTrade_PreOrder ADD Curry char(3) default NULL;
ALTER TABLE eZTrade_PreOrder ADD Ttype int NOT NULL default '0';
ALTER TABLE eZTrade_PreOrder ADD Rtype int NOT NULL default '0';
ALTER TABLE eZTrade_PreOrder ADD Status int NOT NULL default '0';
ALTER TABLE eZTrade_PreOrder ADD Ertyp varchar(255) default NULL;
ALTER TABLE eZTrade_PreOrder ADD Ermsg varchar(255) default NULL;
ALTER TABLE eZTrade_PreOrder ADD Edate int NOT NULL default '0';

