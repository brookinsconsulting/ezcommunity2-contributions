CREATE TABLE eZForm_Form (
  ID int(11) NOT NULL,
  Name varchar(255) default NULL,
  Receiver varchar(255) default NULL,
  CC varchar(255) default NULL,
  Sender varchar(255) default NULL,
  SendAsUser varchar(1) default NULL,
  CompletedPage varchar(255) default NULL,
  InstructionPage varchar(255) default NULL,
  Counter int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZForm_FormContition (
  ID int(11) NOT NULL,
  ElementID int(11) NOT NULL,
  PageID int(11) NOT NULL,
  Max int(11) default '0',
  Min int(11) default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZForm_FormPage (
  ID int(11) NOT NULL,
  Name varchar(255) default NULL,
  PageNumber int(11) default '0',
  FormID int(11) default '0',
  Placement int(11) default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZForm_FormElement (
  ID int(11) NOT NULL,
  Name varchar(255) default NULL,
  Required int(1) default '0',
  ElementTypeID int(11) default NULL,
  Size int(11) default '0',	
  Break int(11) default '0',		
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZForm_PageElementDict (
  ID int(11) NOT NULL,
  Name varchar(255) default NULL,
  PageID int(11) default NULL,
  ElementID int(11) default NULL,
  Placement int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZForm_FormElementType (
  ID int(11) NOT NULL,
  Name varchar(255) default NULL,
  Description text,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZForm_FormElementFixedValues (
  ID int(11) NOT NULL default '0',
  Value varchar(80) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;
 
CREATE TABLE eZForm_FormElementFixedValueLink (
  ID int(11) NOT NULL default '0',
  ElementID int(11) default '0',
  FixedValueID int(11) default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZForm_FormTable (
  ID int(11) NOT NULL default '0',
  ElementID int(11) NOT NULL,
  Cols int(11) NOT NULL,
  Rows int(11) NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZForm_FormTableElementDict (
  ID int(11) NOT NULL default '0',
  Name varchar(255),
  TableID int(11),
  ElementID int(11),
  Placement int(11),
  PRIMARY KEY (ID)
) TYPE=MyISAM;    

INSERT INTO eZForm_FormElementType VALUES (1,'text_field_item','HTML text field (input type="text")');
INSERT INTO eZForm_FormElementType VALUES (2,'text_area_item','HTML text area (textarea)');
INSERT INTO eZForm_FormElementType VALUES (3,'dropdown_item','HTML Select');
INSERT INTO eZForm_FormElementType VALUES (4,'multiple_select_item','HTML Multiple Select');
INSERT INTO eZForm_FormElementType VALUES (5,'checkbox_item','HTML CheckBox');
INSERT INTO eZForm_FormElementType VALUES (6,'radiobox_item','HTML RadioBox');
INSERT INTO eZForm_FormElementType VALUES (7,'table_item','Table of elements');
