alter table eZTrade_VoucherInformation add FromAddressID int default 0; 
alter table eZTrade_VoucherInformation change AddressID ToAddressID int default 0;        

alter table eZFileManager_Folder add SectionID int(11);

