CREATE TABLE IF NOT EXISTS `civicrm_verkiezingslijst` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kandidaat_contact_id` int(11) NOT NULL,
  `partij_contact_id` int(11) NOT NULL,
  `verkiezing` varchar(255) DEFAULT NULL,
  `positie` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE `verkiezing` (  `verkiezing` ,  `partij_contact_id`, `positie` ),
  UNIQUE `kandidaat` (  `verkiezing` ,  `partij_contact_id`, `kandidaat_contact_id` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1 ;