CREATE TABLE `job` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `timestamp` int(11) NOT NULL,
 `totalChapters` int(11) NOT NULL,
 `currentChapter` int(11) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin