#
# Table structure for table 'eZSiteManager_Section'
#
 
CREATE TABLE eZSiteManager_Section (
  ID int(11) NOT NULL ,
  Name varchar(200) default NULL,
  Description text,
  SiteDesign varchar(30) default NULL,
  Created int(11) default NULL,
  TemplateStyle varchar(255) default NULL,
  Language varchar(5) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;


INSERT INTO eZSiteManager_Section   ( ID,  Name, Created, Description,  SiteDesign, TemplateStyle, Language) VALUES ( 1, 'Standard Section', 1, NULL, 'standard', NULL, NULL);



