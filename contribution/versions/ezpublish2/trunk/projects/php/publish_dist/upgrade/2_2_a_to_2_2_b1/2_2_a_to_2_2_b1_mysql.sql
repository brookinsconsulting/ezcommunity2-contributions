alter table eZContact_Person change BirthDate BirthDate int;

# XML contents in trade
alter table eZTrade_Product add Contents text not null;
Update eZTrade_Product set Contents=Concat( "<?xml version=\"1.0\"?><article><generator>qdom</generator><intro>", Brief, "</intro><body><page>", Description, "</page></body></article>" );  

alter table eZTrade_ProductImageLink add Placement int not null default 0;

#Languages in sections
alter table eZSiteManager_Section add Language varchar(5) default NULL;
 