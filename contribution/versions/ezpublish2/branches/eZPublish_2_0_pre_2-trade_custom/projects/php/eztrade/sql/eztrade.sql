#
# Table structure for table 'eZTrade_Attribute'
#
DROP TABLE IF EXISTS eZTrade_Attribute;
CREATE TABLE eZTrade_Attribute (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  TypeID int(11),
  Name char(150),
  Created timestamp(14),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_Attribute'
#

INSERT INTO eZTrade_Attribute VALUES (1,1,'Size',20010126130441);
INSERT INTO eZTrade_Attribute VALUES (2,1,'Color',20010126130449);
INSERT INTO eZTrade_Attribute VALUES (3,1,'Age',20010126130455);
INSERT INTO eZTrade_Attribute VALUES (4,1,'Gender',20010126130459);

#
# Table structure for table 'eZTrade_AttributeValue'
#
DROP TABLE IF EXISTS eZTrade_AttributeValue;
CREATE TABLE eZTrade_AttributeValue (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ProductID int(11),
  AttributeID int(11),
  Value char(200),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_AttributeValue'
#

INSERT INTO eZTrade_AttributeValue VALUES (1,2,1,'Big');
INSERT INTO eZTrade_AttributeValue VALUES (2,2,2,'Red');
INSERT INTO eZTrade_AttributeValue VALUES (3,2,3,'12 yrs');
INSERT INTO eZTrade_AttributeValue VALUES (4,2,4,'Female');

#
# Table structure for table 'eZTrade_Cart'
#
DROP TABLE IF EXISTS eZTrade_Cart;
CREATE TABLE eZTrade_Cart (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  SessionID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_Cart'
#

INSERT INTO eZTrade_Cart VALUES (3,4);
INSERT INTO eZTrade_Cart VALUES (2,1);
INSERT INTO eZTrade_Cart VALUES (4,5);
INSERT INTO eZTrade_Cart VALUES (5,11);
INSERT INTO eZTrade_Cart VALUES (6,10);

#
# Table structure for table 'eZTrade_CartItem'
#
DROP TABLE IF EXISTS eZTrade_CartItem;
CREATE TABLE eZTrade_CartItem (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ProductID int(11),
  Count int(11),
  CartID int(11),
  WishListItemID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_CartItem'
#




#
# Table structure for table 'eZTrade_CartOptionValue'
#
DROP TABLE IF EXISTS eZTrade_CartOptionValue;
CREATE TABLE eZTrade_CartOptionValue (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  CartItemID int(11),
  OptionID int(11),
  OptionValueID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_CartOptionValue'
#




#
# Table structure for table 'eZTrade_Category'
#
DROP TABLE IF EXISTS eZTrade_Category;
CREATE TABLE eZTrade_Category (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Parent int(11),
  Description text,
  Name varchar(100),
  ImageID int(11),
  SortMode int(11) DEFAULT '1' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_Category'
#

INSERT INTO eZTrade_Category VALUES (1,0,'','Products',NULL,1);

#
# Table structure for table 'eZTrade_CategoryOptionLink'
#
DROP TABLE IF EXISTS eZTrade_CategoryOptionLink;
CREATE TABLE eZTrade_CategoryOptionLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  CategoryID int(11),
  OptionID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_CategoryOptionLink'
#




#
# Table structure for table 'eZTrade_Option'
#
DROP TABLE IF EXISTS eZTrade_Option;
CREATE TABLE eZTrade_Option (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Description text,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_Option'
#




#
# Table structure for table 'eZTrade_OptionValue'
#
DROP TABLE IF EXISTS eZTrade_OptionValue;
CREATE TABLE eZTrade_OptionValue (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(100),
  OptionID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_OptionValue'
#




#
# Table structure for table 'eZTrade_Order'
#
DROP TABLE IF EXISTS eZTrade_Order;
CREATE TABLE eZTrade_Order (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11) DEFAULT '0' NOT NULL,
  ShippingCharge float(10,2),
  PaymentMethod text,
  ShippingAddressID int(11),
  BillingAddressID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_Order'
#

INSERT INTO eZTrade_Order VALUES (1,27,50.00,'1',1,1);

#
# Table structure for table 'eZTrade_OrderItem'
#
DROP TABLE IF EXISTS eZTrade_OrderItem;
CREATE TABLE eZTrade_OrderItem (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  OrderID int(11) DEFAULT '0' NOT NULL,
  Count int(11),
  Price float(10,2),
  ProductID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_OrderItem'
#

INSERT INTO eZTrade_OrderItem VALUES (1,1,1,142.00,1);

#
# Table structure for table 'eZTrade_OrderOptionValue'
#
DROP TABLE IF EXISTS eZTrade_OrderOptionValue;
CREATE TABLE eZTrade_OrderOptionValue (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  OrderItemID int(11),
  OptionName char(25),
  ValueName char(25),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_OrderOptionValue'
#


#
# Table structure for table 'eZTrade_OrderStatus'
#
DROP TABLE IF EXISTS eZTrade_OrderStatus;
CREATE TABLE eZTrade_OrderStatus (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  StatusID int(11) DEFAULT '0' NOT NULL,
  Altered timestamp(14),
  AdminID int(11),
  OrderID int(11) DEFAULT '0' NOT NULL,
  Comment text,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_OrderStatus'
#

INSERT INTO eZTrade_OrderStatus VALUES (1,1,20010126102943,0,1,'');

#
# Table structure for table 'eZTrade_OrderStatusType'
#
DROP TABLE IF EXISTS eZTrade_OrderStatusType;
CREATE TABLE eZTrade_OrderStatusType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(25) DEFAULT '' NOT NULL,
  PRIMARY KEY (ID),
  UNIQUE Name (Name)
);

#
# Dumping data for table 'eZTrade_OrderStatusType'
#

INSERT INTO eZTrade_OrderStatusType VALUES (1,'intl-initial');
INSERT INTO eZTrade_OrderStatusType VALUES (2,'intl-sendt');
INSERT INTO eZTrade_OrderStatusType VALUES (3,'intl-payed');
INSERT INTO eZTrade_OrderStatusType VALUES (4,'intl-undefined');

#
# Table structure for table 'eZTrade_Product'
#
DROP TABLE IF EXISTS eZTrade_Product;
CREATE TABLE eZTrade_Product (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Brief text,
  Description text,
  Keywords varchar(100),
  Price float(10,2),
  ShowPrice enum('true','false'),
  ShowProduct enum('true','false'),
  Discontinued enum('true','false'),
  InheritOptions enum('true','false'),
  ProductNumber varchar(100),
  ExternalLink varchar(200),
  IsHotDeal enum('true','false') DEFAULT 'false',
  Published timestamp(14),
  Altered timestamp(14),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_Product'
#

INSERT INTO eZTrade_Product VALUES (1,'Cat','Test product','Please buy this product','',142.00,'true','true','false',NULL,'','','true',20010126102820,00000000000000);
INSERT INTO eZTrade_Product VALUES (2,'Flower','This is a flower','Description','',42.00,'true','true','false',NULL,'','www.ez.no','true',20010126130741,00000000000000);

#
# Table structure for table 'eZTrade_ProductCategoryDefinition'
#
DROP TABLE IF EXISTS eZTrade_ProductCategoryDefinition;
CREATE TABLE eZTrade_ProductCategoryDefinition (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ProductID int(11) DEFAULT '0' NOT NULL,
  CategoryID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_ProductCategoryDefinition'
#

INSERT INTO eZTrade_ProductCategoryDefinition VALUES (1,1,1);
INSERT INTO eZTrade_ProductCategoryDefinition VALUES (2,2,1);

#
# Table structure for table 'eZTrade_ProductCategoryLink'
#
DROP TABLE IF EXISTS eZTrade_ProductCategoryLink;
CREATE TABLE eZTrade_ProductCategoryLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  CategoryID int(11),
  ProductID int(11),
  Placement int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_ProductCategoryLink'
#

INSERT INTO eZTrade_ProductCategoryLink VALUES (1,1,1,0);
INSERT INTO eZTrade_ProductCategoryLink VALUES (2,1,2,0);

#
# Table structure for table 'eZTrade_ProductImageDefinition'
#
DROP TABLE IF EXISTS eZTrade_ProductImageDefinition;
CREATE TABLE eZTrade_ProductImageDefinition (
  ProductID int(11) DEFAULT '0' NOT NULL,
  ThumbnailImageID int(11),
  MainImageID int(11),
  PRIMARY KEY (ProductID)
);

#
# Dumping data for table 'eZTrade_ProductImageDefinition'
#

INSERT INTO eZTrade_ProductImageDefinition VALUES (1,3,3);
INSERT INTO eZTrade_ProductImageDefinition VALUES (2,4,4);

#
# Table structure for table 'eZTrade_ProductImageLink'
#
DROP TABLE IF EXISTS eZTrade_ProductImageLink;
CREATE TABLE eZTrade_ProductImageLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ProductID int(11),
  ImageID int(11),
  Created timestamp(14),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_ProductImageLink'
#

INSERT INTO eZTrade_ProductImageLink VALUES (1,1,3,20010126102759);
INSERT INTO eZTrade_ProductImageLink VALUES (2,2,4,20010126130705);

#
# Table structure for table 'eZTrade_ProductOptionLink'
#
DROP TABLE IF EXISTS eZTrade_ProductOptionLink;
CREATE TABLE eZTrade_ProductOptionLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ProductID int(11),
  OptionID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_ProductOptionLink'
#


#
# Table structure for table 'eZTrade_ProductTypeLink'
#
DROP TABLE IF EXISTS eZTrade_ProductTypeLink;
CREATE TABLE eZTrade_ProductTypeLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ProductID int(11),
  TypeID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_ProductTypeLink'
#

INSERT INTO eZTrade_ProductTypeLink VALUES (1,2,1);

#
# Table structure for table 'eZTrade_Type'
#
DROP TABLE IF EXISTS eZTrade_Type;
CREATE TABLE eZTrade_Type (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(150),
  Description text,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_Type'
#

INSERT INTO eZTrade_Type VALUES (1,'Flower','');

#
# Table structure for table 'eZTrade_WishList'
#
DROP TABLE IF EXISTS eZTrade_WishList;
CREATE TABLE eZTrade_WishList (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11),
  IsPublic int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_WishList'
#

INSERT INTO eZTrade_WishList VALUES (1,27,0);

#
# Table structure for table 'eZTrade_WishListItem'
#
DROP TABLE IF EXISTS eZTrade_WishListItem;
CREATE TABLE eZTrade_WishListItem (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ProductID int(11),
  Count int(11),
  WishListID int(11),
  IsBought int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_WishListItem'
#

INSERT INTO eZTrade_WishListItem VALUES (1,1,1,1,0);

#
# Table structure for table 'eZTrade_WishListOptionValue'
#
DROP TABLE IF EXISTS eZTrade_WishListOptionValue;
CREATE TABLE eZTrade_WishListOptionValue (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  WishListItemID int(11),
  OptionID int(11),
  OptionValueID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_WishListOptionValue'
#
