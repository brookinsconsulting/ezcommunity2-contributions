#
# Table structure for table 'eZTrade_Cart'
#
DROP TABLE IF EXISTS eZTrade_Cart;
CREATE TABLE eZTrade_Cart (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  SessionID int(11),
  Type enum('Cart','WishList'),
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZTrade_CartItem'
#
DROP TABLE IF EXISTS eZTrade_CartItem;
CREATE TABLE eZTrade_CartItem (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ProductID int(11),
  Count int(11),
  CartID int(11),
  PRIMARY KEY (ID)
);

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
# Table structure for table 'eZTrade_Category'
#
DROP TABLE IF EXISTS eZTrade_Category;
CREATE TABLE eZTrade_Category (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Parent int(11),
  Description text,
  Name varchar(100),
  PRIMARY KEY (ID)
);

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
# Table structure for table 'eZTrade_Order'
#
DROP TABLE IF EXISTS eZTrade_Order;
CREATE TABLE eZTrade_Order (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11) DEFAULT '0' NOT NULL,
  AddressID int(11),
  ShippingCharge float(10,2),
  PRIMARY KEY (ID)
);

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
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZTrade_ProductCategoryLink'
#
DROP TABLE IF EXISTS eZTrade_ProductCategoryLink;
CREATE TABLE eZTrade_ProductCategoryLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  CategoryID int(11),
  ProductID int(11),
  PRIMARY KEY (ID)
);

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
# Table structure for table 'eZTrade_ProductImageLink'
#
DROP TABLE IF EXISTS eZTrade_ProductImageLink;
CREATE TABLE eZTrade_ProductImageLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ProductID int(11),
  ImageID int(11),
  PRIMARY KEY (ID)
);

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
