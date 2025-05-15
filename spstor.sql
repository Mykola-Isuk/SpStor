-- Створення бази даних SpStor
CREATE DATABASE IF NOT EXISTS spstor;
USE spstor;

-- Таблиця категорій
CREATE TABLE IF NOT EXISTS `categori` (
                                          `id` INT NOT NULL AUTO_INCREMENT,
                                          `name` VARCHAR(255) NOT NULL,
    `photo` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Таблиця ігор
CREATE TABLE IF NOT EXISTS `games` (
                                       `id` INT NOT NULL AUTO_INCREMENT,
                                       `name` VARCHAR(255) NOT NULL,
    `photo` VARCHAR(255) DEFAULT NULL,
    `categorie_id` INT NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `short_text` TEXT,
    `visible` TINYINT(1) DEFAULT '1',
    PRIMARY KEY (`id`),
    KEY `categorie_id` (`categorie_id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Таблиця користувачів
CREATE TABLE IF NOT EXISTS `user` (
                                      `id` INT NOT NULL AUTO_INCREMENT,
                                      `login` VARCHAR(191) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `nick_name` VARCHAR(191) NOT NULL,
    `level` TINYINT DEFAULT '0',
    PRIMARY KEY (`id`),
    UNIQUE KEY `login` (`login`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Таблиця бібліотеки користувача
CREATE TABLE IF NOT EXISTS `library` (
                                         `id` INT NOT NULL AUTO_INCREMENT,
                                         `user_id` INT NOT NULL,
                                         `game_id` INT NOT NULL,
                                         PRIMARY KEY (`id`),
    UNIQUE KEY `user_id_game_id` (`user_id`,`game_id`),
    KEY `game_id` (`game_id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Таблиця відгуків
CREATE TABLE IF NOT EXISTS `reviews` (
                                         `id` INT NOT NULL AUTO_INCREMENT,
                                         `user_id` INT NOT NULL,
                                         `game_id` INT NOT NULL,
                                         `review_text` TEXT,
                                         `rating` TINYINT CHECK (`rating` BETWEEN 1 AND 5),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Таблиця сесій
CREATE TABLE IF NOT EXISTS `sessions` (
                                          `id` INT NOT NULL AUTO_INCREMENT,
                                          `user_id` INT NOT NULL,
                                          `session_token` VARCHAR(191) NOT NULL,
    `expires_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `session_token` (`session_token`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Таблиця логів авторизації
CREATE TABLE IF NOT EXISTS `login_logs` (
                                            `id` INT NOT NULL AUTO_INCREMENT,
                                            `user_id` INT NOT NULL,
                                            `login_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                            `ip_address` VARCHAR(45),
    PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Таблиця замовлень (майбутнє розширення)
CREATE TABLE IF NOT EXISTS `orders` (
                                        `id` INT NOT NULL AUTO_INCREMENT,
                                        `user_id` INT NOT NULL,
                                        `order_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                        `total_price` DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Таблиця деталей замовлення (ігри в замовленні)
CREATE TABLE IF NOT EXISTS `order_items` (
                                             `id` INT NOT NULL AUTO_INCREMENT,
                                             `order_id` INT NOT NULL,
                                             `game_id` INT NOT NULL,
                                             `price_at_purchase` DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Початкові дані
INSERT INTO `categori` (`name`, `photo`) VALUES
                                             ('Шутери', 'Шутер.png'),
                                             ('MMORPG', 'rpg.png'),
                                             ('Стелс-ігри', 'Стелс-ігри.png'),
                                             ('Безплатні', 'free.png'),
                                             ('Гонки', 'гонки.png');

INSERT INTO `games` (`name`, `photo`, `categorie_id`, `price`, `short_text`, `visible`) VALUES
                                                                                            ('Call of Duty: Warzone', 'codwz.png', 1, 750, 'Безкоштовний бойовий королівський шутер.', 1),
                                                                                            ('Doom Eternal', 'Doom-Eternal.png', 1, 599, 'Динамічний шутер у світі демонів.', 1),
                                                                                            ('Far Cry 5', 'Far-Cry-5.png', 1, 499, 'Відкритий світ та боротьба з релігійним культом.', 1),
                                                                                            ('Dishonored 2', 'Dishonored.png', 3, 499, 'Стелс-екшен у стилі стимпанк.', 1),
                                                                                            ('Hitman 3', 'Hitman.png', 3, 749, 'Симулятор кілера зі свободою вибору.', 1),
                                                                                            ('The Last of Us', 'The-Last-of-Us.png', 2, 299, 'Драматичний екшен про виживання.', 1),
                                                                                            ('Metal Gear Solid V: The Definitive Experience', 'Metal-Gear-Solid-V.png', 2, 649, 'Тактичний екшен у відкритому світі.', 1),
                                                                                            ('EVE Online', 'EVE-Online.png', 2, 499, 'Космічна MMO-гра з масштабними битвами.', 1),
                                                                                            ('Black Desert', 'Black-Desert.png', 2, 999, 'MMORPG з приголомшливою графікою.', 1),
                                                                                            ('New World', 'New-World.png', 2, 599, 'MMORPG у фентезійному колоніальному сеттингу.', 1),
                                                                                            ('The Elder Scrolls Online', 'The-Elder-Scrolls-Online.png', 2, 649, 'MMORPG у світі Tamriel.', 1),
                                                                                            ('Crossout! Craft. Ride. Destroy.', 'Crossout!-Craft.-Ride.-Destroy.png', 1, 749, 'Постапокаліптичні бої на бронемобілях.', 1),
                                                                                            ('ASSETTO CORSA COMPETIZIONE THE OFFICIAL BLANCPAIN GT SERIES GAME', 'ASSETTO-CORSA-COMPETIZIONE-THE-OFFICIAL-BLANCPAIN-GT-SERIES-GAME.png', 1, 690.00, 'Реалістичний симулятор перегонів GT.', 1),
                                                                                            ('Need for Speed Payback', 'Need-for-Speed-Payback.png', 5, 999, 'Аркадні перегони з сюжетною кампанією.', 1),
                                                                                            ('Forza Horizon 5', 'Forza-Horizon-5.png', 5, 999, 'Відкритий світ і перегони по Мексиці.', 1),
                                                                                            ('War Thunder', 'War-Thunder.png', 1, 799, 'Військовий симулятор техніки.', 1),
                                                                                            ('Assassin`s Creed Odyssey', 'Assassin`s-Creed-Odyssey.png', 3, 749, 'Пригоди у Стародавній Греції.', 1),
                                                                                            ('GTA 5: Grand Theft Auto V', 'GTA.png', 1, 600, 'Екшен у відкритому місті Лос-Сантос.', 1),
                                                                                            ('Red Dead Redemption 2', 'Red-Dead-Redemption-2.png', 1, 949, 'Вестерн про життя грабіжника.', 1),
                                                                                            ('The Witcher 3', 'The-Witcher-3.png', 2, 399, 'Фентезійний RPG із захопливим сюжетом.', 1),
                                                                                            ('Horizon Zero Dawn', 'Horizon-Zero-Dawn.png', 2, 899, 'Постапокаліптичний світ і машини-звіри.', 1),
                                                                                            ('Definitive Edition Battlefield V', 'Definitive-Edition-Battlefield-V.png', 3, 749, 'Шутер про Другу світову війну.', 1),
                                                                                            ('Counter Strike Global Offensive', 'Counter-Strike-Global-Offensive.png', 1, 566, 'Знаменитий командний шутер.', 1),
                                                                                            ('Dota 2', 'Dota.png', 2, 299, 'Популярна MOO-гра.', 1),
                                                                                            ('f-1', 'f-1.png', 5, 789, 'Офіційний симулятор Формули-1.', 1);


INSERT INTO `user` (`login`, `password`, `nick_name`, `level`) VALUES
                                                                   ('admin', MD5('admin123'), 'Admin', 1),
                                                                   ('user', MD5('user123'), 'User1', 0);
INSERT INTO `library` (`user_id`, `game_id`) VALUES
                                                 (2, 3),
                                                 (2, 4),
                                                 (2, 5);

INSERT INTO `reviews` (`user_id`, `game_id`, `review_text`, `rating`) VALUES
                                                                          (2, 3, 'Цікава гра з гарним сюжетом', 4),
                                                                          (2, 4, 'Гра дуже атмосферна', 5);
