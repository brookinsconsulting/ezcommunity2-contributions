#
# Table structure for table 'eZForm_Form'
#

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


#
# Table structure for table 'eZForm_FormElement'
#

CREATE TABLE eZForm_FormElement (
  ID int(11) NOT NULL,
  Name varchar(255) default NULL,
  Required int(1) default '0',
  ElementTypeID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;


#
# Table structure for table 'eZForm_FormElementDict'
#

CREATE TABLE eZForm_FormElementDict (
  ID int(11) NOT NULL,
  Name varchar(255) default NULL,
  FormID int(11) default NULL,
  ElementID int(11) default NULL,
  Placement int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;


#
# Table structure for table 'eZForm_FormElementType'
#

CREATE TABLE eZForm_FormElementType (
  ID int(11) NOT NULL,
  Name varchar(255) default NULL,
  Description text,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZForm_FormElementType'
#

INSERT INTO eZForm_FormElementType VALUES (1,'text_field_item','HTML text field (input type="text")');
INSERT INTO eZForm_FormElementType VALUES (2,'text_area_item','HTML text area (textarea)');
