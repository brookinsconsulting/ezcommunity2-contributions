alter table eZTrade_OrderItem drop PriceIncVAT;
alter table eZTrade_OrderItem change VATValue VAT decimal(10,2);