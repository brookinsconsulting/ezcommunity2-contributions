CREATE TABLE eZForm_Form (
  ID int NOT NULL,
  Name varchar(255) default NULL,
  Receiver varchar(255) default NULL,
  CC varchar(255) default NULL,
  Sender varchar(255) default NULL,
  SendAsUser varchar(1) default NULL,
  CompletedPage varchar(255) default NULL,
  InstructionPage varchar(255) default NULL,
  Counter int default NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZForm_FormElement (
  ID int NOT NULL,
  Name varchar(255) default NULL,
  Required int default '0',
  ElementTypeID int default NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZForm_FormElementDict (
  ID int NOT NULL,
  Name varchar(255) default NULL,
  FormID int default NULL,
  ElementID int default NULL,
  Placement int default NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZForm_FormElementType (
  ID int NOT NULL,
  Name varchar(255) default NULL,
  Description text,
  PRIMARY KEY (ID)
);

INSERT INTO eZForm_FormElementType VALUES (1,'text_field_item','HTML text field (input type="text")');
INSERT INTO eZForm_FormElementType VALUES (2,'text_area_item','HTML text area (textarea)');
