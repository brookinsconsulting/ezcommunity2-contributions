-- Edit the ID's (REQUIRED)

-- Edit the id provided to ensure the sql does not conflict
-- with your existing eZ publish installation's ids.

-- #1 : Select the nextid from eZUser_Module,
-- ensure that you use the next unused id that is greater than the the largest id in use.

-- sql select to help you find this informaiton.
-- select ID, Name from eZUser_Module;
-- select MAX(ID) from eZUser_Module;

INSERT INTO eZUser_Module VALUES ('42', 'eZGroupEventCalendar');

-- #2 : Select the nextid from eZUser_Module,
-- ensure that you use the next unused id that is greater than the the largest id in use.
-- also make sure that the eZUser_Permission module ID matches the eZUser_Module ID

-- sql select to help you find this informaiton.
-- select ID, ModuleID, Name from eZUser_Permission;
-- select MAX(ID) from eZUser_Permission;

-- Defaults eZ publish Installations can use these values.
-- INSERT INTO eZUser_Permission VALUES ('55', '24', 'ModuleEdit');
-- INSERT INTO eZUser_Permission VALUES ('56', '24', 'ModuleAnswer');

-- Modified or Customized eZ Publish installations may need larger values instead:
INSERT INTO eZUser_Permission VALUES ('250', '42', 'Read');
INSERT INTO eZUser_Permission VALUES ('251', '42', 'WriteToRoot');

# Wont execute properly (Depricated | Reference)
#
#INSERT INTO eZUser_Permission (ModuleID, Name) SELECT ID AS ModuleID, 'Read' AS Name FROM eZUser_Module WHERE Name='eZGroupEventCalendar';
#INSERT INTO eZUser_Permission (ModuleID, Name) SELECT ID AS ModuleID, 'WriteToRoot' AS Name FROM eZUser_Module WHERE Name='eZGroupEventCalendar';
#
#

-- #3 : Most people don't make much use of eZBulkMail_Template but if you do you may need to alter the id
-- select ID, Name, Header from eZBulkMail_Template;
-- select MAX(ID) from eZBulkMail_Template;

INSERT INTO eZBulkMail_Template ( id, Name, Header ) VALUES ( 8, 'Responses from the vote %VOTE%', '%REPORT%' );

