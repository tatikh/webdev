CREATE TABLE IF NOT EXISTS `#__bj_ss_items` (
          `id` int(11) NOT NULL auto_increment,
          `cid` int(11) NOT NULL default '0',
          `name` varchar(255) NOT NULL default '',
          `description` text NOT NULL,
          `cssclass` varchar(255),
          `path` varchar(255) NOT NULL default '',
          `is_default` tinyint( 1 ) NOT NULL default '0',
          `ordering` int(11) NOT NULL default '0',
          `published` tinyint(1) NOT NULL default '0',
		  `link` varchar(255),
          PRIMARY KEY  (`id`),
          KEY `cid` (`cid`),
          KEY `is_default` (`is_default`)
        );

CREATE TABLE IF NOT EXISTS `#__bj_ss_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `description` text,
  `ordering` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
)
		