CREATE TABLE `search_index` (
    `id` char(36) NOT NULL,
    `model` varchar(255) NOT NULL,
    `foreign_key` char(36) NOT NULL,
    `data` text NOT NULL,
    `name` varchar(255) default NULL,
    `summary` text,
    `url` text,
    `active` tinyint(1) NOT NULL default '1',
    `published` datetime default NULL,
    `created` datetime default NULL,
    `modified` datetime default NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `model` (`model`,`foreign_key`),
    KEY `active` (`active`),
    FULLTEXT KEY `data` (`data`)
) ENGINE=MyISAM;
