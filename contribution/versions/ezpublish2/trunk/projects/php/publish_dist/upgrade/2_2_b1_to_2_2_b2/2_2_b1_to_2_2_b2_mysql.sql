alter table eZTrade_Voucher add MailMethod int default 1;
alter table eZTrade_VoucherEMail change Email OnlineID int default 0; 