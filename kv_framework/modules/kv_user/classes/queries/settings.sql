CREATE TABLE ~tbl_name ( 
    kv_order INT UNSIGNED NOT NULL,
    id VARCHAR(256),
    console_pad INT UNSIGNED NOT NULL,
    console_direction CHAR(30),
    console_db CHAR(30),
    console_table CHAR(30),
    PRIMARY KEY(kv_order)
)