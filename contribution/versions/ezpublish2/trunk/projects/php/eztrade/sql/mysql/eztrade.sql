CREATE TABLE eZTrade_AlternativeCurrency (
  ID int NOT NULL,
  Name varchar(100) NOT NULL default '',
  PrefixSign int(11) NOT NULL default '0',
  Sign varchar(5) NOT NULL default '',
  Value float NOT NULL default '1',
  Created int(11) NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_Attribute (
  ID int NOT NULL,
  TypeID int(11) default NULL,
  Name varchar(150) default NULL,
  Created int(11) NOT NULL,
  Placement int(11) default '0',
  AttributeType int(11) default '1',
  Unit varchar(8) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_AttributeValue (
  ID int NOT NULL,
  ProductID int(11) default NULL,
  AttributeID int(11) default NULL,
  Value varchar(200) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_Cart (
  ID int NOT NULL,
  SessionID int(11) default NULL,
  CompanyID int(11) default '0',
  PersonID int(11) default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_CartItem (
  ID int NOT NULL,
  ProductID int(11) default NULL,
  Count int(11) default NULL,
  CartID int(11) default NULL,
  WishListItemID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_CartOptionValue (
  ID int NOT NULL,
  CartItemID int(11) default NULL,
  OptionID int(11) default NULL,
  OptionValueID int(11) default NULL,
  RemoteID varchar(100) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_Category (
  ID int NOT NULL,
  Parent int(11) default NULL,
  Description text,
  Name varchar(100) default NULL,
  ImageID int(11) default NULL,
  SortMode int(11) NOT NULL default '1',
  RemoteID varchar(100) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_CategoryOptionLink (
  ID int NOT NULL,
  CategoryID int(11) default NULL,
  OptionID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_CategoryPermission (
  ID int NOT NULL,
  ObjectID int(11) default NULL,
  GroupID int(11) default NULL,
  ReadPermission int(11) default '0',
  WritePermission int(11) default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_GroupPriceLink (
  GroupID int(11) NOT NULL default '0',
  PriceID int(11) NOT NULL default '0',
  PRIMARY KEY (GroupID,PriceID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_Link (
  ID int NOT NULL,
  SectionID int(11) NOT NULL default '0',
  Name varchar(60) default NULL,
  URL text,
  Placement int(11) NOT NULL default '0',
  ModuleType int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_LinkSection (
  ID int NOT NULL,
  Name varchar(30) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_Option (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Description text,
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_OptionValue (
  ID int NOT NULL,
  OptionID int(11) default NULL,
  Placement int(11) NOT NULL default '1',
  Price float(10,2) default NULL,
  RemoteID varchar(100) NOT NULL default '',
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_OptionValueContent (
  ID int NOT NULL,
  Value varchar(30) default NULL,
  ValueID int(11) NOT NULL default '0',
  Placement int(11) NOT NULL default '1',
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_OptionValueHeader (
  ID int NOT NULL,
  Name varchar(30) default NULL,
  OptionID int(11) NOT NULL default '0',
  Placement int(11) NOT NULL default '1',
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_Order (
  ID int NOT NULL,
  UserID int(11) NOT NULL default '0',
  ShippingCharge float(10,2) default NULL,
  PaymentMethod text,
  ShippingAddressID int(11) default NULL,
  BillingAddressID int(11) default NULL,
  IsExported int(11) NOT NULL default '0',
  Date int(11) default NULL,
  ShippingVAT float NOT NULL default '0',
  ShippingTypeID int(11) NOT NULL default '0',
  IsVATInc int(11) default '0',
  CompanyID int(11) default '0',
  PersonID int(11) default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_OrderItem (
  ID int NOT NULL,
  OrderID int(11) NOT NULL default '0',
  Count int(11) default NULL,
  Price float(10,2) default NULL,
  ProductID int(11) default NULL,
  VAT float(10,2) default NULL,
  ExpiryDate int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_OrderOptionValue (
  ID int NOT NULL,
  OrderItemID int(11) default NULL,
  OptionName varchar(25) default NULL,
  ValueName varchar(25) default NULL,
  RemoteID varchar(100) default '',
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_OrderStatus (
  ID int NOT NULL,
  StatusID int(11) NOT NULL default '0',
  Altered int(11) NOT NULL,
  AdminID int(11) default NULL,
  OrderID int(11) NOT NULL default '0',
  Comment text,
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_OrderStatusType (
  ID int NOT NULL,
  Name varchar(25) NOT NULL default '',
  PRIMARY KEY (ID),
  UNIQUE KEY Name(Name)
) TYPE=MyISAM;

INSERT INTO eZTrade_OrderStatusType VALUES (1,'intl-initial');
INSERT INTO eZTrade_OrderStatusType VALUES (2,'intl-sendt');
INSERT INTO eZTrade_OrderStatusType VALUES (3,'intl-payed');
INSERT INTO eZTrade_OrderStatusType VALUES (4,'intl-undefined');

CREATE TABLE eZTrade_PreOrder (
  ID int NOT NULL,
  Created int(11) NOT NULL,
  OrderID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_PriceGroup (
  ID int NOT NULL,
  Name varchar(50) default NULL,
  Description text,
  Placement int(11) NOT NULL default '1',
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_Product (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Contents text,
  Brief text,
  Description text,
  Keywords varchar(100) default NULL,
  Price float(10,5) default NULL,
  ShowPrice int(11) default '1',
  ShowProduct int(11) default '1',
  Discontinued int(11) default '0',
  ProductNumber varchar(100) default NULL,
  ExternalLink varchar(200) default NULL,
  IsHotDeal int(11) default '0',
  RemoteID varchar(100) default NULL,
  VATTypeID int(11) NOT NULL default '0',
  ShippingGroupID int(11) NOT NULL default '0',
  ProductType int(11) default '1',
  ExpiryTime int(11) NOT NULL default '0',
  Published int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_ProductCategoryDefinition (
  ID int NOT NULL,
  ProductID int(11) NOT NULL default '0',
  CategoryID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_ProductCategoryLink (
  ID int NOT NULL,
  CategoryID int(11) default NULL,
  ProductID int(11) default NULL,
  Placement int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_ProductImageDefinition (
  ProductID int(11) NOT NULL default '0',
  ThumbnailImageID int(11) default NULL,
  MainImageID int(11) default NULL,
  PRIMARY KEY (ProductID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_ProductImageLink (
  ID int NOT NULL,
  ProductID int(11) default NULL,
  Placement int(11) default NULL,
  ImageID int(11) default NULL,
  Created int(11) NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_ProductOptionLink (
  ID int NOT NULL,
  ProductID int(11) default NULL,
  OptionID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_ProductPermission (
  ID int NOT NULL,
  ObjectID int(11) default NULL,
  GroupID int(11) default NULL,
  ReadPermission int(11) default '0',
  WritePermission int(11) default '0',
  PRIMARY KEY (ID),
  KEY ProductPermissionObjectID(ObjectID),
  KEY ProductPermissionGroupID(GroupID),
  KEY ProductPermissionWritePermission(WritePermission),
  KEY ProductPermissionReadPermission(ReadPermission)
) TYPE=MyISAM;


CREATE TABLE eZTrade_ProductPermissionLink (
  ID int(11) NOT NULL default '0',
  ProductID int(11) NOT NULL default '0',
  GroupID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_ProductPriceLink (
  ProductID int(11) NOT NULL default '0',
  PriceID int(11) NOT NULL default '0',
  OptionID int(11) NOT NULL default '0',
  ValueID int(11) NOT NULL default '0',
  Price float(10,2) default NULL,
  PRIMARY KEY (ProductID,PriceID,OptionID,ValueID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_ProductQuantityDict (
  ProductID int(11) NOT NULL default '0',
  QuantityID int(11) NOT NULL default '0',
  PRIMARY KEY (ProductID,QuantityID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_ProductSectionDict (
  ProductID int(11) NOT NULL default '0',
  SectionID int(11) NOT NULL default '0',
  Placement int(11) NOT NULL default '0',
  PRIMARY KEY (ProductID,SectionID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_ProductTypeLink (
  ID int NOT NULL,
  ProductID int(11) default NULL,
  TypeID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_Quantity (
  ID int NOT NULL,
  Quantity int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_QuantityRange (
  ID int NOT NULL,
  MaxRange int(11) default NULL,
  Name varchar(30) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_ShippingGroup (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Created int(11) NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_ShippingType (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Created int(11) NOT NULL,
  IsDefault int(11) NOT NULL default '0',
  VATTypeID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_ShippingValue (
  ID int NOT NULL,
  ShippingGroupID int(11) NOT NULL default '0',
  ShippingTypeID int(11) NOT NULL default '0',
  StartValue float NOT NULL default '0',
  AddValue float NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_Type (
  ID int NOT NULL,
  Name varchar(150) default NULL,
  Description text,
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_VATType (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  VATValue float NOT NULL default '0',
  Created int(11) NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_ValueQuantityDict (
  ValueID int(11) NOT NULL default '0',
  QuantityID int(11) NOT NULL default '0',
  PRIMARY KEY (ValueID,QuantityID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_Voucher (
  ID int(11) default '0',
  Created int(11) default '0',
  Price float default '0',
  Available int(11) default '0',
  KeyNumber varchar(50) default NULL
) TYPE=MyISAM;


CREATE TABLE eZTrade_VoucherEMail (
  ID int(11) default '0',
  VoucherID int(11) default '0',
  Email varchar(40) default NULL,
  Description text,
  PreOrderID int(11) default '0'
) TYPE=MyISAM;


CREATE TABLE eZTrade_VoucherSMail (
  ID int(11) default '0',
  VoucherID int(11) default '0',
  AddressID int(11) default '0',
  Description text,
  PreOrderID int(11) default '0'
) TYPE=MyISAM;


CREATE TABLE eZTrade_VoucherUsed (
  ID int(11) default '0',
  Used int(11) default '0',
  Price float default NULL,
  VoucherID int(11) default '0'
) TYPE=MyISAM;


CREATE TABLE eZTrade_WishList (
  ID int NOT NULL,
  UserID int(11) default NULL,
  IsPublic int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_WishListItem (
  ID int NOT NULL,
  ProductID int(11) default NULL,
  Count int(11) default NULL,
  WishListID int(11) default NULL,
  IsBought int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE TABLE eZTrade_WishListOptionValue (
  ID int NOT NULL,
  WishListItemID int(11) default NULL,
  OptionID int(11) default NULL,
  OptionValueID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

