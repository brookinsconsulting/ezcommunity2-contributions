CREATE TABLE eZTrade_AlternativeCurrency (
  ID int(11) NOT NULL auto_increment,
  Name char(100) NOT NULL default '',
  PrefixSign int(11) NOT NULL default '0',
  Sign varchar(5) NOT NULL default '',
  Value float NOT NULL default '1',
  Created int(11) NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_Attribute'
#

CREATE TABLE eZTrade_Attribute (
  ID int(11) NOT NULL auto_increment,
  TypeID int(11) default NULL,
  Name varchar(150) default NULL,
  Created int(11) NOT NULL,
  Placement int(11) default '0',
  AttributeType int(11) default '1',
  Unit varchar(8) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_AttributeValue'
#

CREATE TABLE eZTrade_AttributeValue (
  ID int(11) NOT NULL auto_increment,
  ProductID int(11) default NULL,
  AttributeID int(11) default NULL,
  Value char(200) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_Cart'
#

CREATE TABLE eZTrade_Cart (
  ID int(11) NOT NULL auto_increment,
  SessionID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_CartItem'
#

CREATE TABLE eZTrade_CartItem (
  ID int(11) NOT NULL auto_increment,
  ProductID int(11) default NULL,
  Count int(11) default NULL,
  CartID int(11) default NULL,
  WishListItemID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_CartOptionValue'
#

CREATE TABLE eZTrade_CartOptionValue (
  ID int(11) NOT NULL auto_increment,
  CartItemID int(11) default NULL,
  OptionID int(11) default NULL,
  OptionValueID int(11) default NULL,
  RemoteID varchar(100) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_Category'
#

CREATE TABLE eZTrade_Category (
  ID int(11) NOT NULL auto_increment,
  Parent int(11) default NULL,
  Description text,
  Name varchar(100) default NULL,
  ImageID int(11) default NULL,
  SortMode int(11) NOT NULL default '1',
  RemoteID varchar(100) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_CategoryOptionLink'
#

CREATE TABLE eZTrade_CategoryOptionLink (
  ID int(11) NOT NULL auto_increment,
  CategoryID int(11) default NULL,
  OptionID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_GroupPriceLink'
#

CREATE TABLE eZTrade_GroupPriceLink (
  GroupID int(11) NOT NULL default '0',
  PriceID int(11) NOT NULL default '0',
  PRIMARY KEY (GroupID,PriceID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_Link'
#

CREATE TABLE eZTrade_Link (
  ID int(11) NOT NULL auto_increment,
  SectionID int(11) NOT NULL default '0',
  Name varchar(60) default NULL,
  URL text,
  Placement int(11) NOT NULL default '0',
  ModuleType int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_LinkSection'
#

CREATE TABLE eZTrade_LinkSection (
  ID int(11) NOT NULL auto_increment,
  Name varchar(30) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_Option'
#

CREATE TABLE eZTrade_Option (
  ID int(11) NOT NULL auto_increment,
  Name varchar(100) default NULL,
  Description text,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_OptionValue'
#

CREATE TABLE eZTrade_OptionValue (
  ID int(11) NOT NULL auto_increment,
  OptionID int(11) default NULL,
  Placement int(11) NOT NULL default '1',
  Price float(10,2) default NULL,
  RemoteID varchar(100) NOT NULL default '',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_OptionValueContent'
#

CREATE TABLE eZTrade_OptionValueContent (
  ID int(11) NOT NULL auto_increment,
  Value varchar(30) default NULL,
  ValueID int(11) NOT NULL default '0',
  Placement int(11) NOT NULL default '1',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_OptionValueHeader'
#

CREATE TABLE eZTrade_OptionValueHeader (
  ID int(11) NOT NULL auto_increment,
  Name varchar(30) default NULL,
  OptionID int(11) NOT NULL default '0',
  Placement int(11) NOT NULL default '1',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_Order'
#

CREATE TABLE eZTrade_Order (
  ID int(11) NOT NULL auto_increment,
  UserID int(11) NOT NULL default '0',
  ShippingCharge float(10,2) default NULL,
  PaymentMethod text,
  ShippingAddressID int(11) default NULL,
  BillingAddressID int(11) default NULL,
  IsExported int(11) NOT NULL default '0',
  Date int(11) default NULL,
  ShippingVAT float NOT NULL default '0',
  ShippingTypeID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_OrderItem'
#

CREATE TABLE eZTrade_OrderItem (
  ID int(11) NOT NULL auto_increment,
  OrderID int(11) NOT NULL default '0',
  Count int(11) default NULL,
  Price float(10,2) default NULL,
  ProductID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_OrderOptionValue'
#

CREATE TABLE eZTrade_OrderOptionValue (
  ID int(11) NOT NULL auto_increment,
  OrderItemID int(11) default NULL,
  OptionName varchar(25) default NULL,
  ValueName varchar(25) default NULL,
  RemoteID varchar(100) default '',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_OrderStatus'
#

CREATE TABLE eZTrade_OrderStatus (
  ID int(11) NOT NULL auto_increment,
  StatusID int(11) NOT NULL default '0',
  Altered int(11) NOT NULL,
  AdminID int(11) default NULL,
  OrderID int(11) NOT NULL default '0',
  Comment text,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_OrderStatusType'
#

CREATE TABLE eZTrade_OrderStatusType (
  ID int(11) NOT NULL auto_increment,
  Name varchar(25) NOT NULL default '',
  PRIMARY KEY (ID),
  UNIQUE KEY Name(Name)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_PreOrder'
#

CREATE TABLE eZTrade_PreOrder (
  ID int(11) NOT NULL auto_increment,
  Created int(11) NOT NULL,
  OrderID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_PriceGroup'
#

CREATE TABLE eZTrade_PriceGroup (
  ID int(11) NOT NULL auto_increment,
  Name varchar(50) default NULL,
  Description text,
  Placement int(11) NOT NULL default '1',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_Product'
#

CREATE TABLE eZTrade_Product (
  ID int(11) NOT NULL auto_increment,
  Name varchar(100) default NULL,
  Brief text,
  Description text,
  Keywords varchar(100) default NULL,
  Price float(10,5) default NULL,
  ShowPrice int(11) default NULL,
  ShowProduct int(11) default NULL,
  Discontinued int(11) default NULL,
  InheritOptions int(11) default NULL,
  ProductNumber varchar(100) default NULL,
  ExternalLink varchar(200) default NULL,
  IsHotDeal int(11) default '0',
  Published int(11) NOT NULL,
  Altered int(11) NOT NULL,
  RemoteID varchar(100) default NULL,
  VATTypeID int(11) NOT NULL default '0',
  ShippingGroupID int(11) NOT NULL default '0',
  ProductType int(11) default '1',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_ProductCategoryDefinition'
#

CREATE TABLE eZTrade_ProductCategoryDefinition (
  ID int(11) NOT NULL auto_increment,
  ProductID int(11) NOT NULL default '0',
  CategoryID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_ProductCategoryLink'
#

CREATE TABLE eZTrade_ProductCategoryLink (
  ID int(11) NOT NULL auto_increment,
  CategoryID int(11) default NULL,
  ProductID int(11) default NULL,
  Placement int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_ProductImageDefinition'
#

CREATE TABLE eZTrade_ProductImageDefinition (
  ProductID int(11) NOT NULL default '0',
  ThumbnailImageID int(11) default NULL,
  MainImageID int(11) default NULL,
  PRIMARY KEY (ProductID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_ProductImageLink'
#

CREATE TABLE eZTrade_ProductImageLink (
  ID int(11) NOT NULL auto_increment,
  ProductID int(11) default NULL,
  ImageID int(11) default NULL,
  Created int(11) NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_ProductOptionLink'
#

CREATE TABLE eZTrade_ProductOptionLink (
  ID int(11) NOT NULL auto_increment,
  ProductID int(11) default NULL,
  OptionID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_ProductPriceLink'
#

CREATE TABLE eZTrade_ProductPriceLink (
  ProductID int(11) NOT NULL default '0',
  PriceID int(11) NOT NULL default '0',
  OptionID int(11) NOT NULL default '0',
  ValueID int(11) NOT NULL default '0',
  Price float(10,2) default NULL,
  PRIMARY KEY (ProductID,PriceID,OptionID,ValueID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_ProductQuantityDict'
#

CREATE TABLE eZTrade_ProductQuantityDict (
  ProductID int(11) NOT NULL default '0',
  QuantityID int(11) NOT NULL default '0',
  PRIMARY KEY (ProductID,QuantityID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_ProductSectionDict'
#

CREATE TABLE eZTrade_ProductSectionDict (
  ProductID int(11) NOT NULL default '0',
  SectionID int(11) NOT NULL default '0',
  Placement int(11) NOT NULL default '0',
  PRIMARY KEY (ProductID,SectionID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_ProductTypeLink'
#

CREATE TABLE eZTrade_ProductTypeLink (
  ID int(11) NOT NULL auto_increment,
  ProductID int(11) default NULL,
  TypeID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_Quantity'
#

CREATE TABLE eZTrade_Quantity (
  ID int(11) NOT NULL auto_increment,
  Quantity int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_QuantityRange'
#

CREATE TABLE eZTrade_QuantityRange (
  ID int(11) NOT NULL auto_increment,
  MaxRange int(11) default NULL,
  Name varchar(30) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_ShippingGroup'
#

CREATE TABLE eZTrade_ShippingGroup (
  ID int(11) NOT NULL auto_increment,
  Name char(100) default NULL,
  Created int(11) NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_ShippingType'
#

CREATE TABLE eZTrade_ShippingType (
  ID int(11) NOT NULL auto_increment,
  Name char(100) default NULL,
  Created int(11) NOT NULL,
  IsDefault int(11) NOT NULL default '0',
  VATTypeID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_ShippingValue'
#

CREATE TABLE eZTrade_ShippingValue (
  ID int(11) NOT NULL auto_increment,
  ShippingGroupID int(11) NOT NULL default '0',
  ShippingTypeID int(11) NOT NULL default '0',
  StartValue float NOT NULL default '0',
  AddValue float NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_Type'
#

CREATE TABLE eZTrade_Type (
  ID int(11) NOT NULL auto_increment,
  Name varchar(150) default NULL,
  Description text,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_VATType'
#

CREATE TABLE eZTrade_VATType (
  ID int(11) NOT NULL auto_increment,
  Name char(100) default NULL,
  VATValue float NOT NULL default '0',
  Created int(11) NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_ValueQuantityDict'
#

CREATE TABLE eZTrade_ValueQuantityDict (
  ValueID int(11) NOT NULL default '0',
  QuantityID int(11) NOT NULL default '0',
  PRIMARY KEY (ValueID,QuantityID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_WishList'
#

CREATE TABLE eZTrade_WishList (
  ID int(11) NOT NULL auto_increment,
  UserID int(11) default NULL,
  IsPublic int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_WishListItem'
#

CREATE TABLE eZTrade_WishListItem (
  ID int(11) NOT NULL auto_increment,
  ProductID int(11) default NULL,
  Count int(11) default NULL,
  WishListID int(11) default NULL,
  IsBought int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZTrade_WishListOptionValue'
#

CREATE TABLE eZTrade_WishListOptionValue (
  ID int(11) NOT NULL auto_increment,
  WishListItemID int(11) default NULL,
  OptionID int(11) default NULL,
  OptionValueID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

