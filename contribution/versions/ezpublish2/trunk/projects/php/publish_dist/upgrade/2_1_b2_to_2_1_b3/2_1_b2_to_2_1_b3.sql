alter table eZUser_Group add IsRoot int(1) default '0';
ALTER TABLE eZTrade_Link ADD ModuleType int(11) NOT NULL;
CREATE TABLE eZModule_LinkModuleType
       (ID int(11) NOT NULL AUTO_INCREMENT,
        Module varchar(40) NOT NULL,
        Type varchar(40) NOT NULL,
        PRIMARY KEY(ID,Module,Type));

insert into eZUser_Permission SET ModuleID='1', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='2', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='3', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='4', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='5', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='6', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='7', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='8', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='9', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='10', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='11', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='12', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='13', Name='ModuleEdit';
insert into eZUser_Module set Name='eZStats';
insert into eZUser_Permission SET ModuleID='14', Name='ModuleEdit';
insert into eZUser_Module set Name='eZSysInfo';
insert into eZUser_Permission SET ModuleID='15', Name='ModuleEdit';
