
CREATE TABLE eZDataManager_DataType(
    ID int NOT NULL,
    Name varchar(200) default "",
    PRIMARY KEY (ID)
);

CREATE TABLE eZDataManager_DataTypeItem(
    ID int NOT NULL,
    DataTypeID int NOT NULL,
    Name varchar(200) default "",
    ItemType int NOT NULL,
    Created int NOT NULL,    
    PRIMARY KEY (ID)
);

CREATE TABLE eZDataManager_Item(
    ID int NOT NULL,
    DataTypeID int NOT NULL,
    Name varchar(200) default "",
    PRIMARY KEY (ID)
);

CREATE TABLE eZDataManager_ItemValue(
    ID int NOT NULL,
    ItemID int NOT NULL,
    DataTypeItemID int NOT NULL,
    Value text,
    PRIMARY KEY (ID)
);


CREATE INDEX DataManager_Item_Name ON eZDataManager_Item (Name);
# CREATE INDEX DataManager_ItemValue_Value ON eZDataManager_ItemValue (Value);
