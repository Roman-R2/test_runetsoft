create table tires
(
    id   serial not null
        constraint tires_pk
            primary key,
    name text   not null
);

alter table tires
    owner to app;

create unique index tires_id_uindex
    on tires (id);

create table characteristics
(
    id            serial      not null
        constraint characteristics_pk
            primary key,
    brand         varchar(30) not null,
    model         varchar(70) not null,
    width         smallint    not null,
    height        smallint    not null,
    construction  char        not null,
    diameter      smallint    not null,
    load_index    smallint    not null,
    speed_index   char        not null,
    abbreviations varchar(10),
    run_flat_tire varchar(10),
    tire_box      varchar(5),
    season        varchar(30) not null,
    tires_id      integer     not null
        constraint characteristics_tires_id_fk
            references tires
);

alter table characteristics
    owner to app;

create unique index characteristics_id_uindex
    on characteristics (id);

create unique index characteristics_tires_id_uindex
    on characteristics (tires_id);


