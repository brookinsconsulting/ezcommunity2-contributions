#
# Table structure for table 'eZForm_Form'
#
DROP TABLE IF EXISTS eZForm_Form;
CREATE TABLE eZForm_Form (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(255),
  Receiver char(255),
  CC char(255),
  Sender char(255),
  SendAsUser char(1),
  CompletedPage char(255),
  InstructionPage char(255),
  Counter int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZForm_Form'
#

#
# Table structure for table 'eZForm_FormElement'
#
DROP TABLE IF EXISTS eZForm_FormElement;
CREATE TABLE eZForm_FormElement (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(255),
  Required int(1) DEFAULT '0',
  ElementTypeID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZForm_FormElement'
#

#
# Table structure for table 'eZForm_FormElementDict'
#
DROP TABLE IF EXISTS eZForm_FormElementDict;
CREATE TABLE eZForm_FormElementDict (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(255),
  FormID int(11),
  ElementID int(11),
  Placement int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZForm_FormElementDict'
#

#
# Table structure for table 'eZForm_FormElementType'
#
DROP TABLE IF EXISTS eZForm_FormElementType;
CREATE TABLE eZForm_FormElementType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(255),
  Description text,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZForm_FormElementType'
#

INSERT INTO eZForm_FormElementType VALUES (1,'text_field_item','HTML text field (input type="text")');
INSERT INTO eZForm_FormElementType VALUES (2,'text_area_item','HTML text area (textarea)');
