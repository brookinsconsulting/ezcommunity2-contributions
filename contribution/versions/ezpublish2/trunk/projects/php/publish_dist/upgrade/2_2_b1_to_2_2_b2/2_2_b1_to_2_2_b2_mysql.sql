alter table eZTrade_Voucher add MailMethod int default 1;
alter table eZTrade_VoucherEMail change Email OnlineID int default 0; 
create table eZBulkMail_UserCategoryLink ( UserID int default 0, CategoryID int default 0 ); 

create table eZBulkMail_UserSubscriptionCategorySettings( ID int NOT NULL, CategoryID int default 0, UserID int default 0, Delay int default 0 );     =======
alter table eZTrade_VoucherEMail change Email OnlineID int default 0; 


# Article search
alter table eZArticle_ArticleWordLink add Frequency float default 0.2;
alter table eZArticle_Word add Frequency float default 0.2;
