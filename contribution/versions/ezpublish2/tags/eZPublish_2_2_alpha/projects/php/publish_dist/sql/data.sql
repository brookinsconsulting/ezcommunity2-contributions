# MySQL dump 8.12
#
# Host: localhost    Database: trade
#--------------------------------------------------------
# Server version	3.23.32

#
# Dumping data for table 'eZAd_Ad'
#

INSERT INTO eZAd_Ad VALUES (1,'eZ publish banner',10,00000000000000,00000000000000,'','http://ez.no','','true',2.00,3.00,'',0);
INSERT INTO eZAd_Ad VALUES (2,'eZ hostig banner',11,00000000000000,00000000000000,'','http://developer.ez.no','','true',2.00,3.00,'',0);

#
# Dumping data for table 'eZAd_AdCategoryLink'
#

INSERT INTO eZAd_AdCategoryLink VALUES (1,1,1);
INSERT INTO eZAd_AdCategoryLink VALUES (2,1,2);

#
# Dumping data for table 'eZAd_Category'
#

INSERT INTO eZAd_Category VALUES (1,'Banner ads','For rotation',0,'false');

#
# Dumping data for table 'eZAd_Click'
#

INSERT INTO eZAd_Click VALUES (1,2,414,3.00);

#
# Dumping data for table 'eZAd_View'
#

INSERT INTO eZAd_View VALUES (1,1,'2001-03-09',75,150);
INSERT INTO eZAd_View VALUES (2,2,'2001-03-09',75,150);
INSERT INTO eZAd_View VALUES (3,1,'2001-04-20',1,2);
INSERT INTO eZAd_View VALUES (4,2,'2001-04-20',1,2);

#
# Dumping data for table 'eZAddress_Address'
#

INSERT INTO eZAddress_Address VALUES (1,'Street1','Street2',0,'Skien','007',162);

#
# Dumping data for table 'eZAddress_AddressDefinition'
#

INSERT INTO eZAddress_AddressDefinition VALUES (1,1);

#
# Dumping data for table 'eZAddress_AddressType'
#

INSERT INTO eZAddress_AddressType VALUES (1,'Home address',1,0);
INSERT INTO eZAddress_AddressType VALUES (2,'Billing address',2,0);

#
# Dumping data for table 'eZAddress_Country'
#

#
# Dumping data for table 'eZAddress_Online'
#


#
# Dumping data for table 'eZAddress_OnlineType'
#

INSERT INTO eZAddress_OnlineType VALUES (1,'Email',1,'mailto:',0,0,0);

#
# Dumping data for table 'eZAddress_Phone'
#


#
# Dumping data for table 'eZAddress_PhoneType'
#

INSERT INTO eZAddress_PhoneType VALUES (1,'Phone',1,0);
INSERT INTO eZAddress_PhoneType VALUES (2,'Fax',2,0);

#
# Dumping data for table 'eZArticle_Article'
#

INSERT INTO eZArticle_Article VALUES (1,'What can eZ publish Do?','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>eZ publish is a web based application suite. It delivers functionality ranging from publishing of news, web logs and diaries, through web shop functionality like shopping carts and wishlists and forums to intranet functions like contact handling and bug reporting.\r\n\r\nThe software uses caching and other optimization techniques to speed up page serving. It handles users, user preferences and user tracking through a user database and both cookie-based and non-cookie sessions.\r\n\r\nIt supports statistics for page views, links followed and banner ads, both images and HTML with presentation logic.\r\n\r\nThe package lends itself easily to customization, from changing the look and feel by changing templates, localizing the languages and other internationalization issues to adding new functionality.\r\n\r\nThe target audience for eZ publish are e-commerce, ASP (Application Service Providers), BSP (Business Service Providers), news publishing, intranets, bug reporting, content management, discussion boards, FAQ and knowledge handling, file and image management, group ware, calendaring, polls, todo lists, appointments as well as personal web sites.</intro><body><page><image id=\"1\" align=\"right\" size=\"medium\" />eZ publish is a web based application suite which delivers the following functionality:\r\n\r\n<bullet>Advertising with statistics\r\nArticle publication and management\r\nBug handling and reporting\r\nCalendar functionality for creating appointments and events\r\nContact handling for keeping track of people and businesses\r\nFile manager for keeping track of uploaded files\r\nModerated forums for discussions\r\nImage manager for keeping track of uploaded images\r\nLink manager which is used to categorize links\r\nNews feed importing, fetch news and headlines from other sites and incorporate them in your own(1)\r\nPoll module for creating user polls.\r\nSession module for keeping track of users and their preferences\r\nStatistics module for information about page views and visitors\r\nTo-do module for assigning tasks to people\r\nTrade module which is an online shop, with shopping cart and wishlist\r\nUser management for registering users, giving access to different groups to different parts of the site</bullet>\r\n\r\nThe software does not believe in limits(2):\r\n\r\n<bullet>No limits on categories and items in categories\r\nArticles, products and links might belong to several different categories\r\nNo limits on people associated with a company, or the number of people and companies registered totally\r\nNo limits of addresses, phone numbers and other contact points for people and businesses\r\nNo limits on users, the groups they might belong to and number of user groups</bullet>\r\n      \r\nBased on PHP, the leading programming language module for the Apache web server software, eZ publish draws on the speed from this renown software. The backend database is MySQL which is fast and reliable, proven on thousands of Internet sites.\r\n\r\nFurther speed enhancements are made in the eZ publish by using caching of data and reduced connections to the database.\r\n\r\nAll the default templates delivered with eZ publish are tested on a diverse mix of browsers, Opera, Internet Explorer, Netscape, Konqueror and Lynx, thus enabling all users to gain access to your site.\r\n\r\nSo called cookie-less sessions are supported, a method used to enable user recognition even for those who objects to use cookies, no-one will feel left out or overseen.\r\n\r\neZ publish keeps track of the statistics of your site. How many visitors, from where, what do they buy and what are they looking at.\r\n\r\nThe package has been translated to several languages, you can even translate it yourself through the eZ Babel software we\'ve developed for this purpose specifically.\r\n\r\nChanging the design of your site is easy because of separation of content and design. You don\'t have to know anything about PHP or coding, just something about HTML.\r\n\r\nFor those proficient in programming PHP the source code is available, it can be used as a basis for adding new modules and functionality tailored to your specific needs.\r\n\r\nThe target audience for eZ publish are e-commerce, ASP (Application Service Providers), news publishing, intranets, bug reporting, content management, discussion boards, FAQ and knowledge handling, file and image management, group ware, calendaring, appointments.\r\n\r\n     \r\n(1) We do not encourage copyright infringements with this feature. Our default templates will not pass these news items as the site\'s own. \r\n\r\nAsk permission from copyright holder before publishing other site\'s news on your site.\r\n\r\n(2) There are limits, of course, since the system is based on other software, and because it will run on systems with different sizes of hard disks and ram, as well as processor speed.</page></body></article>','admin user','Read more',1,20010308195442,20010308194825,1,'true',20010308194826,'tech\neZ publish is a web based application suite. It delivers functionality ranging from publishing of news, logs and diaries, through shop like shopping carts wishlists forums to intranet functions contact handling bug reporting.\r\n\r\nThe software uses caching other optimization techniques speed up page serving. handles users, user preferences tracking database both cookie-based non-cookie sessions.\r\n\r\nIt supports statistics for views, links followed banner ads, images HTML with presentation logic.\r\n\r\nThe package lends itself easily customization, changing the look feel by templates, localizing languages internationalization issues adding new functionality.\r\n\r\nThe target audience eZ are e-commerce, ASP (Application Service Providers), BSP (Business news publishing, intranets, reporting, content management, discussion boards, FAQ knowledge handling, file image group ware, calendaring, polls, todo lists, appointments as well personal sites.eZ suite which following functionality:\r\n\r\nAdvertising statistics\r\nArticle publication management\r\nBug reporting\r\nCalendar creating events\r\nContact keeping track people businesses\r\nFile manager uploaded files\r\nModerated discussions\r\nImage images\r\nLink used categorize links\r\nNews feed importing, fetch headlines sites incorporate them in your own(1)\r\nPoll module polls.\r\nSession users their preferences\r\nStatistics information about views visitors\r\nTo-do assigning tasks people\r\nTrade an online shop, cart wishlist\r\nUser management registering giving access different groups parts site\r\n\r\nThe does not believe limits(2):\r\n\r\nNo limits on categories items categories\r\nArticles, products might belong several categories\r\nNo associated company, or number companies registered totally\r\nNo addresses, phone numbers points businesses\r\nNo they groups\r\n  \r\nBased PHP, leading programming language Apache server software, draws this renown software. The backend MySQL fast reliable, proven thousands Internet sites.\r\n\r\nFurther enhancements made using data reduced connections database.\r\n\r\nAll default templates delivered tested diverse mix browsers, Opera, Explorer, Netscape, Konqueror Lynx, thus enabling all gain site.\r\n\r\nSo called cookie-less sessions supported, method enable recognition even those who objects use cookies, no-one will left out overseen.\r\n\r\neZ keeps site. How many visitors, where, what do buy looking at.\r\n\r\nThe has been translated languages, you can translate it yourself Babel we\'ve developed purpose specifically.\r\n\r\nChanging design site easy because separation design. You don\'t have know anything PHP coding, just something HTML.\r\n\r\nFor proficient source code available, be basis modules tailored specific needs.\r\n\r\nThe appointments.\r\n\r\n \r\n(1) We encourage copyright infringements feature. Our pass these site\'s own. \r\n\r\nAsk permission holder before site.\r\n\r\n(2) There limits, course, since system run systems sizes hard disks ram, processor speed. ',0,'');
INSERT INTO eZArticle_Article VALUES (2,'What is New in 2.0?','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>This is a major new release of eZ publish, we\'ve added lots of new information.</intro><body><page><bullet>Merged eZ trade with eZ publish\r\nAdded about module\r\nAdded ad module\r\nAdded address module\r\nAdded bug tracking module\r\nAdded calendar module\r\nAdded contact module\r\nAdded newsfeed module\r\nAdded statistics module\r\nAdded todo module\r\nAdded cookie-less sessions \r\nAdded absolute positioning of products and articles\r\nAdded choosable sort mode on article categories\r\nAdded choosable sort mode on product categories\r\nAdded previous/next paging of article lists (admin &amp; user )\r\nAdded previous/next paging of product lists (admin &amp; user )\r\nAdded RSS headlines\r\nAdded forum moderation with assignment of moderator\r\nAdded notification when articles are published\r\nAdded file uploads on articles.\r\nAdded dynamically updating of menues with static pages.\r\nAdded file upload to eZ article\r\nAdded word wrap of message replies in eZ forum. Nicer looking replies.\r\nAdded new tags in articles (bullet lists/includes of php files)\r\nAdded preferred layout for users\r\nMade the menus in the admin module expandable/collapsable as well as moveable. This is remembered by the preferences for each user. We\'ve also changed the design to a more sleek version.\r\nLanguage updates\r\nRemoved java script which were a problem for lynx users.\r\nRemoved strip tags from messages in eZ forum\r\nSpeeded up many features among them database connections, localisation, rendering of articles, templates and HTML.\r\nFixed bugs</bullet>\r\n\r\n\r\nRead on to learn how to use some of the new features.\r\n</page><page>\r\n<header>RSS Headlines</header>\r\nYou can access the RSS Headlines of eZ publish from the URL \"/article/rssheadlines\".\r\n\r\nIn \"site.ini\" you can configure some of its options; read more in the \"eZ article Admin\'s Guide\" and \"eZ publish Customisation Guide\".\r\n\r\n<header>About</header>\r\nIf you write in the URL \"/about\" you\'ll be presented with an about box for eZ publish.\r\n\r\n<header>User Preferences</header>\r\nWe\'ve added preference functionality. If you take a look into the left hand column of this site you\'ll find some links which are called \"intranet\", \"portal site\" and \"E-commerce\". Those links take you to different designs for eZ publish (only two links will be shown at any time).\r\n\r\nAn example of its usage might be to give users the option of reading your site with different amounts of graphics, or different text sizes.\r\n\r\n<header>Cookie-less Sessions</header>\r\nWe\'ve added cookie-less sessions.\r\n\r\n<header>Moderated Forums</header>\r\nWe\'ve added the much requested moderation functionality to forums. Now you can assign a moderator to each and every forum.\r\n\r\nUsage for this function might, in addition to plain old moderation, is to protect forums so that you can use them as an FAQ.</page></body></article>','admin user','Read the changelog...',1,20010308195616,20010308195616,2,'true',20010308195616,'tech\nThis is a major new release of eZ publish, we\'ve added lots information.Merged trade with publish\r\nAdded about module\r\nAdded ad address bug tracking calendar contact newsfeed statistics todo cookie-less sessions \r\nAdded absolute positioning products and articles\r\nAdded choosable sort mode on article categories\r\nAdded product previous/next paging lists (admin &amp; user )\r\nAdded RSS headlines\r\nAdded forum moderation assignment moderator\r\nAdded notification when articles are published\r\nAdded file uploads articles.\r\nAdded dynamically updating menues static pages.\r\nAdded upload to article\r\nAdded word wrap message replies in forum. Nicer looking replies.\r\nAdded tags (bullet lists/includes php files)\r\nAdded preferred layout for users\r\nMade the menus admin module expandable/collapsable as well moveable. This remembered by preferences each user. We\'ve also changed design more sleek version.\r\nLanguage updates\r\nRemoved java script which were problem lynx users.\r\nRemoved strip from messages forum\r\nSpeeded up many features among them database connections, localisation, rendering articles, templates HTML.\r\nFixed bugs\r\n\r\n\r\nRead learn how use some features.\r\n\r\nRSS Headlines\r\nYou can access Headlines publish URL \"/article/rssheadlines\".\r\n\r\nIn \"site.ini\" you configure its options; read \"eZ Admin\'s Guide\" Customisation Guide\".\r\n\r\nAbout\r\nIf write \"/about\" you\'ll be presented an box publish.\r\n\r\nUser Preferences\r\nWe\'ve preference functionality. If take look into left hand column this site find links called \"intranet\", \"portal site\" \"E-commerce\". Those different designs (only two will shown at any time).\r\n\r\nAn example usage might give users option reading your amounts graphics, or text sizes.\r\n\r\nCookie-less Sessions\r\nWe\'ve sessions.\r\n\r\nModerated Forums\r\nWe\'ve much requested functionality forums. Now assign moderator every forum.\r\n\r\nUsage function might, addition plain old moderation, protect forums so that FAQ. ',0,'');
INSERT INTO eZArticle_Article VALUES (3,'Demo article','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>This article will show the tags you can use in eZ publish.</intro><body><page><header>Standard tags</header>\r\n\r\nThis is <bold>bold</bold> text.\r\nThis is <strike>strike</strike> text.\r\nThis is <underline>underline</underline> text.\r\n\r\n<pre>\r\nPre defined text\r\n  indented\r\n    as \r\n      written.\r\n</pre>\r\n<bullet>\r\nItem one\r\nItem two\r\nItem three\r\n</bullet>\r\n\r\n<header>Image tags</header>\r\n\r\n<image id=\"1\" align=\"left\" size=\"medium\" /> Fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text.\r\n\r\n<image id=\"2\" align=\"center\" size=\"medium\" />\r\n\r\nImages on a row\r\n\r\n<image id=\"1\" align=\"float\" size=\"small\" /> <image id=\"3\" align=\"float\" size=\"small\" /> <image id=\"1\" align=\"float\" size=\"small\" /> <image id=\"2\" align=\"float\" size=\"small\" /></page></body></article>','admin user','See demo',1,20010308195827,20010308195722,1,'true',20010308195722,'tech\nThis article will show the tags you can use in eZ publish.Standard tags\r\n\r\nThis is bold text.\r\nThis strike underline text.\r\n\r\n\r\nPre defined text\r\n  indented\r\n as \r\n written.\r\n\r\n\r\nItem one\r\nItem two\r\nItem three\r\n\r\n\r\nImage tags\r\n\r\n Fill text fill text.\r\n\r\n\r\n\r\nImages on a row\r\n\r\n ',0,'');
INSERT INTO eZArticle_Article VALUES (4,'Static 1','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>This is a static page..</intro><body><page>Nothing special about it..</page></body></article>','admin user','Static one',1,20010308200154,20010308200154,1,'true',20010308200154,'tech\nThis is a static page..Nothing special about it.. ',0,'');

#
# Dumping data for table 'eZArticle_ArticleCategoryDefinition'
#

INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (3,1,1);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (4,2,1);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (6,3,1);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (7,4,2);

#
# Dumping data for table 'eZArticle_ArticleCategoryLink'
#

INSERT INTO eZArticle_ArticleCategoryLink VALUES (1,1,1,1);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (2,2,1,2);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (3,3,1,3);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (4,4,2,1);

#
# Dumping data for table 'eZArticle_ArticleFileLink'
#


#
# Dumping data for table 'eZArticle_ArticleForumLink'
#

INSERT INTO eZArticle_ArticleForumLink VALUES (1,1,1);
INSERT INTO eZArticle_ArticleForumLink VALUES (2,3,2);
INSERT INTO eZArticle_ArticleForumLink VALUES (3,2,6);

#
# Dumping data for table 'eZArticle_ArticleImageDefinition'
#

INSERT INTO eZArticle_ArticleImageDefinition VALUES (1,2);
INSERT INTO eZArticle_ArticleImageDefinition VALUES (3,3);

#
# Dumping data for table 'eZArticle_ArticleImageLink'
#

INSERT INTO eZArticle_ArticleImageLink VALUES (2,1,2,20010308195506);
INSERT INTO eZArticle_ArticleImageLink VALUES (3,3,3,20010308195734);
INSERT INTO eZArticle_ArticleImageLink VALUES (4,3,4,20010308195747);
INSERT INTO eZArticle_ArticleImageLink VALUES (5,3,5,20010308195801);

#
# Dumping data for table 'eZArticle_ArticleKeyword'
#


#
# Dumping data for table 'eZArticle_ArticlePermission'
#

INSERT INTO eZArticle_ArticlePermission VALUES (1,1,-1,1,1);
INSERT INTO eZArticle_ArticlePermission VALUES (2,2,-1,1,1);
INSERT INTO eZArticle_ArticlePermission VALUES (3,3,-1,1,1);
INSERT INTO eZArticle_ArticlePermission VALUES (4,4,-1,1,1);

#
# Dumping data for table 'eZArticle_Category'
#

INSERT INTO eZArticle_Category VALUES (1,'News','This is news, fresh from the press.',0,'false',1,1,1,1,0);
INSERT INTO eZArticle_Category VALUES (2,'Static pages','Theese are static pages.',0,'true',4,1,2,1,0);

#
# Dumping data for table 'eZArticle_CategoryPermission'
#

INSERT INTO eZArticle_CategoryPermission VALUES (1,1,1,0,1);
INSERT INTO eZArticle_CategoryPermission VALUES (2,1,-1,1,0);
INSERT INTO eZArticle_CategoryPermission VALUES (3,2,1,0,1);
INSERT INTO eZArticle_CategoryPermission VALUES (4,2,-1,1,0);

#
# Dumping data for table 'eZArticle_CategoryReaderLink'
#


#
# Dumping data for table 'eZBug_Bug'
#

INSERT INTO eZBug_Bug VALUES (3,'A new bug!','Oh yeah!',1,20010309105134,'true',5,6,'false','',0,'false','');

#
# Dumping data for table 'eZBug_BugCategoryLink'
#

INSERT INTO eZBug_BugCategoryLink VALUES (5,1,0);
INSERT INTO eZBug_BugCategoryLink VALUES (9,10,3);

#
# Dumping data for table 'eZBug_BugFileLink'
#

INSERT INTO eZBug_BugFileLink VALUES (2,3,4,20010309105410);
INSERT INTO eZBug_BugFileLink VALUES (5,3,7,20010309110904);

#
# Dumping data for table 'eZBug_BugImageLink'
#

INSERT INTO eZBug_BugImageLink VALUES (3,3,14,20010309105152);

#
# Dumping data for table 'eZBug_BugModuleLink'
#

INSERT INTO eZBug_BugModuleLink VALUES (5,1,0);
INSERT INTO eZBug_BugModuleLink VALUES (9,1,3);

#
# Dumping data for table 'eZBug_Category'
#

INSERT INTO eZBug_Category VALUES (10,'Critical','');
INSERT INTO eZBug_Category VALUES (11,'Wish/Feature request','');
INSERT INTO eZBug_Category VALUES (9,'Grave','');
INSERT INTO eZBug_Category VALUES (8,'Normal','');

#
# Dumping data for table 'eZBug_Log'
#

INSERT INTO eZBug_Log VALUES (1,3,1,'A log for this bug!',20010309111452);

#
# Dumping data for table 'eZBug_Module'
#

INSERT INTO eZBug_Module VALUES (1,0,'MyProgram','',0);
INSERT INTO eZBug_Module VALUES (2,1,'MySubModule','',0);

#
# Dumping data for table 'eZBug_ModulePermission'
#


#
# Dumping data for table 'eZBug_Priority'
#

INSERT INTO eZBug_Priority VALUES (6,'Normal',NULL);
INSERT INTO eZBug_Priority VALUES (5,'High',NULL);
INSERT INTO eZBug_Priority VALUES (7,'Low',NULL);

#
# Dumping data for table 'eZBug_Status'
#

INSERT INTO eZBug_Status VALUES (8,'Future addition');
INSERT INTO eZBug_Status VALUES (7,'Won\'t fix');
INSERT INTO eZBug_Status VALUES (6,'Fixed');
INSERT INTO eZBug_Status VALUES (9,'Works here');

#
# Dumping data for table 'eZBulkMail_Category'
#


#
# Dumping data for table 'eZBulkMail_Mail'
#


#
# Dumping data for table 'eZBulkMail_MailCategoryLink'
#


#
# Dumping data for table 'eZBulkMail_MailTemplateLink'
#


#
# Dumping data for table 'eZBulkMail_SentLog'
#


#
# Dumping data for table 'eZBulkMail_SubscriptionAddress'
#


#
# Dumping data for table 'eZBulkMail_SubscriptionLink'
#


#
# Dumping data for table 'eZBulkMail_Template'
#


#
# Dumping data for table 'eZCalendar_Appointment'
#

INSERT INTO eZCalendar_Appointment VALUES (1,1,20010308120000,'04:00:00',2,0,0,'Lunch with a client','Eating lobster.',0);
INSERT INTO eZCalendar_Appointment VALUES (2,1,20010309090000,'06:00:00',1,0,0,'Meeting with a client','Important meeting with the most important client.',0);
INSERT INTO eZCalendar_Appointment VALUES (3,1,20010314110000,'01:00:00',1,0,0,'Meeting 1','',0);
INSERT INTO eZCalendar_Appointment VALUES (4,1,20010314123000,'03:30:00',1,0,0,'Meeting 2','',0);
INSERT INTO eZCalendar_Appointment VALUES (5,1,20010314113000,'02:30:00',2,0,0,'Lunch','Crashing appointment',0);

#
# Dumping data for table 'eZCalendar_AppointmentType'
#

INSERT INTO eZCalendar_AppointmentType VALUES (1,0,'This is a meeting.','Meeting');
INSERT INTO eZCalendar_AppointmentType VALUES (2,0,'eating','Lunch');

#
# Dumping data for table 'eZContact_Company'
#


#
# Dumping data for table 'eZContact_CompanyAddressDict'
#


#
# Dumping data for table 'eZContact_CompanyImageDefinition'
#


#
# Dumping data for table 'eZContact_CompanyOnlineDict'
#


#
# Dumping data for table 'eZContact_CompanyPersonDict'
#


#
# Dumping data for table 'eZContact_CompanyPhoneDict'
#


#
# Dumping data for table 'eZContact_CompanyProjectDict'
#


#
# Dumping data for table 'eZContact_CompanyType'
#

INSERT INTO eZContact_CompanyType VALUES (1,'Firms','',0,0);

#
# Dumping data for table 'eZContact_CompanyTypeDict'
#

INSERT INTO eZContact_CompanyTypeDict VALUES (1,1);

#
# Dumping data for table 'eZContact_CompanyView'
#


#
# Dumping data for table 'eZContact_ConsulationCompanyDict'
#


#
# Dumping data for table 'eZContact_Consultation'
#


#
# Dumping data for table 'eZContact_ConsultationCompanyUserDict'
#


#
# Dumping data for table 'eZContact_ConsultationGroupsDict'
#


#
# Dumping data for table 'eZContact_ConsultationPersonUserDict'
#


#
# Dumping data for table 'eZContact_ConsultationType'
#

INSERT INTO eZContact_ConsultationType VALUES (1,'Phone call',1);

#
# Dumping data for table 'eZContact_ContactType'
#


#
# Dumping data for table 'eZContact_Person'
#


#
# Dumping data for table 'eZContact_PersonAddressDict'
#


#
# Dumping data for table 'eZContact_PersonOnlineDict'
#


#
# Dumping data for table 'eZContact_PersonPhoneDict'
#


#
# Dumping data for table 'eZContact_PersonProjectDict'
#


#
# Dumping data for table 'eZContact_ProjectType'
#

INSERT INTO eZContact_ProjectType VALUES (1,'Large project',1);

#
# Dumping data for table 'eZContact_UserCompanyDict'
#


#
# Dumping data for table 'eZContact_UserPersonDict'
#


#
# Dumping data for table 'eZFileManager_File'
#

INSERT INTO eZFileManager_File VALUES (1,'eZ publish FAQ','The new FAQ..','phpF3zo9r','FAQ',1,1,1);
INSERT INTO eZFileManager_File VALUES (2,'eZ publish description','A description of eZ publish.','phpQ4cY9X','DESCRIPTION',1,1,1);
INSERT INTO eZFileManager_File VALUES (4,'A new file!','It\'s a file','phpgzPqbH','dummy.txt',1,1,0);
INSERT INTO eZFileManager_File VALUES (7,'Try again','And aaing','phpq0ZQEX','dummy.txt',1,1,0);

#
# Dumping data for table 'eZFileManager_FileFolderLink'
#

INSERT INTO eZFileManager_FileFolderLink VALUES (3,1,1);
INSERT INTO eZFileManager_FileFolderLink VALUES (4,2,2);

#
# Dumping data for table 'eZFileManager_FilePageViewLink'
#


#
# Dumping data for table 'eZFileManager_FilePermission'
#

INSERT INTO eZFileManager_FilePermission VALUES (1,1,-1,1,0);
INSERT INTO eZFileManager_FilePermission VALUES (2,1,1,0,1);
INSERT INTO eZFileManager_FilePermission VALUES (3,2,-1,1,0);
INSERT INTO eZFileManager_FilePermission VALUES (4,2,1,0,1);

#
# Dumping data for table 'eZFileManager_FileReadGroupLink'
#


#
# Dumping data for table 'eZFileManager_FileWriteGroupLink'
#


#
# Dumping data for table 'eZFileManager_Folder'
#

INSERT INTO eZFileManager_Folder VALUES (1,'Files for the people','Here you find lots of interesting files.',0,1,1,1);
INSERT INTO eZFileManager_Folder VALUES (2,'Other files','Bla',0,1,1,1);

#
# Dumping data for table 'eZFileManager_FolderPermission'
#

INSERT INTO eZFileManager_FolderPermission VALUES (1,1,-1,1,1);
INSERT INTO eZFileManager_FolderPermission VALUES (2,1,1,0,0);
INSERT INTO eZFileManager_FolderPermission VALUES (3,2,-1,0,0);
INSERT INTO eZFileManager_FolderPermission VALUES (4,2,1,1,1);

#
# Dumping data for table 'eZFileManager_FolderReadGroupLink'
#


#
# Dumping data for table 'eZFileManager_FolderWriteGroupLink'
#


#
# Dumping data for table 'eZForum_Category'
#

INSERT INTO eZForum_Category VALUES ('Discussion','Talk in here',0,1);

#
# Dumping data for table 'eZForum_Forum'
#

INSERT INTO eZForum_Forum VALUES ('What can eZ publish','',0,1,0,0,0,0);
INSERT INTO eZForum_Forum VALUES ('Demo article','',0,2,0,0,0,0);
INSERT INTO eZForum_Forum VALUES ('This is a forum','With anonymous posting',0,3,1,0,0,1);
INSERT INTO eZForum_Forum VALUES ('Login discussion','Here you must have an account',0,4,0,0,0,0);
INSERT INTO eZForum_Forum VALUES ('Admin forum','Here you must be admin to use this forum',0,5,0,0,1,0);
INSERT INTO eZForum_Forum VALUES ('What is New in 2.0?','',0,6,0,0,0,0);

#
# Dumping data for table 'eZForum_ForumCategoryLink'
#

INSERT INTO eZForum_ForumCategoryLink VALUES (1,3,1);
INSERT INTO eZForum_ForumCategoryLink VALUES (2,4,1);
INSERT INTO eZForum_ForumCategoryLink VALUES (3,5,1);

#
# Dumping data for table 'eZForum_Message'
#

INSERT INTO eZForum_Message VALUES (3,'First post!!','This is posted by anonymous.. I.e. not logged in person.',0,0,0,20010308200612,0,0,0,1,1,0);
INSERT INTO eZForum_Message VALUES (3,'Second Post','Posted by admin user.',1,0,0,20010308200745,1,1,0,2,1,0);
INSERT INTO eZForum_Message VALUES (5,'Admin post (first)','Just a test post..',1,0,0,20010308201027,2,2,0,3,1,0);
INSERT INTO eZForum_Message VALUES (2,'Article comment','Tjobing...',1,0,0,20010309092730,3,3,0,4,1,0);

#
# Dumping data for table 'eZImageCatalogue_Category'
#

INSERT INTO eZImageCatalogue_Category VALUES (1,'Images','Nature images',0,1,1,1);
INSERT INTO eZImageCatalogue_Category VALUES (2,'My category','This is a category only root can see',0,1,1,1);

#
# Dumping data for table 'eZImageCatalogue_CategoryPermission'
#

INSERT INTO eZImageCatalogue_CategoryPermission VALUES (1,1,-1,1,1);
INSERT INTO eZImageCatalogue_CategoryPermission VALUES (2,2,-1,0,0);
INSERT INTO eZImageCatalogue_CategoryPermission VALUES (3,2,1,0,0);

#
# Dumping data for table 'eZImageCatalogue_Image'
#

INSERT INTO eZImageCatalogue_Image VALUES (1,'','','','php8aZkiw.jpg','dscn0360.jpg',0,0,0);
INSERT INTO eZImageCatalogue_Image VALUES (2,'','','','phpeDaF5r.jpg','dscn0360.jpg',0,0,0);
INSERT INTO eZImageCatalogue_Image VALUES (3,'','','','phpGHNdLn.jpg','DSCN1760.JPG',0,0,0);
INSERT INTO eZImageCatalogue_Image VALUES (4,'','','','phpDurrJU.jpg','DSCN1354.JPG',0,0,0);
INSERT INTO eZImageCatalogue_Image VALUES (5,'','','','phpZ3x5hl.jpg','DSCN1728.JPG',0,0,0);
INSERT INTO eZImageCatalogue_Image VALUES (6,'FOo','Bar','Image','phpXvDcMd.jpg','DSCN1247.JPG',0,0,1);
INSERT INTO eZImageCatalogue_Image VALUES (7,'Nature','Image','here','phpQ0icAt.jpg','DSCN1354.JPG',0,0,1);
INSERT INTO eZImageCatalogue_Image VALUES (8,'Tjobing','foo','bar','phpGqOpL5.jpg','DSCN1722.JPG',0,0,1);
INSERT INTO eZImageCatalogue_Image VALUES (9,'','','','phpqAOCph.jpg','DSCN1750.JPG',0,0,0);
INSERT INTO eZImageCatalogue_Image VALUES (10,'','','','phppHtAY6.gif','ezpublish-longanim-banner.gif',0,0,0);
INSERT INTO eZImageCatalogue_Image VALUES (11,'','','','phpaXRB1d.gif','hosting-longanim-banner.gif',0,0,0);
INSERT INTO eZImageCatalogue_Image VALUES (14,'A new screenshot','Hooba','','phpglNn3c.png','snapshot1.png',0,0,0);

#
# Dumping data for table 'eZImageCatalogue_ImageCategoryLink'
#

INSERT INTO eZImageCatalogue_ImageCategoryLink VALUES (5,2,6);
INSERT INTO eZImageCatalogue_ImageCategoryLink VALUES (2,1,7);
INSERT INTO eZImageCatalogue_ImageCategoryLink VALUES (3,1,8);

#
# Dumping data for table 'eZImageCatalogue_ImagePermission'
#

INSERT INTO eZImageCatalogue_ImagePermission VALUES (1,6,-1,1,0);
INSERT INTO eZImageCatalogue_ImagePermission VALUES (2,7,-1,1,1);
INSERT INTO eZImageCatalogue_ImagePermission VALUES (3,8,-1,1,1);
INSERT INTO eZImageCatalogue_ImagePermission VALUES (4,6,1,0,1);

#
# Dumping data for table 'eZImageCatalogue_ImageVariation'
#

INSERT INTO eZImageCatalogue_ImageVariation VALUES (1,1,1,'ezimagecatalogue/catalogue/variations/1-150x150.jpg',150,113,'');
INSERT INTO eZImageCatalogue_ImageVariation VALUES (2,1,2,'ezimagecatalogue/catalogue/variations/1-200x200.jpg',200,150,'');
INSERT INTO eZImageCatalogue_ImageVariation VALUES (3,2,1,'ezimagecatalogue/catalogue/variations/2-150x150.jpg',150,112,'');
INSERT INTO eZImageCatalogue_ImageVariation VALUES (4,3,1,'ezimagecatalogue/catalogue/variations/3-150x150.jpg',150,112,'');
INSERT INTO eZImageCatalogue_ImageVariation VALUES (5,4,1,'ezimagecatalogue/catalogue/variations/4-150x150.jpg',150,112,'');
INSERT INTO eZImageCatalogue_ImageVariation VALUES (6,5,1,'ezimagecatalogue/catalogue/variations/5-150x150.jpg',150,112,'');
INSERT INTO eZImageCatalogue_ImageVariation VALUES (7,3,2,'ezimagecatalogue/catalogue/variations/3-200x200.jpg',200,150,'');
INSERT INTO eZImageCatalogue_ImageVariation VALUES (8,4,2,'ezimagecatalogue/catalogue/variations/4-200x200.jpg',200,150,'');
INSERT INTO eZImageCatalogue_ImageVariation VALUES (9,3,3,'ezimagecatalogue/catalogue/variations/3-100x100.jpg',100,75,'');
INSERT INTO eZImageCatalogue_ImageVariation VALUES (10,5,3,'ezimagecatalogue/catalogue/variations/5-100x100.jpg',100,75,'');
INSERT INTO eZImageCatalogue_ImageVariation VALUES (11,4,3,'ezimagecatalogue/catalogue/variations/4-100x100.jpg',100,75,'');
INSERT INTO eZImageCatalogue_ImageVariation VALUES (12,3,4,'ezimagecatalogue/catalogue/variations/3-400x500.jpg',400,300,'');
INSERT INTO eZImageCatalogue_ImageVariation VALUES (20,9,1,'ezimagecatalogue/catalogue/variations/9-150x150.jpg',150,112,'');
INSERT INTO eZImageCatalogue_ImageVariation VALUES (14,7,1,'ezimagecatalogue/catalogue/variations/7-150x150.jpg',150,112,'');
INSERT INTO eZImageCatalogue_ImageVariation VALUES (15,8,1,'ezimagecatalogue/catalogue/variations/8-150x150.jpg',150,112,'');
INSERT INTO eZImageCatalogue_ImageVariation VALUES (16,8,4,'ezimagecatalogue/catalogue/variations/8-400x500.jpg',400,300,'');
INSERT INTO eZImageCatalogue_ImageVariation VALUES (19,6,1,'ezimagecatalogue/catalogue/variations/6-150x150.jpg',150,112,'');
INSERT INTO eZImageCatalogue_ImageVariation VALUES (21,9,5,'ezimagecatalogue/catalogue/variations/9-109x109.jpg',109,81,'');
INSERT INTO eZImageCatalogue_ImageVariation VALUES (22,9,6,'ezimagecatalogue/catalogue/variations/9-300x300.jpg',300,225,'');
INSERT INTO eZImageCatalogue_ImageVariation VALUES (23,9,7,'ezimagecatalogue/catalogue/variations/9-240x200.jpg',240,180,'');
INSERT INTO eZImageCatalogue_ImageVariation VALUES (24,9,8,'ezimagecatalogue/catalogue/variations/9-35x35.jpg',35,26,'');
INSERT INTO eZImageCatalogue_ImageVariation VALUES (25,7,4,'ezimagecatalogue/catalogue/variations/7-400x500.jpg',400,300,'');
INSERT INTO eZImageCatalogue_ImageVariation VALUES (26,2,2,'ezimagecatalogue/catalogue/variations/2-200x200.jpg',200,150,'');
INSERT INTO eZImageCatalogue_ImageVariation VALUES (27,14,4,'ezimagecatalogue/catalogue/variations/14-400x500.png',400,300,'');

#
# Dumping data for table 'eZImageCatalogue_ImageVariationGroup'
#

INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (1,150,150);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (2,200,200);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (3,100,100);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (4,400,500);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (5,109,109);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (6,300,300);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (7,240,200);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (8,35,35);

#
# Dumping data for table 'eZLink_Category'
#


#
# Dumping data for table 'eZLink_Hit'
#

INSERT INTO eZLink_Hit VALUES (1,4,20010309104100,'10.0.2.3');

#
# Dumping data for table 'eZLink_Link'
#

INSERT INTO eZLink_Link VALUES (1,'Slashdot','News for nerds, stuff that matters',1,'',20010308203131,'Y','2001-03-08 20:12:29','slashdot.org',0);
INSERT INTO eZLink_Link VALUES (2,'freshmeat.net','Index of Linux and Open Source software',1,'',20010308203047,'Y','2001-03-08 20:16:59','freshmeat.net',0);
INSERT INTO eZLink_Link VALUES (3,'AltaVista','AltaVista Company is the premier knowledge resource on the Internet.',2,'',20010308203011,'Y','2001-03-08 20:24:33','www.altavista.com',0);
INSERT INTO eZLink_Link VALUES (4,'User Friendly','The Comic Strip',3,'',20010308202822,'Y','2001-03-08 20:26:24','www.userfriendly.org',0);
INSERT INTO eZLink_Link VALUES (5,'Dilbert','The Official Dilbert Website features\r\nScott Adam\'s daily Dilbert strips, Dilbert merchandise, The Dilbert List of\r\nthe Day and Dogbert\'s New Ruling Class.',3,'',20010308202822,'Y','2001-03-08 20:27:26','www.dilbert.com',0);
INSERT INTO eZLink_Link VALUES (6,'SourceForge','Breaking Down the Barriers to Open Source Development',1,'',20010308203327,'N','2001-03-08 20:33:27','sourceforge.net',0);
INSERT INTO eZLink_Link VALUES (9,'Trolltech','Qt.',1,'qt qpl gpl',20010309110800,'N','2001-03-09 11:08:00','www.trolltech.com',0);

#
# Dumping data for table 'eZLink_LinkCategoryDefinition'
#


#
# Dumping data for table 'eZLink_LinkCategoryLink'
#


#
# Dumping data for table 'eZLink_LinkGroup'
#

INSERT INTO eZLink_LinkGroup VALUES (1,0,'Linux',0,'Linux related links');
INSERT INTO eZLink_LinkGroup VALUES (2,0,'General',0,'General links');
INSERT INTO eZLink_LinkGroup VALUES (3,0,'Comics',0,'Online comic strips');

#
# Dumping data for table 'eZMail_Account'
#


#
# Dumping data for table 'eZMail_FetchedMail'
#


#
# Dumping data for table 'eZMail_FilterRule'
#


#
# Dumping data for table 'eZMail_Folder'
#


#
# Dumping data for table 'eZMail_Mail'
#


#
# Dumping data for table 'eZMail_MailAttachmentLink'
#


#
# Dumping data for table 'eZMail_MailFolderLink'
#


#
# Dumping data for table 'eZMail_MailImageLink'
#


#
# Dumping data for table 'eZNewsFeed_Category'
#

INSERT INTO eZNewsFeed_Category VALUES (1,'Freshmeat news','Newsfeed from freshmeat.',0);

#
# Dumping data for table 'eZNewsFeed_News'
#

INSERT INTO eZNewsFeed_News VALUES (1,'true',20010309104941,20010309104941,'Satellite 1.0.2 (Default)','A system for tracking machines with dynamic IP addresses.','','http://freshmeat.net/projects/satellite/','');
INSERT INTO eZNewsFeed_News VALUES (2,'true',20010309104941,20010309104941,'X11-Basic Interpreter for Linux 1.00 (Development)','A Basic interpreter.','','http://freshmeat.net/projects/x11-basic/','');
INSERT INTO eZNewsFeed_News VALUES (3,'true',20010309104941,20010309104941,'CGIProxy 1.4.1-SSL (SSL)','Anonymizing, filter-bypassing HTTP proxy in a CGI script (in Perl)','','http://freshmeat.net/projects/cgiproxy/','');
INSERT INTO eZNewsFeed_News VALUES (4,'true',20010309104941,20010309104941,'CGIProxy 1.4.1 (Default)','Anonymizing, filter-bypassing HTTP proxy in a CGI script (in Perl)','','http://freshmeat.net/projects/cgiproxy/','');
INSERT INTO eZNewsFeed_News VALUES (5,'true',20010309104941,20010309104941,'Pure FTP Server 0.95.1 (Default)','An efficient, lightweight, and secure FTP server','','http://freshmeat.net/projects/pureftpd/','');
INSERT INTO eZNewsFeed_News VALUES (6,'true',20010309104941,20010309104941,'PayPal Sender 0.1 (Default)','A command-line tool that sends money through PayPal.','','http://freshmeat.net/projects/ppsend/','');
INSERT INTO eZNewsFeed_News VALUES (7,'true',20010309104941,20010309104941,'Perl-RPM 0.30 (Development)','Perl bindings for the rpm 3.0.X API.','','http://freshmeat.net/projects/perl-rpm/','');
INSERT INTO eZNewsFeed_News VALUES (8,'true',20010309104941,20010309104941,'sntop 1.4.1 (Default)','A curses-based utility that polls network hosts to determine connectivity.','','http://freshmeat.net/projects/simplenetworktop/','');
INSERT INTO eZNewsFeed_News VALUES (9,'true',20010309104941,20010309104941,'Smarty PHP template engine 1.3.1pl1 (Default)','The PHP compiling template engine.','','http://freshmeat.net/projects/smarty/','');
INSERT INTO eZNewsFeed_News VALUES (10,'true',20010309104941,20010309104941,'JeruKey 0.3 (Default)','Lets you bind keyboard keys to actions.','','http://freshmeat.net/projects/jerukey/','');

#
# Dumping data for table 'eZNewsFeed_NewsCategoryLink'
#

INSERT INTO eZNewsFeed_NewsCategoryLink VALUES (1,1,1);
INSERT INTO eZNewsFeed_NewsCategoryLink VALUES (2,2,1);
INSERT INTO eZNewsFeed_NewsCategoryLink VALUES (3,3,1);
INSERT INTO eZNewsFeed_NewsCategoryLink VALUES (4,4,1);
INSERT INTO eZNewsFeed_NewsCategoryLink VALUES (5,5,1);
INSERT INTO eZNewsFeed_NewsCategoryLink VALUES (6,6,1);
INSERT INTO eZNewsFeed_NewsCategoryLink VALUES (7,7,1);
INSERT INTO eZNewsFeed_NewsCategoryLink VALUES (8,8,1);
INSERT INTO eZNewsFeed_NewsCategoryLink VALUES (9,9,1);
INSERT INTO eZNewsFeed_NewsCategoryLink VALUES (10,10,1);

#
# Dumping data for table 'eZNewsFeed_SourceSite'
#

INSERT INTO eZNewsFeed_SourceSite VALUES (1,'http://freshmeat.net/backend/fm.rdf','','',1,'Freshmeat','rdf','true',1);

#
# Dumping data for table 'eZPoll_MainPoll'
#

INSERT INTO eZPoll_MainPoll VALUES (1,1);

#
# Dumping data for table 'eZPoll_Poll'
#

INSERT INTO eZPoll_Poll VALUES (1,'What do you think about PI?','What do you think about PI?',NULL,NULL,'true','true','false','true');

#
# Dumping data for table 'eZPoll_PollChoice'
#

INSERT INTO eZPoll_PollChoice VALUES (1,1,'Should be higher',0);
INSERT INTO eZPoll_PollChoice VALUES (2,1,'Should be lower',0);
INSERT INTO eZPoll_PollChoice VALUES (3,1,'PI?',0);

#
# Dumping data for table 'eZPoll_Vote'
#

INSERT INTO eZPoll_Vote VALUES (1,1,1,'10.0.2.3',1);

#
# Dumping data for table 'eZSession_Preferences'
#

#
# Dumping data for table 'eZSession_Session'
#

#
# Dumping data for table 'eZSession_SessionVariable'
#

#
# Dumping data for table 'eZStats_BrowserType'
#


#
# Dumping data for table 'eZStats_PageView'
#


#
# Dumping data for table 'eZStats_RefererURL'
#


#
# Dumping data for table 'eZStats_RemoteHost'
#


#
# Dumping data for table 'eZStats_RequestPage'
#


#
# Dumping data for table 'eZTodo_Category'
#

INSERT INTO eZTodo_Category VALUES (NULL,1,'Doing this');
INSERT INTO eZTodo_Category VALUES (NULL,2,'Doing that');

#
# Dumping data for table 'eZTodo_Priority'
#

INSERT INTO eZTodo_Priority VALUES (1,'High');
INSERT INTO eZTodo_Priority VALUES (2,'Low');

#
# Dumping data for table 'eZTodo_Status'
#

INSERT INTO eZTodo_Status VALUES (NULL,1,'Not done');
INSERT INTO eZTodo_Status VALUES (NULL,2,'Done');

#
# Dumping data for table 'eZTodo_Todo'
#

INSERT INTO eZTodo_Todo VALUES (1,1,'Public',1,1,1,'Fix this',20010308202612,00000000000000,'Fix this, and then fix that... By noon.',0);

#
# Dumping data for table 'eZTrade_AlternativeCurrency'
#

INSERT INTO eZTrade_AlternativeCurrency VALUES (1,'EUR',0,'EUR',1.5432,20010309094549);

#
# Dumping data for table 'eZTrade_Attribute'
#

INSERT INTO eZTrade_Attribute VALUES (1,1,'Size',20010309095656,0,1,NULL);
INSERT INTO eZTrade_Attribute VALUES (2,1,'Age',20010309095700,0,1,NULL);
INSERT INTO eZTrade_Attribute VALUES (3,1,'Color',20010309095708,0,1,NULL);

#
# Dumping data for table 'eZTrade_AttributeValue'
#

INSERT INTO eZTrade_AttributeValue VALUES (6,1,3,'Red, Green and blue');
INSERT INTO eZTrade_AttributeValue VALUES (5,1,2,'1 year.');
INSERT INTO eZTrade_AttributeValue VALUES (4,1,1,'42 inc.');

#
# Dumping data for table 'eZTrade_Cart'
#

INSERT INTO eZTrade_Cart VALUES (1,5);
INSERT INTO eZTrade_Cart VALUES (4,15);
INSERT INTO eZTrade_Cart VALUES (3,9);

#
# Dumping data for table 'eZTrade_CartItem'
#

INSERT INTO eZTrade_CartItem VALUES (2,1,1,4,0);

#
# Dumping data for table 'eZTrade_CartOptionValue'
#


#
# Dumping data for table 'eZTrade_Category'
#

INSERT INTO eZTrade_Category VALUES (1,0,'Here you will find some nice products.','Products',NULL,1,'');

#
# Dumping data for table 'eZTrade_CategoryOptionLink'
#


#
# Dumping data for table 'eZTrade_GroupPriceLink'
#


#
# Dumping data for table 'eZTrade_Link'
#


#
# Dumping data for table 'eZTrade_LinkSection'
#


#
# Dumping data for table 'eZTrade_Option'
#


#
# Dumping data for table 'eZTrade_OptionValue'
#


#
# Dumping data for table 'eZTrade_OptionValueContent'
#


#
# Dumping data for table 'eZTrade_OptionValueHeader'
#


#
# Dumping data for table 'eZTrade_Order'
#


#
# Dumping data for table 'eZTrade_OrderItem'
#


#
# Dumping data for table 'eZTrade_OrderOptionValue'
#


#
# Dumping data for table 'eZTrade_OrderStatus'
#


#
# Dumping data for table 'eZTrade_OrderStatusType'
#

#
# Dumping data for table 'eZTrade_PreOrder'
#


#
# Dumping data for table 'eZTrade_PriceGroup'
#


#
# Dumping data for table 'eZTrade_Product'
#

INSERT INTO eZTrade_Product VALUES (1,'Flower','Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. ','Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. \r\n\r\nNice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. \r\n\r\nNice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. Nice product, buy. ','nice',200.00,'true','true','false',NULL,'F-100','ez.no','true',20010309095405,00000000000000,'',1,1);

#
# Dumping data for table 'eZTrade_ProductCategoryDefinition'
#

INSERT INTO eZTrade_ProductCategoryDefinition VALUES (5,1,1);

#
# Dumping data for table 'eZTrade_ProductCategoryLink'
#

INSERT INTO eZTrade_ProductCategoryLink VALUES (1,1,1,1);

#
# Dumping data for table 'eZTrade_ProductImageDefinition'
#

INSERT INTO eZTrade_ProductImageDefinition VALUES (1,9,9);

#
# Dumping data for table 'eZTrade_ProductImageLink'
#

INSERT INTO eZTrade_ProductImageLink VALUES (1,1,9,20010309095426);

#
# Dumping data for table 'eZTrade_ProductOptionLink'
#


#
# Dumping data for table 'eZTrade_ProductPriceLink'
#


#
# Dumping data for table 'eZTrade_ProductQuantityDict'
#


#
# Dumping data for table 'eZTrade_ProductSectionDict'
#


#
# Dumping data for table 'eZTrade_ProductTypeLink'
#

INSERT INTO eZTrade_ProductTypeLink VALUES (4,1,1);

#
# Dumping data for table 'eZTrade_Quantity'
#


#
# Dumping data for table 'eZTrade_QuantityRange'
#


#
# Dumping data for table 'eZTrade_ShippingGroup'
#

INSERT INTO eZTrade_ShippingGroup VALUES (1,'Large packet',20010309094624);
INSERT INTO eZTrade_ShippingGroup VALUES (2,'Small packet',20010309094707);

#
# Dumping data for table 'eZTrade_ShippingType'
#

INSERT INTO eZTrade_ShippingType VALUES (1,'Air mail',20010309094620,1,1);
INSERT INTO eZTrade_ShippingType VALUES (2,'Snail mail',20010309094651,0,1);

#
# Dumping data for table 'eZTrade_ShippingValue'
#

INSERT INTO eZTrade_ShippingValue VALUES (1,1,1,10,2);
INSERT INTO eZTrade_ShippingValue VALUES (2,1,2,7,2);
INSERT INTO eZTrade_ShippingValue VALUES (3,2,1,5,1);
INSERT INTO eZTrade_ShippingValue VALUES (4,2,2,4,1);

#
# Dumping data for table 'eZTrade_Type'
#

INSERT INTO eZTrade_Type VALUES (1,'Flower','Attributes for flowers');

#
# Dumping data for table 'eZTrade_VATType'
#

INSERT INTO eZTrade_VATType VALUES (1,'No VAT',0,20010309094734);
INSERT INTO eZTrade_VATType VALUES (2,'Normal',24,20010309094745);

#
# Dumping data for table 'eZTrade_ValueQuantityDict'
#


#
# Dumping data for table 'eZTrade_WishList'
#


#
# Dumping data for table 'eZTrade_WishListItem'
#


#
# Dumping data for table 'eZTrade_WishListOptionValue'
#


#
# Dumping data for table 'eZUser_Cookie'
#


#
# Dumping data for table 'eZUser_Forgot'
#


#
# Dumping data for table 'eZUser_Group'
#

#
# Dumping data for table 'eZUser_GroupPermissionLink'
#

#
# Dumping data for table 'eZUser_Module'
#

#
# Dumping data for table 'eZUser_Permission'
#

#
# Dumping data for table 'eZUser_User'
#


#
# Dumping data for table 'eZUser_UserAddressLink'
#

INSERT INTO eZUser_UserAddressLink VALUES (1,1,1);

