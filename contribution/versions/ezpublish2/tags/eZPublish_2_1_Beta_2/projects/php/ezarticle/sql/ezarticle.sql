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

INSERT INTO eZArticle_Article VALUES (1,'Demo article','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>This article will show the tags you can use in eZ publish.</intro><body><page><header>Standard tags</header>\r\n\r\nThis is <bold>bold</bold> text.\r\nThis is <strike>strike</strike> text.\r\nThis is <underline>underline</underline> text.\r\n\r\n<pre>\r\nPre defined text\r\n  indented\r\n    as \r\n      written.\r\n</pre>\r\n<bullet>\r\nItem one\r\nItem two\r\nItem three\r\n</bullet>\r\n\r\n<header>Image tags</header>\r\n\r\n<image id=\"1\" align=\"left\" size=\"medium\" /> Fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text.\r\n\r\n<image id=\"2\" align=\"center\" size=\"medium\" />\r\n\r\nImages on a row\r\n\r\n<image id=\"1\" align=\"float\" size=\"small\" /> <image id=\"2\" align=\"float\" size=\"small\" /> <image id=\"1\" align=\"float\" size=\"small\" /> <image id=\"2\" align=\"float\" size=\"small\" /></page></body></article>','admin user','See demo',27,20010126110422,20010126100350,1,'true',20010126100350,'tech\nThis article will show the tags you can use in eZ publish.Standard tags\r\n\r\nThis is bold text.\r\nThis strike underline text.\r\n\r\n\r\nPre defined text\r\n  indented\r\n as \r\n written.\r\n\r\n\r\nItem one\r\nItem two\r\nItem three\r\n\r\n\r\nImage tags\r\n\r\n Fill text fill text.\r\n\r\n\r\n\r\nImages on a row\r\n\r\n ');
INSERT INTO eZArticle_Article VALUES (5,'What is New in 2.0?','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>This is a major new release of eZ publish, we\'ve added lots of new information.</intro><body><page><bullet>Merged eZ trade with eZ publish\r\nAdded about module\r\nAdded ad module\r\nAdded address module\r\nAdded bug tracking module\r\nAdded calendar module\r\nAdded contact module\r\nAdded newsfeed module\r\nAdded statistics module\r\nAdded todo module\r\nAdded cookie-less sessions \r\nAdded absolute positioning of products and articles\r\nAdded choosable sort mode on article categories\r\nAdded choosable sort mode on product categories\r\nAdded previous/next paging of article lists (admin &amp; user )\r\nAdded previous/next paging of product lists (admin &amp; user )\r\nAdded RSS headlines\r\nAdded forum moderation with assignment of moderator\r\nAdded notification when articles are published\r\nAdded file uploads on articles.\r\nAdded dynamically updating of menues with static pages.\r\nAdded file upload to eZ article\r\nAdded word wrap of message replies in eZ forum. Nicer looking replies.\r\nAdded new tags in articles (bullet lists/includes of php files)\r\nAdded preferred layout for users\r\nMade the menus in the admin module expandable/collapsable as well as moveable. This is remembered by the preferences for each user. We\'ve also changed the design to a more sleek version.\r\nLanguage updates\r\nRemoved java script which were a problem for lynx users.\r\nRemoved strip tags from messages in eZ forum\r\nSpeeded up many features among them database connections, localisation, rendering of articles, templates and HTML.\r\nFixed bugs</bullet>\r\n\r\n\r\nRead on to learn how to use some of the new features.\r\n</page><page>\r\n<header>RSS Headlines</header>\r\nYou can access the RSS Headlines of eZ publish from the URL \"/article/rssheadlines\".\r\n\r\nIn \"site.ini\" you can configure some of its options; read more in the \"eZ article Admin\'s Guide\" and \"eZ publish Customisation Guide\".\r\n\r\n<header>About</header>\r\nIf you write in the URL \"/about\" you\'ll be presented with an about box for eZ publish.\r\n\r\n<header>User Preferences</header>\r\nWe\'ve added preference functionality. If you take a look into the left hand column of this site you\'ll find some links which are called \"intranet\", \"portal site\" and \"E-commerce\". Those links take you to different designs for eZ publish (only two links will be shown at any time).\r\n\r\nAn example of its usage might be to give users the option of reading your site with different amounts of graphics, or different text sizes.\r\n\r\n<header>Cookie-less Sessions</header>\r\nWe\'ve added cookie-less sessions.\r\n\r\n<header>Moderated Forums</header>\r\nWe\'ve added the much requested moderation functionality to forums. Now you can assign a moderator to each and every forum.\r\n\r\nUsage for this function might, in addition to plain old moderation, is to protect forums so that you can use them as an FAQ.</page></body></article>','admin user','Read the changelog...',27,20010126112508,20010126102640,2,'true',20010126102640,'tech\nThis is a major new release of eZ publish, we\'ve added lots information.Merged trade with publish\r\nAdded about module\r\nAdded ad address bug tracking calendar contact newsfeed statistics todo cookie-less sessions \r\nAdded absolute positioning products and articles\r\nAdded choosable sort mode on article categories\r\nAdded product previous/next paging lists (admin &amp; user )\r\nAdded RSS headlines\r\nAdded forum moderation assignment moderator\r\nAdded notification when articles are published\r\nAdded file uploads articles.\r\nAdded dynamically updating menues static pages.\r\nAdded upload to article\r\nAdded word wrap message replies in forum. Nicer looking replies.\r\nAdded tags (bullet lists/includes php files)\r\nAdded preferred layout for users\r\nMade the menus admin module expandable/collapsable as well moveable. This remembered by preferences each user. We\'ve also changed design more sleek version.\r\nLanguage updates\r\nRemoved java script which were problem lynx users.\r\nRemoved strip from messages forum\r\nSpeeded up many features among them database connections, localisation, rendering articles, templates HTML.\r\nFixed bugs\r\n\r\n\r\nRead learn how use some features.\r\n\r\nRSS Headlines\r\nYou can access Headlines publish URL \"/article/rssheadlines\".\r\n\r\nIn \"site.ini\" you configure its options; read \"eZ Admin\'s Guide\" Customisation Guide\".\r\n\r\nAbout\r\nIf write \"/about\" you\'ll be presented an box publish.\r\n\r\nUser Preferences\r\nWe\'ve preference functionality. If take look into left hand column this site find links called \"intranet\", \"portal site\" \"E-commerce\". Those different designs (only two will shown at any time).\r\n\r\nAn example usage might give users option reading your amounts graphics, or text sizes.\r\n\r\nCookie-less Sessions\r\nWe\'ve sessions.\r\n\r\nModerated Forums\r\nWe\'ve much requested functionality forums. Now assign moderator every forum.\r\n\r\nUsage function might, addition plain old moderation, protect forums so that FAQ. ');
INSERT INTO eZArticle_Article VALUES (4,'How does static pages work?','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>Static pages are articles entered into the normal article system, but which belong to a category which has specific settings.</intro><body><page>All categories can have that special setting, which is called \"Exclude from search\". Not only does this disallow normal search functions, but those articles will not be shown in normal archive listings, nor latest article listings or the rss headlines.\r\n\r\nYou will neither see the name of the author of a static page; it is anonymous to the reader.\r\n\r\n<header>Intended Usage</header>\r\n\r\nThe usage of such pages is intended to create copyright notices, address info and other static information; thus the name.\r\n\r\nThat doesn\'t exclude any or all other methods you would like to use, but this is how we use it.\r\n\r\n<header>Tricks of the Trade</header>\r\n\r\nA category listing for static pages might be used on the front page; when you add a new page it will be added to the menu.\r\n\r\nBy changing the category sort method to \"Absolute positioning\" you can order the rendering of the menu to suit your desires.\r\n\r\nYou could also create several static page groups, and use those to good effect to distinguish information.\r\n\r\nFor all other intents and purposes articles written as static pages are the same as normal articles.</page></body></article>','admin user','',27,20010126102509,20010126101612,1,'true',20010126101612,'tech\nStatic pages are articles entered into the normal article system, but which belong to a category has specific settings.All categories can have that special setting, is called \"Exclude from search\". Not only does this disallow search functions, those will not be shown in archive listings, nor latest listings or rss headlines.\r\n\r\nYou neither see name of author static page; it anonymous reader.\r\n\r\nIntended Usage\r\n\r\nThe usage such intended create copyright notices, address info and other information; thus name.\r\n\r\nThat doesn\'t exclude any all methods you would like use, how we use it.\r\n\r\nTricks Trade\r\n\r\nA listing for might used on front when add new page added menu.\r\n\r\nBy changing sort method \"Absolute positioning\" order rendering menu suit your desires.\r\n\r\nYou could also several groups, good effect distinguish information.\r\n\r\nFor intents purposes written as same articles. ');
INSERT INTO eZArticle_Article VALUES (8,'eZ Trade','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>eZ Trade has had a couple of face lifts as well.</intro><body><page><header>Category Sorting</header>\r\nYou can set the sorting methods of both article and trade categories.\r\n\r\nAmong other sorting methods we\'ve added absolute positioning. This feature might be good for presenting a front page of your site where certain items appear at specific places.\r\n\r\nBy \"cross posting\" news and products to both their main category and the category with absolute positioning you can have items appear on the front page at a certain position within the time limit you want.\r\n\r\n<header>Types</header>\r\nYou can define a product type, where you set what kind of information that type requires. Then when creating a product you can set the type of the product and enter the required data.\r\n\r\nLooks great and can be used for comparision of features.\r\n\r\nCombine this with options for your products to create really compelling product pages.\r\n</page></body></article>','admin user','',27,20010126120506,20010126112654,1,'true',20010126112654,'tech\neZ Trade has had a couple of face lifts as well.Category Sorting\r\nYou can set the sorting methods both article and trade categories.\r\n\r\nAmong other we\'ve added absolute positioning. This feature might be good for presenting front page your site where certain items appear at specific places.\r\n\r\nBy \"cross posting\" news products to their main category with positioning you have on position within time limit want.\r\n\r\nTypes\r\nYou define product type, what kind information that type requires. Then when creating enter required data.\r\n\r\nLooks great used comparision features.\r\n\r\nCombine this options create really compelling pages.\r\n ');
INSERT INTO eZArticle_Article VALUES (6,'eZ Newsfeed','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>eZ newsfeed is a new module from 2.0. It fetches RSS headlines from other sites.</intro><body><page>The news feed is a module which fetches headlines from RSS enabled sites, pluss a couple of speciality sites.\r\n\r\nFetching RSS headlines is easy, just point eZ publish to the URL you want, and it will fetch the info into a queue. You can then select which items you want to publish from that queue.\r\n\r\nIt is possible to create your own fetch methods which fetches headlines from other sites. PHP programming required.</page></body></article>','admin user','',27,20010126112345,20010126111844,1,'true',20010126111844,'tech\neZ newsfeed is a new module from 2.0. It fetches RSS headlines other sites.The news feed which enabled sites, pluss couple of speciality sites.\r\n\r\nFetching easy, just point eZ publish to the URL you want, and it will fetch info into queue. You can then select items want that queue.\r\n\r\nIt possible create your own methods sites. PHP programming required. ');
INSERT INTO eZArticle_Article VALUES (7,'eZ Article','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>Some additions were made to eZ article, the main points are presented here.</intro><body><page><header>Article Comments</header>\r\nWhen readers comment on an article eZ publish will now send an e-mail to the user who published the article.\r\n\r\n<header>File Attachments</header>\r\nAs you can see from this article it is now possible to add files to an article; thus you can use the article for distributing files.\r\n\r\n<header>Category Sorting</header>\r\nYou can set the sorting methods of article categories.\r\n\r\nAmong other sorting methods we\'ve added absolute positioning. This feature might be good for presenting a front page of your site where certain items appear at specific places\r\n\r\n<header>Include Generated Content</header>\r\neZ Article now accepts a tag called module it takes a second argument, a file name, sans extension. The extension is assumed to be .php.\r\n\r\nThe article will parse and include that file from \"ezarticle/modules\". Thus you can create much fancier lay outs than what you\'d normally get from the standard renderer.</page></body></article>','admin user','',27,20010126125446,20010126112242,1,'true',20010126112242,'tech\nSome additions were made to eZ article, the main points are presented here.Article Comments\r\nWhen readers comment on an article publish will now send e-mail user who published article.\r\n\r\nFile Attachments\r\nAs you can see from this it is possible add files article; thus use for distributing files.\r\n\r\nCategory Sorting\r\nYou set sorting methods of categories.\r\n\r\nAmong other we\'ve added absolute positioning. This feature might be good presenting a front page your site where certain items appear at specific places\r\n\r\nInclude Generated Content\r\neZ Article accepts tag called module takes second argument, file name, sans extension. The extension assumed .php.\r\n\r\nThe parse and include that \"ezarticle/modules\". Thus create much fancier lay outs than what you\'d normally get standard renderer. ');
INSERT INTO eZArticle_Article VALUES (9,'What can eZ publish Do?','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>eZ publish is a web based application suite. It delivers functionality ranging from publishing of news, web logs and diaries, through web shop functionality like shopping carts and wishlists and forums to intranet functions like contact handling and bug reporting.\r\n\r\nThe software uses caching and other optimization techniques to speed up page serving. It handles users, user preferences and user tracking through a user database and both cookie-based and non-cookie sessions.\r\n\r\nIt supports statistics for page views, links followed and banner ads, both images and HTML with presentation logic.\r\n\r\nThe package lends itself easily to customization, from changing the look and feel by changing templates, localizing the languages and other internationalization issues to adding new functionality.\r\n\r\nThe target audience for eZ publish are e-commerce, ASP (Application Service Providers), BSP (Business Service Providers), news publishing, intranets, bug reporting, content management, discussion boards, FAQ and knowledge handling, file and image management, group ware, calendaring, polls, todo lists, appointments as well as personal web sites.\r\n</intro><body><page>eZ publish is a web based application suite which delivers the following functionality:\r\n\r\n<bullet>Advertising with statistics\r\nArticle publication and management\r\nBug handling and reporting\r\nCalendar functionality for creating appointments and events\r\nContact handling for keeping track of people and businesses\r\nFile manager for keeping track of uploaded files\r\nModerated forums for discussions\r\nImage manager for keeping track of uploaded images\r\nLink manager which is used to categorize links\r\nNews feed importing, fetch news and headlines from other sites and incorporate them in your own(1)\r\nPoll module for creating user polls.\r\nSession module for keeping track of users and their preferences\r\nStatistics module for information about page views and visitors\r\nTo-do module for assigning tasks to people\r\nTrade module which is an online shop, with shopping cart and wishlist\r\nUser management for registering users, giving access to different groups to different parts of the site</bullet>\r\n\r\nThe software does not believe in limits(2):\r\n\r\n<bullet>No limits on categories and items in categories\r\nArticles, products and links might belong to several different categories\r\nNo limits on people associated with a company, or the number of people and companies registered totally\r\nNo limits of addresses, phone numbers and other contact points for people and businesses\r\nNo limits on users, the groups they might belong to and number of user groups</bullet>\r\n      \r\nBased on PHP, the leading programming language module for the Apache web server software, eZ publish draws on the speed from this renown software. The backend database is MySQL which is fast and reliable, proven on thousands of Internet sites.\r\n\r\nFurther speed enhancements are made in the eZ publish by using caching of data and reduced connections to the database.\r\n\r\nAll the default templates delivered with eZ publish are tested on a diverse mix of browsers, Opera, Internet Explorer, Netscape, Konqueror and Lynx, thus enabling all users to gain access to your site.\r\n\r\nSo called cookie-less sessions are supported, a method used to enable user recognition even for those who objects to use cookies, no-one will feel left out or overseen.\r\n\r\neZ publish keeps track of the statistics of your site. How many visitors, from where, what do they buy and what are they looking at.\r\n\r\nThe package has been translated to several languages, you can even translate it yourself through the eZ Babel software we\'ve developed for this purpose specifically.\r\n\r\nChanging the design of your site is easy because of separation of content and design. You don\'t have to know anything about PHP or coding, just something about HTML.\r\n\r\nFor those proficient in programming PHP the source code is available, it can be used as a basis for adding new modules and functionality tailored to your specific needs.\r\n\r\nThe target audience for eZ publish are e-commerce, ASP (Application Service Providers), news publishing, intranets, bug reporting, content management, discussion boards, FAQ and knowledge handling, file and image management, group ware, calendaring, appointments.\r\n\r\n     \r\n(1) We do not encourage copyright infringements with this feature. Our default templates will not pass these news items as the site\'s own. \r\n\r\nAsk permission from copyright holder before publishing other site\'s news on your site.\r\n\r\n(2) There are limits, of course, since the system is based on other software, and because it will run on systems with different sizes of hard disks and ram, as well as processor speed.\r\n</page></body></article>','admin user','',27,20010126121313,20010126115247,1,'true',20010126115247,'tech\neZ publish is a web based application suite. It delivers functionality ranging from publishing of news, logs and diaries, through shop like shopping carts wishlists forums to intranet functions contact handling bug reporting.\r\n\r\nThe software uses caching other optimization techniques speed up page serving. handles users, user preferences tracking database both cookie-based non-cookie sessions.\r\n\r\nIt supports statistics for views, links followed banner ads, images HTML with presentation logic.\r\n\r\nThe package lends itself easily customization, changing the look feel by templates, localizing languages internationalization issues adding new functionality.\r\n\r\nThe target audience eZ are e-commerce, ASP (Application Service Providers), BSP (Business news publishing, intranets, reporting, content management, discussion boards, FAQ knowledge handling, file image group ware, calendaring, polls, todo lists, appointments as well personal sites.\r\neZ suite which following functionality:\r\n\r\nAdvertising statistics\r\nArticle publication management\r\nBug reporting\r\nCalendar creating events\r\nContact keeping track people businesses\r\nFile manager uploaded files\r\nModerated discussions\r\nImage images\r\nLink used categorize links\r\nNews feed importing, fetch headlines sites incorporate them in your own(1)\r\nPoll module polls.\r\nSession users their preferences\r\nStatistics information about views visitors\r\nTo-do assigning tasks people\r\nTrade an online shop, cart wishlist\r\nUser management registering giving access different groups parts site\r\n\r\nThe does not believe limits(2):\r\n\r\nNo limits on categories items categories\r\nArticles, products might belong several categories\r\nNo associated company, or number companies registered totally\r\nNo addresses, phone numbers points businesses\r\nNo they groups\r\n  \r\nBased PHP, leading programming language Apache server software, draws this renown software. The backend MySQL fast reliable, proven thousands Internet sites.\r\n\r\nFurther enhancements made using data reduced connections database.\r\n\r\nAll default templates delivered tested diverse mix browsers, Opera, Explorer, Netscape, Konqueror Lynx, thus enabling all gain site.\r\n\r\nSo called cookie-less sessions supported, method enable recognition even those who objects use cookies, no-one will left out overseen.\r\n\r\neZ keeps site. How many visitors, where, what do buy looking at.\r\n\r\nThe has been translated languages, you can translate it yourself Babel we\'ve developed purpose specifically.\r\n\r\nChanging design site easy because separation design. You don\'t have know anything PHP coding, just something HTML.\r\n\r\nFor proficient source code available, be basis modules tailored specific needs.\r\n\r\nThe appointments.\r\n\r\n \r\n(1) We encourage copyright infringements feature. Our pass these site\'s own. \r\n\r\nAsk permission holder before site.\r\n\r\n(2) There limits, course, since system run systems sizes hard disks ram, processor speed.\r\n ');

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

INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (6,1,1);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (12,5,1);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (10,6,1);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (4,4,2);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (17,7,1);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (15,8,1);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (16,9,1);

#
# Table structure for table 'eZArticle_ArticleCategoryLink'
#
DROP TABLE IF EXISTS eZArticle_ArticleCategoryLink;
CREATE TABLE eZArticle_ArticleCategoryLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ArticleID int(11) DEFAULT '0' NOT NULL,
  CategoryID int(11) DEFAULT '0' NOT NULL,
  Placement int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZArticle_ArticleCategoryLink'
#

INSERT INTO eZArticle_ArticleCategoryLink VALUES (6,1,1,15);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (14,5,1,10);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (7,1,4,7);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (4,4,2,4);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (8,1,3,8);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (12,6,1,18);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (19,7,1,20);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (18,9,1,13);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (17,8,1,19);

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

INSERT INTO eZArticle_ArticleFileLink VALUES (1,5,1,20010126103230);

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

INSERT INTO eZArticle_ArticleForumLink VALUES (1,1,2);
INSERT INTO eZArticle_ArticleForumLink VALUES (2,5,3);
INSERT INTO eZArticle_ArticleForumLink VALUES (3,9,4);
INSERT INTO eZArticle_ArticleForumLink VALUES (4,6,5);
INSERT INTO eZArticle_ArticleForumLink VALUES (5,7,6);

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

INSERT INTO eZArticle_ArticleImageDefinition VALUES (4,20);
INSERT INTO eZArticle_ArticleImageDefinition VALUES (1,1);

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

INSERT INTO eZArticle_ArticleImageLink VALUES (1,1,1,20010126100427);
INSERT INTO eZArticle_ArticleImageLink VALUES (2,1,2,20010126100445);

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
  Placement int(11) default '0',
  SortMode int(11) DEFAULT '1' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZArticle_Category'
#

INSERT INTO eZArticle_Category VALUES (1,'News','',0,'false',4);
INSERT INTO eZArticle_Category VALUES (2,'Static pages','',0,'true',4);
INSERT INTO eZArticle_Category VALUES (3,'Category three','',0,'false',3);
INSERT INTO eZArticle_Category VALUES (4,'Category four','',0,'false',3);

#
# Table structure for table 'eZArticle_BulkMailCategoryLink'
#
DROP TABLE IF EXISTS eZArticle_BulkMailCategoryLink;
CREATE TABLE eZArticle_BulkMailCategoryLink (
  ArticleCategoryID int(11) DEFAULT '0' NOT NULL,
  BulkMailCategoryID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ArticleCategoryID, BulkMailCategoryID)
);
