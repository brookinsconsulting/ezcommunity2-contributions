alter table eZTrade_OrderItem drop PriceIncVAT;
alter table eZTrade_OrderItem change VATValue VAT decimal(10,2);

alter table eZTrade_Option add RemoteID varchar(100);
alter table eZTrade_CartOptionValue add Count int default 1;