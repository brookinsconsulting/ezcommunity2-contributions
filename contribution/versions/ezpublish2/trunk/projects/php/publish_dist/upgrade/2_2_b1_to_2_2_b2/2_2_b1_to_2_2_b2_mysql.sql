alter table eZTrade_Voucher add MailMethod int default 1;
alter table eZTrade_VoucherEMail change Email OnlineID int default 0; 


# Article search
alter table eZArticle_ArticleWordLink add Frequency float default 0.2;
alter table eZArticle_Word add Frequency float default 0.2;
