alter table eZTrade_VoucherInformation add FromAddressID int default 0; 
alter table eZTrade_VoucherInformation change AddressID ToAddressID int default 0;        

alter table eZFileManager_Folder add SectionID int(11);

alter table eZTrade_OrderOptionValue change OptionName OptionName text;
alter table eZTrade_OrderOptionValue change ValueName ValueName text;
