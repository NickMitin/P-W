

DROP TABLE IF EXISTS `dataObjectField`;
CREATE TABLE IF NOT EXISTS `dataObjectField` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `propertyName` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `fieldName` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `dataType` int(10) unsigned NOT NULL,
  `defaultValue` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `localName` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=10 ;


INSERT INTO `dataObjectField` (`id`, `propertyName`, `fieldName`, `dataType`, `defaultValue`, `localName`, `type`) VALUES
(1, 'email', 'email', 1, '0', 'a:6:{s:10:"nominative";s:5:"email";s:8:"genitive";s:5:"email";s:6:"dative";s:5:"email";s:8:"accusive";s:5:"email";s:8:"creative";s:5:"email";s:13:"prepositional";s:5:"email";}', 1),
(2, 'password', 'password', 6, '0', 'a:6:{s:10:"nominative";s:8:"password";s:8:"genitive";s:8:"password";s:6:"dative";s:8:"password";s:8:"accusive";s:8:"password";s:8:"creative";s:8:"password";s:13:"prepositional";s:8:"password";}', 1),
(3, 'type', 'type', 2, '0', 'a:6:{s:10:"nominative";s:4:"type";s:8:"genitive";s:4:"type";s:6:"dative";s:4:"type";s:8:"accusive";s:4:"type";s:8:"creative";s:4:"type";s:13:"prepositional";s:4:"type";}', 1),
(4, 'title', 'title', 1, '', 'a:6:{s:10:"nominative";s:5:"title";s:8:"genitive";s:5:"title";s:6:"dative";s:5:"title";s:8:"accusive";s:5:"title";s:8:"creative";s:5:"title";s:13:"prepositional";s:5:"title";}', 1),
(5, 'keywords', 'keywords', 9, '', 'a:6:{s:10:"nominative";s:8:"keywords";s:8:"genitive";s:8:"keywords";s:6:"dative";s:8:"keywords";s:8:"accusive";s:8:"keywords";s:8:"creative";s:8:"keywords";s:13:"prepositional";s:8:"keywords";}', 1),
(6, 'description', 'description', 9, '', 'a:6:{s:10:"nominative";s:11:"description";s:8:"genitive";s:11:"description";s:6:"dative";s:11:"description";s:8:"accusive";s:11:"description";s:8:"creative";s:11:"description";s:13:"prepositional";s:11:"description";}', 1),
(7, 'content', 'content', 9, '', 'a:6:{s:10:"nominative";s:7:"content";s:8:"genitive";s:7:"content";s:6:"dative";s:7:"content";s:8:"accusive";s:7:"content";s:8:"creative";s:7:"content";s:13:"prepositional";s:7:"content";}', 1),
(8, 'url', 'url', 1, '', 'a:6:{s:10:"nominative";s:3:"url";s:8:"genitive";s:3:"url";s:6:"dative";s:3:"url";s:8:"accusive";s:3:"url";s:8:"creative";s:3:"url";s:13:"prepositional";s:3:"url";}', 1),
(9, 'status', 'status', 2, '0', 'a:6:{s:10:"nominative";s:6:"status";s:8:"genitive";s:6:"status";s:6:"dative";s:6:"status";s:8:"accusive";s:6:"status";s:8:"creative";s:6:"status";s:13:"prepositional";s:6:"status";}', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `dataObjectMap`
--

DROP TABLE IF EXISTS `dataObjectMap`;
CREATE TABLE IF NOT EXISTS `dataObjectMap` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `type` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `dataObjectMap`
--

INSERT INTO `dataObjectMap` (`id`, `name`, `type`) VALUES
(1, 'user', 1),
(10, 'textPage', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `link_dataObjectMap_dataObjectField`
--

DROP TABLE IF EXISTS `link_dataObjectMap_dataObjectField`;
CREATE TABLE IF NOT EXISTS `link_dataObjectMap_dataObjectField` (
  `dataObjectMapId` int(10) unsigned NOT NULL,
  `dataObjectFieldId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`dataObjectMapId`,`dataObjectFieldId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `link_dataObjectMap_dataObjectField`
--

INSERT INTO `link_dataObjectMap_dataObjectField` (`dataObjectMapId`, `dataObjectFieldId`) VALUES
(1, 1),
(1, 2),
(1, 3),
(10, 4),
(10, 5),
(10, 6),
(10, 7),
(10, 8),
(10, 9);

-- --------------------------------------------------------

--
-- Структура таблицы `link_referenceField_dataObjectMap`
--

DROP TABLE IF EXISTS `link_referenceField_dataObjectMap`;
CREATE TABLE IF NOT EXISTS `link_referenceField_dataObjectMap` (
  `dataObjectMapId` int(10) unsigned NOT NULL,
  `referenceFieldId` int(10) unsigned NOT NULL,
  UNIQUE KEY `referenceFieldId` (`referenceFieldId`,`dataObjectMapId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `link_referenceMap_referenceField`
--

DROP TABLE IF EXISTS `link_referenceMap_referenceField`;
CREATE TABLE IF NOT EXISTS `link_referenceMap_referenceField` (
  `referenceMapId` int(10) unsigned NOT NULL,
  `referenceFieldId` int(10) unsigned NOT NULL,
  `referenceFieldType` int(11) NOT NULL COMMENT '1 - основной объект, 2 - зависимый объект, 3 - дополнительный объект; 4 - свойство.',
  PRIMARY KEY (`referenceMapId`,`referenceFieldId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `referenceField`
--

DROP TABLE IF EXISTS `referenceField`;
CREATE TABLE IF NOT EXISTS `referenceField` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `propertyName` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `fieldName` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `dataType` int(10) unsigned NOT NULL,
  `defaultValue` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `localName` varchar(255) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Структура таблицы `referenceMap`
--

DROP TABLE IF EXISTS `referenceMap`;
CREATE TABLE IF NOT EXISTS `referenceMap` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `type` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Структура таблицы `textPage`
--

DROP TABLE IF EXISTS `textPage`;
CREATE TABLE IF NOT EXISTS `textPage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `keywords` text COLLATE utf8_general_ci NOT NULL,
  `description` text COLLATE utf8_general_ci NOT NULL,
  `content` text COLLATE utf8_general_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `status` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `password` varchar(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `email` varchar(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `type` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `password`, `email`, `type`) VALUES
(1, '', 'guest', 0);
