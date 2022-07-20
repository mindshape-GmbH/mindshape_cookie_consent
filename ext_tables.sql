CREATE TABLE tx_mindshapecookieconsent_domain_model_configuration (

  `uid`                      int(11)                         NOT NULL auto_increment,
  `pid`                      int(11)             DEFAULT '0' NOT NULL,

  `site`                     varchar(255)        DEFAULT ''  NOT NULL,
  `enable_statistic`         tinyint(4) unsigned DEFAULT '0' NOT NULL,
  `header`                   varchar(255)        DEFAULT ''  NOT NULL,
  `imprint`                  varchar(255)        DEFAULT ''  NOT NULL,
  `datapolicy`               varchar(255)        DEFAULT ''  NOT NULL,
  `hint`                     text,
  `necessary_cookies_info`   text,
  `necessary_cookie_options` varchar(255)        DEFAULT ''  NOT NULL,
  `select_all_label`         varchar(255)        DEFAULT ''  NOT NULL,
  `save_label`               varchar(255)        DEFAULT ''  NOT NULL,
  `deny_label`               varchar(255)        DEFAULT ''  NOT NULL,
  `show_details_label`       varchar(255)        DEFAULT ''  NOT NULL,
  `hide_details_label`       varchar(255)        DEFAULT ''  NOT NULL,
  `cookie_categories`        int(11) unsigned    DEFAULT '0',

  `hidden`                   tinyint(4) unsigned DEFAULT '0' NOT NULL,
  `sys_language_uid`         int(11)             DEFAULT '0' NOT NULL,
  `l10n_source`              int(11)             DEFAULT '0' NOT NULL,
  `l10n_parent`              int(11)             DEFAULT '0' NOT NULL,
  `l10n_diffsource`          mediumblob,

  PRIMARY KEY (`uid`),
  KEY parent(`pid`),
  KEY language(`l10n_parent`, `sys_language_uid`)

);

CREATE TABLE tx_mindshapecookieconsent_domain_model_cookiecategory (

  `uid`              int(11)                         NOT NULL auto_increment,
  `pid`              int(11)             DEFAULT '0' NOT NULL,

  `sorting`          int(11) unsigned    DEFAULT '0' NOT NULL,

  `configuration`    int(11) unsigned    DEFAULT '0' NOT NULL,
  `label`            varchar(255)        DEFAULT ''  NOT NULL,
  `info`             text,
  `cookie_options`   int(11) unsigned    DEFAULT '0',

  `hidden`           tinyint(4) unsigned DEFAULT '0' NOT NULL,
  `sys_language_uid` int(11)             DEFAULT '0' NOT NULL,
  `l10n_source`      int(11)             DEFAULT '0' NOT NULL,
  `l10n_parent`      int(11)             DEFAULT '0' NOT NULL,
  `l10n_diffsource`  mediumblob,

  PRIMARY KEY (`uid`),
  KEY parent(`pid`),
  KEY language(`l10n_parent`, `sys_language_uid`)

);

CREATE TABLE tx_mindshapecookieconsent_domain_model_cookieoption (

    `uid`               int(11)                         NOT NULL auto_increment,
    `pid`               int(11)             DEFAULT '0' NOT NULL,

    `sorting`           int(11) unsigned    DEFAULT '0' NOT NULL,

    `cookie_category`   int(11) unsigned    DEFAULT '0' NOT NULL,
    `label`             varchar(255)        DEFAULT ''  NOT NULL,
    `identifier`        varchar(255)        DEFAULT ''  NOT NULL,
    `provider`          varchar(255)        DEFAULT ''  NOT NULL,
    `purpose`           varchar(255)        DEFAULT ''  NOT NULL,
    `cookie_name`       varchar(255)        DEFAULT ''  NOT NULL,
    `cookie_duration`   varchar(255)        DEFAULT ''  NOT NULL,
    `replacement_label` varchar(255)        DEFAULT ''  NOT NULL,
    `info`              text,

    `hidden`            tinyint(4) unsigned DEFAULT '0' NOT NULL,
    `sys_language_uid`  int(11)             DEFAULT '0' NOT NULL,
    `l10n_source`       int(11)             DEFAULT '0' NOT NULL,
    `l10n_parent`       int(11)             DEFAULT '0' NOT NULL,
    `l10n_diffsource`   mediumblob,

    PRIMARY KEY (`uid`),
    KEY parent(`pid`),
    KEY language(`l10n_parent`, `sys_language_uid`)

);

CREATE TABLE tx_mindshapecookieconsent_domain_model_statisticcategory (

  `uid`              int(11)                      NOT NULL auto_increment,
  `pid`              int(11)          DEFAULT '0' NOT NULL,

  `configuration`    int(11) unsigned DEFAULT '0' NOT NULL,
  `date_begin`       datetime         DEFAULT NULL,
  `date_end`         datetime         DEFAULT NULL,
  `cookie_category`  int(11) unsigned DEFAULT '0' NOT NULL,
  `counter`          int(11) unsigned DEFAULT '0' NOT NULL,

  `sys_language_uid` int(11)          DEFAULT '0' NOT NULL,

  PRIMARY KEY (`uid`),
  KEY parent(`pid`)

);

CREATE TABLE tx_mindshapecookieconsent_domain_model_statisticoption (

  `uid`              int(11)                      NOT NULL auto_increment,
  `pid`              int(11)          DEFAULT '0' NOT NULL,

  `configuration`    int(11) unsigned DEFAULT '0' NOT NULL,
  `date_begin`       datetime         DEFAULT NULL,
  `date_end`         datetime         DEFAULT NULL,
  `cookie_option`    int(11) unsigned DEFAULT '0' NOT NULL,
  `counter`          int(11) unsigned DEFAULT '0' NOT NULL,

  `sys_language_uid` int(11)          DEFAULT '0' NOT NULL,

  PRIMARY KEY (`uid`),
  KEY parent(`pid`)

);

CREATE TABLE tx_mindshapecookieconsent_domain_model_statisticbutton (

  `uid`              int(11)                      NOT NULL auto_increment,
  `pid`              int(11)          DEFAULT '0' NOT NULL,

  `configuration`    int(11) unsigned DEFAULT '0' NOT NULL,
  `date_begin`       datetime         DEFAULT NULL,
  `date_end`         datetime         DEFAULT NULL,
  `save`             int(11) unsigned DEFAULT '0' NOT NULL,
  `deny`             int(11) unsigned DEFAULT '0' NOT NULL,
  `agree_to_all`     int(11) unsigned DEFAULT '0' NOT NULL,

  `sys_language_uid` int(11)          DEFAULT '0' NOT NULL,

  PRIMARY KEY (`uid`),
  KEY parent(`pid`)

);
