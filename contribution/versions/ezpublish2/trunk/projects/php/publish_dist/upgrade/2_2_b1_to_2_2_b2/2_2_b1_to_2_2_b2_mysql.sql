alter table eZTrade_Voucher add MailMethod int default 1;
alter table eZTrade_VoucherEMail change Email OnlineID int default 0; 
create table eZBulkMail_UserCategoryLink ( UserID int default 0, CategoryID int default 0 ); 

create table eZBulkMail_UserSubscriptionCategorySettings( CategoryID int default 0, UserID int default 0, Delay int default 0 );

# Article search
alter table eZArticle_ArticleWordLink add Frequency float default 0.2;
alter table eZArticle_Word add Frequency float default 0.2;

# cache of article index
CREATE TABLE eZArticle_ArticleKeywordFirstLetter (
  ID int(11) NOT NULL default '0',
  Letter char(1) NOT NULL default ''
);

alter table eZTrade_Voucher add UserID int default 0; 
alter table eZTrade_VoucherUsed add OrderID int default 0;
create table eZTrade_ProductPriceRange( ID int NOT NULL, Min int default 0, Max int default 0, ProductID int default 0 );        

alter table eZBulkMail_UserSubscriptionCategorySettings rename eZBulkMail_UserCategorySettings;  

create table eZBulkMail_UserCategoryDelay ( ID int NOT NULL, CategoryID int default 0, UserID int default 0, Delay int default 0, MailID int default 0 );   

alter table eZTrade_Product add IncludesVAT int(1) default '0';

alter table eZArticle_Category add ListLimit int default '0';

alter table eZCalendar_Appointment add AllDay int default 0;
alter table eZFileManager_FolderPermission add UploadPermission int(11) default '0';
alter table eZImageCatalogue_CategoryPermission add UploadPermission int(11) default '0';

insert into eZUser_Permission (ModuleID, Name) VALUES ('6', 'Buy');

alter table eZTrade_CartItem add VoucherInformationID int default 0;

alter table eZTrade_Category add SectionID int default 1;

alter table eZTrade_Category add SectionID int default 1;       

alter table eZFileManager_Folder add SectionID int default 1;

alter table eZImageCatalogue_Category add SectionID int default '1';                
