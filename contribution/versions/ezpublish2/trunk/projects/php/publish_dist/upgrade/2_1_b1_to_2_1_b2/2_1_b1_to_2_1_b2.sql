create table eZURLTranslator_URL( ID int primary key auto_increment, Source char(200), Dest char(200) );
alter table eZURLTranslator_URL add Created timestamp;

