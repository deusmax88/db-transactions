create database stocks;

create table stocks.stocks
(
    sku          int          not null primary key,
    qty          int unsigned not null,
    reserved_qty int unsigned not null
);