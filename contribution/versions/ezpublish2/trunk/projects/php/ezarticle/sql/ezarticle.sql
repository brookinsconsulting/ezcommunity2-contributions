#
# Table structure for table 'eZArticle_Article'
#
DROP TABLE IF EXISTS eZArticle_Article;
CREATE TABLE eZArticle_Article (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Contents text,
  AuthorText varchar(100),
  LinkText varchar(50),
  AuthorID int(11) DEFAULT '0' NOT NULL,
  Modified timestamp(14),
  Created timestamp(14),
  PageCount int(11),
  IsPublished enum('true','false') DEFAULT 'false',
  Published timestamp(14),
  Keywords text,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZArticle_Article'
#

INSERT INTO eZArticle_Article VALUES (1,'Demo article','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>This is a demo article. It will demontrate the power of the eZTechRenderer used for generating articles.</intro><body><page><header>Here I will demonstrate some simple tags</header>\r\n\r\n<bold> this is bold text</bold> \r\n<italic>this is italic text</italic>\r\n<strike>this is strike through text</strike>\r\n\r\n<link href=\"ez.no\" text=\"this is a link\" />\r\n<mail to=\"bf@ez.no\" subject=\"demo\" text=\"mail me\" /> a mail link with subject set to demo\r\n\r\n</page><page>\r\n\r\n<header>Here I will demonstrate images</header>\r\n\r\nAs you see the images are generated on the fly, so you can request any size (small, medium, large) at any time.\r\n\r\n<image id=\"1\" align=\"left\" size=\"small\" /> This is a small image. Bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla .\r\n\r\nbla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla .\r\n\r\n<header>A large image:</header>\r\n\r\n<image id=\"2\" align=\"center\" size=\"large\" /> \r\n\r\n<header>Images on a row</header>\r\n\r\n<image id=\"1\" align=\"float\" size=\"small\" /> <image id=\"2\" align=\"float\" size=\"small\" /> <image id=\"3\" align=\"float\" size=\"small\" />\r\n\r\n</page><page>\r\n\r\n<header>Coding tags</header>\r\n\r\nHere I will demonstrate som programming tags.\r\n\r\n<php>\r\n// this is php code\r\nfunction foo()\r\n{\r\n  bar();\r\n}\r\n</php>\r\n\r\nAnd some cpp:\r\n<cpp>\r\nclass foo\r\n{\r\n  foo();\r\n  void bar();\r\n}\r\n</cpp>\r\n\r\nAnd \r\n<ezhtml>\r\n&lt;html&gt;\r\n&lt;head&gt;\r\n  &lt;title&gt;\r\n  Title\r\n  &lt;/title&gt;\r\n&lt;/head&gt;\r\n&lt;body&gt;\r\nthis is the body\r\n&lt;/body&gt;\r\n&lt;/html&gt;\r\n</ezhtml>\r\n\r\n</page></body></article>','Bård Farstad','read',27,20001219123508,20001101122842,3,'true',20001101122842,'tech\nThis is a demo article. It will demontrate the power of eZTechRenderer used for generating articles.Here I demonstrate some simple tags\r\n\r\n this bold text \r\nthis italic text\r\nthis strike through text\r\n\r\n\r\n mail link with subject set to demo\r\n\r\n\r\n\r\nHere images\r\n\r\nAs you see images are generated on fly, so can request any size (small, medium, large) at time.\r\n\r\n This small image. Bla bla .\r\n\r\nbla .\r\n\r\nA large image:\r\n\r\n \r\n\r\nImages row\r\n\r\n  \r\n\r\n\r\n\r\nCoding tags\r\n\r\nHere som programming tags.\r\n\r\n\r\n// php code\r\nfunction foo()\r\n{\r\n bar();\r\n}\r\n\r\n\r\nAnd cpp:\r\n\r\nclass foo\r\n{\r\n foo();\r\n void \r\n\r\n&lt;html&gt;\r\n&lt;head&gt;\r\n &lt;title&gt;\r\n Title\r\n &lt;/title&gt;\r\n&lt;/head&gt;\r\n&lt;body&gt;\r\nthis body\r\n&lt;/body&gt;\r\n&lt;/html&gt;\r\n\r\n\r\n ');
INSERT INTO eZArticle_Article VALUES (2,'About eZ publish','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>eZ publish is an Open Source portal building software, content manager or publishing solution whichever fits your needs. </intro><body><page>It is released under the GPL license and can be downloaded from <link href=\"publish.ez.no\" text=\"publish.ez.no\" />. You can get commercial support from eZ systems at <link href=\"ez.no\" text=\"ez.no\" /> or at <link href=\"sourceprovider.com\" text=\"sourceprovider.com\" />.\r\n\r\nThere is a similar project which deals with e-commerce solutions at <link href=\"trade.ez.no\" text=\"trade.ez.no\" /></page></body></article>','admin user','About eZ publish',27,20010103105143,20001123085405,1,'true',20001123085405,'tech\neZ publish is an Open Source portal building software, content manager or publishing solution whichever fits your needs. It released under the GPL license and can be downloaded from . You get commercial support eZ systems at  .\r\n\r\nThere a similar project which deals with e-commerce solutions ');
INSERT INTO eZArticle_Article VALUES (3,'eZ publish introduction','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>This is an example of articles which is a member of two or more categories. As you can see this article is a part of the News and Help category.\r\n</intro><body><page>For more information about eZ publish go to :\r\n<link href=\"developer.ez.no\" text=\"developer.ez.no\" />.</page></body></article>','admin user','Read more',27,20001123090845,20001123090702,1,'true',20001123090717,'tech\nThis is an example of articles which a member two or more categories. As you can see this article part the News and Help category.\r\nFor information about eZ publish go to :\r\n. ');
INSERT INTO eZArticle_Article VALUES (4,'filetest','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>sadf</intro><body><page>asdf</page></body></article>','admin user','asdf',27,20001222130605,20001221173430,1,'true',20001221173430,'tech\nsadfasdf ');
INSERT INTO eZArticle_Article VALUES (5,'asdfasdf','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>asdf</intro><body><page>asdfsadf</page></body></article>','admin user','234234',27,20010103105707,20010103105651,1,'true',20010103105651,'tech\nasdfasdfsadf ');

#
# Table structure for table 'eZArticle_ArticleCategoryDefinition'
#
DROP TABLE IF EXISTS eZArticle_ArticleCategoryDefinition;
CREATE TABLE eZArticle_ArticleCategoryDefinition (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ArticleID int(11) DEFAULT '0' NOT NULL,
  CategoryID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZArticle_ArticleCategoryDefinition'
#

INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (4,1,1);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (8,2,2);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (3,3,3);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (7,4,3);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (9,5,2);

#
# Table structure for table 'eZArticle_ArticleCategoryLink'
#
DROP TABLE IF EXISTS eZArticle_ArticleCategoryLink;
CREATE TABLE eZArticle_ArticleCategoryLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ArticleID int(11) DEFAULT '0' NOT NULL,
  CategoryID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZArticle_ArticleCategoryLink'
#

INSERT INTO eZArticle_ArticleCategoryLink VALUES (5,1,1);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (9,2,2);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (4,3,1);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (3,3,3);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (8,4,3);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (10,5,2);

#
# Table structure for table 'eZArticle_ArticleFileLink'
#
DROP TABLE IF EXISTS eZArticle_ArticleFileLink;
CREATE TABLE eZArticle_ArticleFileLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ArticleID int(11) DEFAULT '0' NOT NULL,
  FileID int(11) DEFAULT '0' NOT NULL,
  Created timestamp(14),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZArticle_ArticleFileLink'
#

INSERT INTO eZArticle_ArticleFileLink VALUES (1,0,8,20001221182033);
INSERT INTO eZArticle_ArticleFileLink VALUES (2,4,9,20001221183007);
INSERT INTO eZArticle_ArticleFileLink VALUES (3,4,10,20001221183015);
INSERT INTO eZArticle_ArticleFileLink VALUES (4,4,11,20001222130308);
INSERT INTO eZArticle_ArticleFileLink VALUES (5,4,12,20001222130331);
INSERT INTO eZArticle_ArticleFileLink VALUES (6,4,13,20001222130552);

#
# Table structure for table 'eZArticle_ArticleForumLink'
#
DROP TABLE IF EXISTS eZArticle_ArticleForumLink;
CREATE TABLE eZArticle_ArticleForumLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ArticleID int(11) DEFAULT '0' NOT NULL,
  ForumID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZArticle_ArticleForumLink'
#

INSERT INTO eZArticle_ArticleForumLink VALUES (1,1,3);
INSERT INTO eZArticle_ArticleForumLink VALUES (2,3,4);
INSERT INTO eZArticle_ArticleForumLink VALUES (3,4,5);
INSERT INTO eZArticle_ArticleForumLink VALUES (4,2,6);
INSERT INTO eZArticle_ArticleForumLink VALUES (5,5,7);

#
# Table structure for table 'eZArticle_ArticleImageDefinition'
#
DROP TABLE IF EXISTS eZArticle_ArticleImageDefinition;
CREATE TABLE eZArticle_ArticleImageDefinition (
  ArticleID int(11) DEFAULT '0' NOT NULL,
  ThumbnailImageID int(11),
  PRIMARY KEY (ArticleID),
  UNIQUE ArticleID (ArticleID)
);

#
# Dumping data for table 'eZArticle_ArticleImageDefinition'
#

INSERT INTO eZArticle_ArticleImageDefinition VALUES (1,1);

#
# Table structure for table 'eZArticle_ArticleImageLink'
#
DROP TABLE IF EXISTS eZArticle_ArticleImageDefinition;
CREATE TABLE eZArticle_ArticleImageDefinition (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ArticleID int(11) DEFAULT '0' NOT NULL,
  ImageID int(11) DEFAULT '0' NOT NULL,
  Created timestamp(14),
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZArticle_ArticleImageLink'
#
DROP TABLE IF EXISTS eZArticle_ArticleImageLink;
CREATE TABLE eZArticle_ArticleImageLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ArticleID int(11) DEFAULT '0' NOT NULL,
  ImageID int(11) DEFAULT '0' NOT NULL,
  Created timestamp(14),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZArticle_ArticleImageLink'
#

INSERT INTO eZArticle_ArticleImageLink VALUES (1,1,1,20001219123357);
INSERT INTO eZArticle_ArticleImageLink VALUES (2,1,2,20001219123406);
INSERT INTO eZArticle_ArticleImageLink VALUES (3,1,3,20001219123415);

#
# Table structure for table 'eZArticle_Category'
#
DROP TABLE IF EXISTS eZArticle_Category;
CREATE TABLE eZArticle_Category (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Description text,
  ParentID int(11) DEFAULT '0',
  ExcludeFromSearch enum('true','false') DEFAULT 'false',
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZArticle_Category'
#

INSERT INTO eZArticle_Category VALUES (1,'News','Here are some news, fresh from the press.',0,'false');
INSERT INTO eZArticle_Category VALUES (2,'Static pages','Here you put pages which is not supposed to come up in search.',0,'true');
INSERT INTO eZArticle_Category VALUES (3,'Help','',0,'false');
