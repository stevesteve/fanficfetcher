CREATE TABLE `job` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `totalChapters` int(11) NOT NULL,
 `currentChapter` int(11) NOT NULL,
 `url` text COLLATE utf8_bin NOT NULL,
 `filename` text COLLATE utf8_bin,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8 COLLATE=utf8_bin