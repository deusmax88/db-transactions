begin;
select qty from stocks.stocks where sku = 1;
update stocks.stocks set qty = qty-1 where sku = 1;
rollback;
commit;