#
# $Id: eznews.sql,v 1.2 2000/09/15 10:55:01 pkej-cvs Exp $
#
# eZNews database schema.
#
# Primarily here for creating types of nodes in a news
# hiearchy, but also to map those nodes handling to specific
# classes and tables for handling.

CREATE TABLE eZNews_ItemType
(
    ID          int(11) DEFAULT '0' NOT NULL AUTO_INCREMENT,
    Name        varchar(255) NOT NULL,
    eZClass     varchar(255) NOT NULL,
    eZTable     varchar(255) NOT NULL,

    PRIMARY KEY (ID),
    KEY (Name),
    KEY (eZClass),
    KEY (eZTable)
);

INSERT INTO eZNews_ItemType VALUES (1, 'Category', 'eZArticleCategory', 'eZNews_Category');
INSERT INTO eZNews_ItemType VALUES (2, 'Article',  'eZArticle',         'eZNews_Article');



# This table keeps track of all items in the hiearcy,
# from categories, to articles.
#
# This simplifies the system by giving every element a
# unique ID, which is needed for support of some XML
# standards. Not to mention the fact that we don't have
# to create special cases.
#
# The logging function for this module benefits from
# this design.

CREATE TABLE eZNews_Item
(
    ID          int(11) DEFAULT '0' NOT NULL AUTO_INCREMENT,
    ItemTypeID  int(11) NOT NULL REFERENCES eZNews_ItemType(ID),
    Name        varchar(255) NOT NULL,
        
    # We need to decide if the item is viewable,
    # other info is stored in the log, but we need this
    # fast and easy way to determine if we need to show
    # the info or not.
    #
    # This is only for GLOBAL viewing.
    isVisible   enum('Y','N') DEFAULT 'Y' NOT NULL,

    PRIMARY KEY (ID),
    KEY (ItemTypeID),
    KEY (Name)
);

INSERT INTO eZNews_Item VALUES (1, 1,  'Root', 'Y');
INSERT INTO eZNews_Item VALUES (2, 1,  'News', 'Y');



# This table is used for mapping our tree structure.

CREATE TABLE eZNews_Hiearchy
(
    ID          int(11) DEFAULT '0' NOT NULL AUTO_INCREMENT,
    ItemID      int(11) NOT NULL REFERENCES eZNews_Item(ID),
    ParentID    int(11) NOT NULL REFERENCES eZNews_Item(ID),
    
    PRIMARY KEY (ItemID, ParentID),
    KEY (ID)
);

INSERT INTO eZNews_Hiearchy VALUES (1, 2, 1);



# Changes to the items are logged.

CREATE TABLE eZNews_ItemLog
(
    ID              int(11) DEFAULT '0' NOT NULL AUTO_INCREMENT,
    ItemID          int(11) NOT NULL REFERENCES eZNews_Item(ID),
    ChangeTicketID  int(11) NOT NULL REFERENCES eZNews_ChangeTicket(ID),
    
    PRIMARY KEY (ItemID, ChangeTicketID),
    KEY (ID)
);



CREATE TABLE eZNews_ChangeType
(
    ID              int(11) DEFAULT '0' NOT NULL AUTO_INCREMENT,
    Name            varchar(255) NOT NULL,
    
    PRIMARY KEY (ID),
    KEY (Name)
);

INSERT INTO eZNews_ChangeType VALUES ('1', 'Other');
INSERT INTO eZNews_ChangeType VALUES ('2', 'Created');
INSERT INTO eZNews_ChangeType VALUES ('3', 'Drafted');
INSERT INTO eZNews_ChangeType VALUES ('4', 'Refused');
INSERT INTO eZNews_ChangeType VALUES ('5', 'Published');
INSERT INTO eZNews_ChangeType VALUES ('6', 'Updated');
INSERT INTO eZNews_ChangeType VALUES ('7', 'Translated');
INSERT INTO eZNews_ChangeType VALUES ('8', 'Retracted');
INSERT INTO eZNews_ChangeType VALUES ('9', 'Deleted');



CREATE TABLE eZNews_ItemImage
(
    ID      int(11) DEFAULT '0' NOT NULL AUTO_INCREMENT,
    ItemID  int(11) NOT NULL REFERENCES eZNews_Item(ID),
    ImageID int(11) NOT NULL REFERENCES eZCommon_Image(ID),
    
    PRIMARY KEY (ItemID, ImageID),
    KEY (ID)
);



CREATE TABLE eZNews_ItemImagePreference
(
    ID              int(11) NOT NULL REFERENCES eZNews_ItemImage(ID),    

    # Is this image the main thumbnail? (Ie. front page image.)
    isFrontImage    enum('Y','N') DEFAULT 'N' NOT NULL,
        
    PRIMARY KEY (ID)
);



CREATE TABLE eZNews_Article
(
    # We generate a unique ID so that we can have easy refrences to
    # the table.
    ID              int(11) DEFAULT '0' NOT NULL AUTO_INCREMENT,
    ItemID          int(11) NOT NULL REFERENCES eZNews_Item(ID),
    Title           varchar(255) NOT NULL,
    Meta            longtext,
    Story           longtext,
    LinkText        varchar(255) NOT NULL,
    AuthorText      varchar(255) NOT NULL,
    
    # When MySQL 3.23.23 or higher is stable, use next line
    # FULLTEXT (Meta, Story),
    
    PRIMARY KEY(ID),
    KEY (Title),
    KEY (ItemID),
    KEY (AuthorText)
);



CREATE TABLE eZNews_ArticlePreference
(
    ID              int(11) NOT NULL REFERENCES eZNews_Article(ID),

    # Does this entry accept links from readers?
    AcceptLinks     enum('Y','N') DEFAULT 'N' NOT NULL,

    # Does this entry accept comments from readers?
    AcceptComments  enum('Y','N') DEFAULT 'N' NOT NULL,

    # For complex systems we need to know which renderer we
    # have to use for an item.
    MetaClass       varchar(255),
    StoryClass      varchar(255),
    
    PRIMARY KEY(ID)
);



CREATE TABLE eZNews_ChangeTicket
(
    ID          int(11) DEFAULT '0' NOT NULL AUTO_INCREMENT,
    ChangeInfo  int(11) DEFAULT '0' NOT NULL REFERENCES eZNews_Article(ID),
    ChangeType  int(11) NOT NULL REFERENCES eZNews_ChangeType(ID),
    ChangeText  varchar(255),
    ChangedBy   int(11) NOT NULL REFERENCES ezCommon_User(ID),
    ChangedAt   timestamp DEFAULT 'now()',    
    PRIMARY KEY (ID),
    KEY (ChangeInfo),
    KEY (ChangedBy),
    KEY (ChangeType)
);



CREATE TABLE eZNews_Category
(
    ID                      int(11) DEFAULT '0' NOT NULL AUTO_INCREMENT,
    ItemID                  int(11) NOT NULL REFERENCES eZNews_Item(ID),
    
    # This article will be used in "more" links describing this
    # category to the public.
    PublicDescriptionID     int(11) REFERENCES eZNews_Article(ID),
    
    # This article will be used in additonal "more" links used by
    # administrators and privileged users. (Ie. people making
    # important desicions about categorization.
    PrivateDescriptionID    int(11) REFERENCES eZNews_Article(ID),
    
    PRIMARY KEY(ID)
);



CREATE TABLE eZNews_CategoryPreference
(
    ID                  int(11) NOT NULL REFERENCES eZNews_Category(ID),

    # Does this entry sub categories?
    AcceptSubcategories enum('Y','N') DEFAULT 'Y' NOT NULL,

    # Does this entry have a picture to identify it?
    hasImage            enum('Y','N') DEFAULT 'N' NOT NULL,
    ImageURL            varchar(255),

    PRIMARY KEY(ID)
);



CREATE TABLE eZNews_ItemPosition
(
    ID                  int(11) DEFAULT '0' NOT NULL AUTO_INCREMENT,
    CategoryID          int(11) NOT NULL REFERENCES eZNews_Category(ID),
    ItemID              int(11) NOT NULL REFERENCES eZNews_Item(ID),
    Position            int(11) NOT NULL,
    
    PRIMARY KEY (CategoryID, ItemID),
    KEY (ID),
    
    # We can only have one item occupying a certain position in any
    # category
    UNIQUE (CategoryID, Position)    
);
