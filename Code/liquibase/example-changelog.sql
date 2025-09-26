--liquibase formatted sql

--changeset Moi.name:1 labels:example-label context:example-context
--comment: example comment
create table test (
    id int primary key not null,
    name varchar(50) not null
)
--rollback DROP TABLE test;

--changeset moi:5
insert into test values (5,'moi');
--rollback delete from test where id=5;

--changeset moi:8
select * from test