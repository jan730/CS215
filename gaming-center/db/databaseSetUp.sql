SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `Games` (
  `gid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
);

CREATE TABLE `Games_history` (
  `ghid` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `oponent_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `results` varchar(255) NOT NULL,
  `game_date` date NOT NULL
);

CREATE TABLE `Users` (
  `uid` int(11) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dob` date DEFAULT NULL,
  `avatar_path` varchar(255) DEFAULT NULL
);

ALTER TABLE `Games`
  ADD PRIMARY KEY (`gid`);

ALTER TABLE `Games_history`
  ADD PRIMARY KEY (`ghid`),
  ADD KEY `FK1` (`player_id`),
  ADD KEY `FK2` (`oponent_id`),
  ADD KEY `FK3` (`game_id`);

ALTER TABLE `Users`
  ADD PRIMARY KEY (`uid`);

ALTER TABLE `Games`
  MODIFY `gid` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `Games_history`
  MODIFY `ghid` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `Users`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `Games_history`
  ADD CONSTRAINT `games_history_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `Users` (`uid`),
  ADD CONSTRAINT `games_history_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `Games` (`gid`),
  ADD CONSTRAINT `games_history_ibfk_3` FOREIGN KEY (`oponent_id`) REFERENCES `Users` (`uid`);
COMMIT;