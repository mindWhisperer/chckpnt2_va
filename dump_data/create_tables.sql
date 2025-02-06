create table genre
(
    id   int auto_increment
        primary key,
    name varchar(255) null
);

create table users
(
    id                bigint unsigned auto_increment
        primary key,
    name              varchar(255) not null,
    email             varchar(255) not null,
    email_verified_at timestamp    null,
    password          varchar(255) not null,
    created_at        timestamp    null,
    updated_at        timestamp    null,
    role              varchar(10)  null,
    profile_pic       varchar(255) null,
    constraint users_email_unique
        unique (email)
)
    collate = utf8mb4_unicode_ci;

create table books
(
    id          bigint unsigned auto_increment
        primary key,
    name        varchar(255)                        not null,
    genre       int                                 not null,
    image       varchar(255)                        not null,
    description text                                not null,
    created_at  timestamp default CURRENT_TIMESTAMP null,
    updated_at  timestamp                           null,
    creator_id  bigint unsigned                     null,
    constraint books___fk
        foreign key (creator_id) references users (id),
    constraint books__id_fk
        foreign key (genre) references genre (id)
            on delete cascade
)
    collate = utf8mb4_unicode_ci;

create table comments
(
    id         int auto_increment
        primary key,
    book_id    bigint unsigned not null,
    user_id    bigint unsigned not null,
    comment    varchar(255)    null,
    created_at timestamp       null,
    updated_at timestamp       null,
    constraint comments__id_fk
        foreign key (book_id) references books (id),
    constraint comments_users_id_fk
        foreign key (user_id) references users (id)
);

