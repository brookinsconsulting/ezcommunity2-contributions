ALTER TABLE eZTrade_Link ADD ModuleType int(11) NOT NULL;
CREATE TABLE eZModule_LinkModuleType
       (ID int(11) NOT NULL AUTO_INCREMENT,
        Module varchar(40) NOT NULL,
        Type varchar(40) NOT NULL,
        PRIMARY KEY(ID,Module,Type));

