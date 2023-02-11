-- -------------------------------------------------------------
-- TablePlus 5.2.2(478)
--
-- https://tableplus.com/
--
-- Database: db
-- Generation Time: 2023-01-23 13:05:36.1070
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


DROP TABLE IF EXISTS `drupal_vul_registration_type`;
CREATE TABLE `drupal_vul_registration_type` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `display_name` varchar(255) NOT NULL DEFAULT '',
  `for_team` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `player_count` int(10) NOT NULL DEFAULT 0,
  `is_returning` tinyint(4) NOT NULL DEFAULT 0,
  `required_registrant_type` int(10) unsigned NOT NULL DEFAULT 0,
  `gender_mix` varchar(20) DEFAULT NULL COMMENT 'The required gender make-up for this registration type, base on vul_user_sex gender codes.',
  `display_name_parents` varchar(255) DEFAULT NULL COMMENT 'The display name displayed to parents registering children.',
  `is_active` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Is this registration type active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `_UQ1_name` (`name`),
  KEY `idx_for_team` (`for_team`),
  KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

INSERT INTO `drupal_vul_registration_type` (`id`, `name`, `display_name`, `for_team`, `player_count`, `is_returning`, `required_registrant_type`, `gender_mix`, `display_name_parents`, `is_active`) VALUES
(1, 'Team Import from Old System', '(Team Import)', 1, 0, 0, 0, '', '(Team Import)', 0),
(2, 'Player Import from Old System', '(Player Import)', 0, 1, 0, 0, '', '(Player Import)', 0),
(3, 'New Team', 'New Team', 1, 12, 0, 0, NULL, 'New Team', 1),
(4, 'Single', 'Just me (Single)', 0, 1, 0, 0, '', 'Just my child (Single)', 1),
(5, 'Duo', 'Me and 1 friend (Duo)', 0, 2, 0, 0, 'F', 'Two children (Duo)', 1),
(6, 'Trio', 'Me and 2 friends (Trio)', 0, 3, 0, 0, 'F', 'Three children (Trio)', 1),
(7, 'Returning Team', 'An Existing Team', 1, 12, 1, 0, NULL, 'An Existing Team', 1),
(8, 'Player', 'One player', 0, 1, 0, 0, '', 'One player', 1),
(9, 'Youth Player', 'Just me (I\'m the PLAYER)', 0, 1, 0, 0, '', 'Just me (I\'m the PLAYER)', 0),
(10, 'Youth Parent', 'My child (I\'m the PARENT)', 0, 1, 0, 0, '', 'My child (I\'m the PARENT)', 0),
(11, 'Double', 'Two people (Double)', 0, 2, 0, 0, '', 'Two people (Double)', 1),
(12, 'Guest', 'Guest (non player)', 0, 1, 0, 0, '', 'Guest (non player)', 1),
(13, 'Triple', 'Three People (Triple)', 0, 3, 0, 0, '', 'Three People (Triple)', 1),
(14, 'Quintet', 'Five People (Quintet)', 0, 5, 0, 0, 'F-F-M-M-M,F-F-F-M-M', 'Five People (Quintet)', 1),
(15, 'Woman Matching Player', 'Woman Matching Player', 0, 1, 0, 0, 'F', '', 1);


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;