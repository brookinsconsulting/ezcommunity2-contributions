#
# $Id: eznews.sql,v 1.18 2000/10/16 13:42:19 pkej-cvs Exp $
#
# eZNews database schema.
#
# Primarily here for creating types of nodes in a news
# hiearchy, but also to map those nodes handling to specific
# classes and tables for handling.

DROP TABLE eZNews_ItemType;

CREATE TABLE eZNews_ItemType
(
    ID          int(11) DEFAULT '0' NOT NULL AUTO_INCREMENT,
    ParentID    int(11) DEFAULT '0' NOT NULL REFERENCES eZNews_ItemType(ID),
    Name        varchar(255) NOT NULL,
    eZClass     varchar(255) NOT NULL,
    eZTable     varchar(255) NOT NULL,

    PRIMARY KEY (ID),
    KEY (ParentID),
    UNIQUE KEY (Name),
    KEY (eZClass),
    KEY (eZTable)
);
<<<<<<< eznews.sql

INSERT INTO eZNews_ItemType VALUES (1, 'category', 'eZNewsCategory', 'eZNews_Category');
INSERT INTO eZNews_ItemType VALUES (2, 'article',  'eZNewsArticle',  'eZNews_Article');

#Future extensions follow
=======

INSERT INTO eZNews_ItemType (ID, Name, eZClass, eZTable) VALUES ('1', 'undefined', '', '');
INSERT INTO eZNews_ItemType (ID, Name, eZClass, eZTable) VALUES ('2', 'news', '', '');
INSERT INTO eZNews_ItemType (ID, Name, eZClass, eZTable) VALUES ('3', 'flower', '', '');
INSERT INTO eZNews_ItemType (ID, ParentID, Name, eZClass, eZTable) VALUES ('4', '2', 'category',  'eZNewsCategory',  'eZNews_Category');
INSERT INTO eZNews_ItemType (ID, ParentID, Name, eZClass, eZTable) VALUES ('5', '2', 'article',  'eZNewsArticle',  'eZNews_Article');
INSERT INTO eZNews_ItemType (ID, ParentID, Name, eZClass, eZTable) VALUES ('6', '4', 'product',  'eZNewsArticleProduct',  '');
INSERT INTO eZNews_ItemType (ID, ParentID, Name, eZClass, eZTable) VALUES ('7', '4', 'nitf',  'eZNewsArticleNITF',  'eZNews_ArticleNITF');
INSERT INTO eZNews_ItemType (ID, ParentID, Name, eZClass, eZTable) VALUES ('8', '3', 'flowercategory',  'eZNewsFlowerCategory',  'eZNews_Category');
INSERT INTO eZNews_ItemType (ID, ParentID, Name, eZClass, eZTable) VALUES ('9', '3', 'flowerarticle',  'eZNewsFlowerArticle',  'eZNews_Article');


#Future extensions follow

INSERT INTO eZNews_ItemType (ID, Name, eZClass, eZTable) VALUES ('11', 'faq', '', '');
INSERT INTO eZNews_ItemType (ID, ParentID, Name, eZClass, eZTable) VALUES ('12', '11', 'question',  'eZfaqquestion',  'eZfaq_question');
INSERT INTO eZNews_ItemType (ID, ParentID, Name, eZClass, eZTable) VALUES ('13', '11', 'answer',  'eZfaqanswer',  'eZfaq_answer');
INSERT INTO eZNews_ItemType (ID, Name, eZClass, eZTable) VALUES ('14', 'diary', '', '');
INSERT INTO eZNews_ItemType (ID, ParentID, Name, eZClass, eZTable) VALUES ('15', '14', 'entry',  'eZdiaryentry',  'eZdiary_entry');





>>>>>>> 1.14

<<<<<<< eznews.sql
#INSERT INTO eZNews_ItemType VALUES (3, 'question', 'eZNewsQuestion', 'eZNews_Question');
#INSERT INTO eZNews_ItemType VALUES (4, 'answer',   'eZNewsAnswer',   'eZNews_Answer');
#INSERT INTO eZNews_ItemType VALUES (5, 'diary',    'eZNewsDiary',    'eZNews_Diary');



=======
DROP TABLE eZNews_ChangeType;

CREATE TABLE eZNews_ChangeType
(
    ID              int(11) DEFAULT '0' NOT NULL AUTO_INCREMENT,
    Name            varchar(255) NOT NULL,
    Description     varchar(255) NOT NULL,
    eZArguments     int(2) DEFAULT '0' NOT NULL,
    eZSelect        text NOT NULL,

    PRIMARY KEY (ID),
    UNIQUE KEY (Name)
);

INSERT INTO eZNews_ChangeType (Description, Name) VALUES ('The item has been deleted',       'delete'   );
INSERT INTO eZNews_ChangeType (Description, Name) VALUES ('The item has been create',       'create'   );
INSERT INTO eZNews_ChangeType (Description, Name) VALUES ('The item has been drafted',       'draft'    );
INSERT INTO eZNews_ChangeType (Description, Name) VALUES ('We don''t know how to categorize this change',         'other'    );
INSERT INTO eZNews_ChangeType (Description, Name) VALUES ('The item has been publish',     'publish'  );

INSERT INTO eZNews_ChangeType (Description, Name) VALUES ('The item has been refused',       'refuse'   );
INSERT INTO eZNews_ChangeType (Description, Name) VALUES ('The item has been retracted',     'retract'  );
INSERT INTO eZNews_ChangeType (Description, Name) VALUES ('The item has been translated',    'translate');
INSERT INTO eZNews_ChangeType (Description, Name) VALUES ('The item has been updated',       'update'   );
INSERT INTO eZNews_ChangeType (Description, Name) VALUES ('The item has been copied',        'copy'   );
INSERT INTO eZNews_ChangeType (Description, Name) VALUES ('The item is a temporary item',       'temporary'   );
INSERT INTO eZNews_ChangeType (Description, Name) VALUES ('The item is an administrative item',       'administrate'   );


>>>>>>> 1.14
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

DROP TABLE eZNews_Item;

CREATE TABLE eZNews_Item
(
    ID          int(11) DEFAULT '0' NOT NULL AUTO_INCREMENT,
    ItemTypeID  int(11) DEFAULT 'SELECT ID FROM eZNews_ItemType WHERE Name = \'undefined\'' NOT NULL REFERENCES eZNews_ItemType(ID),
    Name        varchar(255) NOT NULL,
    CreatedAt   timestamp DEFAULT 'now()' NOT NULL,

    # User ID of the creator.
    CreatedBy   int(11) DEFAULT '1' NOT NULL REFERENCES ezCommon_User(ID),

    # IP address of the creator.
    CreationIP  char(50),

    # Keep a count of how many times this article has been shown.
    # Should be checked against a list of domains/ip-addresses
    # which shouldn't be counted.
    Views       int(11) DEFAULT '0' NOT NULL,

    # Shall we show this page or not for the current user?
    Status      int(11) DEFAULT 'SELECT ID FROM eZNews_ChangeType WHERE Name = \'create\'' NOT NULL REFERENCES eZNews_ChangeType(ID),

    PRIMARY KEY (ID),
    KEY (ItemTypeID),
    KEY (Name)
);

#INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Root', 'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'create' AND Type.Name = 'category';
#INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Discussion Board', 'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'create' AND Type.Name = 'category';
#INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'faq (Frequently Asked questions)', 'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'create' AND Type.Name = 'category';
#INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Help',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'create'    AND Type.Name = 'category';
#INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'news',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'publish' AND Type.Name = 'category';

#INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'diary', 'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'create'    AND Type.Name = 'category';
#INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'news Administrative', 'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'create' AND Type.Name = 'category';
#INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Department',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'publish' AND Type.Name = 'category';
#INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Editorial',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'publish' AND Type.Name = 'category';
#INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Sports',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'publish' AND Type.Name = 'category';

#INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'National',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'publish' AND Type.Name = 'category';
#INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'International',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'publish' AND Type.Name = 'category';
#INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Local',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'publish' AND Type.Name = 'category';
#INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Feature',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'publish' AND Type.Name = 'category';
#INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Politics',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'publish' AND Type.Name = 'category';

#INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Summer Olympics 2000',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'publish' AND Type.Name = 'category';
#INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Winter Olympics 2002',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'publish' AND Type.Name = 'category';
#INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Technology',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'publish' AND Type.Name = 'category';




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


DROP TABLE eZNews_Hiearchy;

CREATE TABLE eZNews_Hiearchy
(
    ItemID      int(11) NOT NULL REFERENCES eZNews_Item(ID),
    ParentID    int(11) NOT NULL REFERENCES eZNews_Item(ID),
<<<<<<< eznews.sql
    isCanonical enum('Y', 'N') DEFAULT 'N' NOT NULL,
    
    PRIMARY KEY (ItemID, ParentID),
    KEY (ID)
);

INSERT INTO eZNews_Hiearchy VALUES (1, 2, 1);



CREATE TABLE eZNews_ChangeType
(
    ID              int(11) DEFAULT '0' NOT NULL AUTO_INCREMENT,
    Name            varchar(255) NOT NULL,
    
    PRIMARY KEY (ID),
    KEY (Name)
=======
    isCanonical enum('Y', 'N') DEFAULT 'N' NOT NULL,

    PRIMARY KEY (ItemID, ParentID)
>>>>>>> 1.14
);

<<<<<<< eznews.sql
INSERT INTO eZNews_ChangeType VALUES ('1', 'other');
INSERT INTO eZNews_ChangeType VALUES ('2', 'created');
INSERT INTO eZNews_ChangeType VALUES ('3', 'drafted');
INSERT INTO eZNews_ChangeType VALUES ('4', 'refused');
INSERT INTO eZNews_ChangeType VALUES ('5', 'published');
INSERT INTO eZNews_ChangeType VALUES ('6', 'updated');
INSERT INTO eZNews_ChangeType VALUES ('7', 'translated');
INSERT INTO eZNews_ChangeType VALUES ('8', 'retracted');
INSERT INTO eZNews_ChangeType VALUES ('9', 'deleted');
=======
#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, '0', 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Root';
#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Help' AND Parent.Name = 'Root';
#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'news' AND Parent.Name = 'Root';
#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'news Administrative' AND Parent.Name = 'news';
#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Discussion Board' AND Parent.Name = 'Root';

#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'diary' AND Parent.Name = 'Root';
#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'faq (Frequently Asked questions)' AND Parent.Name = 'Root';
#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Department' AND Parent.Name = 'news';
#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Editorial' AND Parent.Name = 'news';
#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Feature' AND Parent.Name = 'news';

#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'National' AND Parent.Name = 'news';
#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'International' AND Parent.Name = 'news';
#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Local' AND Parent.Name = 'news';
#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Sports' AND Parent.Name = 'news';
#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'N' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Sports' AND Parent.Name = 'National';

#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'N' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Sports' AND Parent.Name = 'International';
#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'N' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Sports' AND Parent.Name = 'Local';
#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Politics' AND Parent.Name = 'news';
#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'N' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Politics' AND Parent.Name = 'National';
#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'N' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Politics' AND Parent.Name = 'International';

#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'N' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Politics' AND Parent.Name = 'Local';
#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Summer Olympics 2000' AND Parent.Name = 'Sports';
#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'N' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Summer Olympics 2000' AND Parent.Name = 'Feature';
#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Winter Olympics 2002' AND Parent.Name = 'Sports';
#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'N' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Winter Olympics 2002' AND Parent.Name = 'Feature';
>>>>>>> 1.14

#INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Technology' AND Parent.Name = 'news';


<<<<<<< eznews.sql
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
    KEY (ChangedAt),
    KEY (ChangeType)
);



=======

>>>>>>> 1.14
# Changes to the items are logged.

DROP TABLE eZNews_ItemLog;

CREATE TABLE eZNews_ItemLog
(
    ID              int(11) DEFAULT '0' NOT NULL AUTO_INCREMENT,
    ItemID          int(11) NOT NULL REFERENCES eZNews_Item(ID),
    ChangeTicketID  int(11) NOT NULL REFERENCES eZNews_ChangeTicket(ID),

    PRIMARY KEY (ItemID, ChangeTicketID),
    KEY (ID)
);



<<<<<<< eznews.sql
CREATE TABLE eZNews_ItemImage
=======
DROP TABLE eZNews_ItemFile;

CREATE TABLE eZNews_ItemFile
>>>>>>> 1.14
(
<<<<<<< eznews.sql
    ID      int(11) DEFAULT '0' NOT NULL AUTO_INCREMENT,
    ItemID  int(11) NOT NULL REFERENCES eZNews_Item(ID),
    ImageID int(11) NOT NULL REFERENCES eZCommon_Image(ID),
    
    PRIMARY KEY (ItemID, ImageID),
    KEY (ID)
=======
    ID      int(11) DEFAULT '0' NOT NULL AUTO_INCREMENT,
    ItemID  int(11) NOT NULL REFERENCES eZNews_Item(ID),
    FileID int(11) NOT NULL REFERENCES eZCommon_File(ID),

    PRIMARY KEY (ItemID, FileID),
    KEY (ID)
>>>>>>> 1.14
);



<<<<<<< eznews.sql
CREATE TABLE eZNews_ItemImagePreference
(
    ID              int(11) NOT NULL REFERENCES eZNews_ItemImage(ID),    
=======
DROP TABLE eZNews_ItemImage;
>>>>>>> 1.14

<<<<<<< eznews.sql
    # Is this image the main thumbnail? (Ie. front page image.)
    isFrontImage    enum('Y','N') DEFAULT 'N' NOT NULL,
        
    PRIMARY KEY (ID)
=======
CREATE TABLE eZNews_ItemImage
(
    ID      int(11) DEFAULT '0' NOT NULL AUTO_INCREMENT,
    ItemID  int(11) NOT NULL REFERENCES eZNews_Item(ID),
    ImageID int(11) NOT NULL REFERENCES eZImageCatalogue_Image(ID),
    # Is this image the main thumbnail? (Ie. front page image.)
    isFrontImage    enum('Y','N') DEFAULT 'N' NOT NULL,
    
    # This is the image width which the user has elected to use.
    ImageWidth  int(11) DEFAULT '0',
    
    # This is the image height which the user has elected to use.
    ImageHeight  int(11) DEFAULT '0',

    # This is the image width which the user has elected to use
    # for the thumbnail image.
    ThumbImageWidth  int(11) DEFAULT '0',
    
    # This is the image height which the user has elected to use
    # for the thumbnail image.
    ThumbImageHeight  int(11) DEFAULT '0',

    PRIMARY KEY (ItemID, ImageID),
    KEY (ID)
>>>>>>> 1.14
);



<<<<<<< eznews.sql
=======
DROP TABLE eZNews_Article;

>>>>>>> 1.14
CREATE TABLE eZNews_Article
(
    ID              int(11) NOT NULL REFERENCES eZNews_Item(ID),
    Meta            longtext,
    Story           longtext,
    LinkText        varchar(255) NOT NULL,

    # Here comes a field which I really don't =, but which we need
    # Remember to ask users to write names in 'firstname secondname' order
    # Hell to pay in any furture normalization, though we store author ID
    # in the log
    AuthorText      varchar(255) NOT NULL,

    # Does this entry accept links from readers?
    AcceptLinks     enum('Y','N') DEFAULT 'N' NOT NULL,

    # Does this entry accept comments from readers?
    AcceptComments  enum('Y','N') DEFAULT 'N' NOT NULL,

    # For complex systems we need to know which renderer we
    # have to use for an item.
    MetaClass       varchar(255),
    StoryClass      varchar(255),

    # When MySQL 3.23.23 or higher is stable, use next line
    # FULLTEXT (Meta, Story),

    PRIMARY KEY(ID),
    KEY (AuthorText)
);


<<<<<<< eznews.sql

<<<<<<< eznews.sql
=======
=======


DROP TABLE eZNews_ChangeTicket;

>>>>>>> 1.14
CREATE TABLE eZNews_ChangeTicket
(
    ID              int(11) DEFAULT '0' NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Name            varchar(255) DEFAULT '' NOT NULL,
    ChangeInfo      int(11) DEFAULT '0' NOT NULL REFERENCES eZNews_Article(ID),
    ChangeTypeID    int(11) NOT NULL REFERENCES eZNews_ChangeType(ID),
    ChangedBy       int(11) NOT NULL REFERENCES ezCommon_User(ID),
    ChangedAt       timestamp DEFAULT 'now()' NOT NULL,

    # Keep track of the IP address of the person who made the change.
    ChangeIP        varchar(50),

    INDEX (Name),
    INDEX (ChangeInfo),
    INDEX (ChangedBy),
    INDEX (ChangeTypeID)
);


DROP TABLE eZNews_Category;

>>>>>>> 1.3
CREATE TABLE eZNews_Category
(
    ID                  int(11) NOT NULL REFERENCES eZNews_Item(ID),

    # This article will be used in "more" links describing this
    # category to the public.
    PublicDescriptionID     int(11) REFERENCES eZNews_Article(ID),

    # This article will be used in additonal "more" links used by
    # administrators and privileged users. (Ie. people making
    # important desicions about categorization.
    PrivateDescriptionID    int(11) REFERENCES eZNews_Article(ID),

    # Does this entry sub categories?
    AcceptSubcategories     enum('Y','N') DEFAULT 'Y' NOT NULL,

    # Does this entry have a picture to identify it?
    hasImage                enum('Y','N') DEFAULT 'N' NOT NULL,
    ImageID                 int(11) NOT NULL REFERENCES eZImageCatalogue_Image(ID),

    # How is this category ordered (ie. listed, shown)? Hmm, how do we implement these?
    OrderedBy               enum('value','manual', 'date', 'author', 'popularity') DEFAULT 'value' NOT NULL,
    Direction               enum('forward', 'reverse') DEFAULT 'forward' NOT NULL,
    
    # Propagation. Which headlines are moved upwards, how far upwards, and why.
    PropagateUp             enum('N', 'Y') DEFAULT 'Y' NOT NULL,
    
    # How many items do we propagate?
    PropagateNoItems        int(11) DEFAULT '1' NOT NULL,
    
    # Propagation rules, same as OrderedBy and Direction.
    PropagatedBy            enum('value','manual', 'date', 'author', 'popularity') DEFAULT 'value' NOT NULL,
    PropagationDirection    enum('forward', 'reverse') DEFAULT 'forward' NOT NULL,
    PRIMARY KEY(ID)
);

#INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'Root';
#INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'Help';
#INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'news Administrative';
#INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'news';
#INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'Discussion Board';

#INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'faq (Frequently Asked questions)';
#INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'diary';
#INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'Department';
#INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'Editorial';
#INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'Sports';

#INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'National';
#INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'International';
#INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'Local';
#INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'Feature';
#INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'Politics';

#INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'Summer Olympics 2000';
#INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'Winter Olympics 2002';
#INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'Technology';


INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Root', 'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'create' AND Type.Name = 'category';
INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Heistad Hagesenter',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'publish' AND Type.Name = 'flowercategory';
INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Blomster',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'publish' AND Type.Name = 'flowercategory';
INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Hagesenter',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'publish' AND Type.Name = 'flowercategory';
INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Hageartikler',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'publish' AND Type.Name = 'flowercategory';
INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Buketter',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'publish' AND Type.Name = 'flowercategory';
INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Planter',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'publish' AND Type.Name = 'flowercategory';
INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Begravelse',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'publish' AND Type.Name = 'flowercategory';
INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Euro3Plast',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'publish' AND Type.Name = 'flowercategory';
INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Hundehus',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'publish' AND Type.Name = 'flowercategory';
INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Hundehus artikkel 1',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'publish' AND Type.Name = 'flowerarticle';
INSERT INTO eZNews_Item (ItemTypeID, Name, CreationIP, Status) SELECT DISTINCT Type.ID,  'Hundehus artikkel 2',  'local', CT.ID FROM eZNews_ChangeType AS CT, eZNews_ItemType AS Type WHERE CT.Name = 'publish' AND Type.Name = 'flowerarticle';

INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, '0', 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Root';
INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Heistad Hagesenter' AND Parent.Name = 'Root';
INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Blomster' AND Parent.Name = 'Heistad Hagesenter';
INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Hagesenter' AND Parent.Name = 'Heistad Hagesenter';
INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Hageartikler' AND Parent.Name = 'Heistad Hagesenter';
INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Buketter' AND Parent.Name = 'Heistad Hagesenter';
INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Planter' AND Parent.Name = 'Heistad Hagesenter';
INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Begravelse' AND Parent.Name = 'Heistad Hagesenter';
INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Euro3Plast' AND Parent.Name = 'Heistad Hagesenter';
INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Hundehus' AND Parent.Name = 'Heistad Hagesenter';
INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Hundehus artikkel 1' AND Parent.Name = 'Hundehus';
INSERT INTO eZNews_Hiearchy (ItemID, ParentID, isCanonical) SELECT DISTINCT Item.ID, Parent.ID, 'Y' FROM eZNews_Item AS Item, eZNews_Item AS Parent WHERE Item.Name = 'Hundehus artikkel 2' AND Parent.Name = 'Hundehus';

INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'Root';
INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'Heistad Hagesenter';
INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'Blomster';
INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'Hagesenter';
INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'Hageartikler';
INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'Buketter';
INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'Planter';
INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'Begravelse';
INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'Euro3Plast';
INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'Hundehus';
<<<<<<< eznews.sql
INSERT INTO eZNews_Category (ID, PublicDescriptionID, PrivateDescriptionID ) SELECT DISTINCT ID,  '0', '0' FROM eZNews_Item AS Item WHERE Item.Name = 'Hundehus2';
=======

INSERT INTO eZNews_Article (ID) SELECT DISTINCT ID FROM eZNews_Item AS Item WHERE Item.Name = 'Hundehus artikkel 1';
INSERT INTO eZNews_Article (ID) SELECT DISTINCT ID FROM eZNews_Item AS Item WHERE Item.Name = 'Hundehus artikkel 2';
>>>>>>> 1.16


DROP TABLE eZNews_ItemPosition;

CREATE TABLE eZNews_ItemPosition
(
    ID                  int(11) DEFAULT '0' NOT NULL AUTO_INCREMENT PRIMARY KEY,
    CategoryID          int(11) NOT NULL REFERENCES eZNews_Category(ID),
    ItemID              int(11) NOT NULL REFERENCES eZNews_Item(ID),
    Position            int(11) NOT NULL,

    # An arbitrary value, used for value based positioning of items.
    Value               int(11) DEFAULT '1' NOT NULL,

    # Some dates for when this item is devalued (ie. moved down the list)
    # In the future we'll make this more...adaptive.
    DevaluationDateA    timestamp DEFAULT 'now()' NOT NULL,
    DevaluationDateB    timestamp DEFAULT 'now()' NOT NULL,
    DevaluationDateC    timestamp DEFAULT 'now()' NOT NULL,

    UNIQUE (CategoryID, ItemID),

    # We can only have one item occupying a certain position in any
    # category
    UNIQUE (CategoryID, Position)
);

