CREATE TABLE `car` (
                       `id` int(11) NOT NULL,
                       `make` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
                       `model` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
                       `model_year` year(4) NOT NULL,
                       `mileage` int(11) NOT NULL,
                       `fuel` varchar(20) COLLATE utf8mb4_danish_ci NOT NULL,
                       `type` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
                       `price` int(11) NOT NULL,
                       `dealer_id` varchar(8) COLLATE utf8mb4_danish_ci NOT NULL,
                       `comment` varchar(512) COLLATE utf8mb4_danish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

INSERT INTO `car` (`id`, `make`, `model`, `model_year`, `mileage`, `fuel`, `type`, `price`, `dealer_id`, `comment`) VALUES
(1, 'Volkswagen', 'Passat', 2017, 97805, 'diesel', 'station wagon', 425000, 'Bdf', NULL),
(2, 'Mazda', 'CX-3', 2019, 19777, 'petrol', 'suv', 378900, 'Gjvk', 'Demo car'),
(3, 'Volkswagen', 'UP!', 2017, 16551, 'electric', 'hatchback', 125000, 'Bdf', NULL),
(4, 'Toyota', 'RAV4', 2019, 39661, 'hybrid', 'suv', 428900, 'Hmr', 'Towing hitch'),
(5, 'Mercedes-Benz', 'C Class', 2004, 301204, 'diesel', 'sedan', 31707, 'Hmr', NULL),
(6, 'Audi', 'Q3', 2020, 18516, 'diesel', 'suv', 624900, 'Hrst', 'Demo driven'),
(7, 'Toyota', 'Corolla', 2020, 8738, 'hybrid', 'station wagon', 354900, 'Hmr', NULL),
(8, 'Mazda', 'CX-3', 2019, 23100, 'petrol', 'suv', 289900, 'Gjvk', NULL),
(9, 'Volkswagen', 'Passat', 2019, 43162, 'diesel', 'station wagon', 375000, 'Hrst', NULL),
(10, 'Volkswagen', 'Passat', 2019, 37815, 'diesel', 'station wagon', 362000, 'Elv', NULL),
(11, 'Toyota', 'RAV4', 2016, 80000, 'hybrid', 'suv', 326000, 'Ksvg', NULL),
(12, 'Toyota', 'Corolla', 2020, 20503, 'hybrid', 'station wagon', 359000, 'Gjvk', 'Demobil'),
(13, 'Volkswagen', 'UP!', 2016, 26000, 'electric', 'hatchback', 129000, 'Bo', 'Demo car'),
(14, 'Mercedes-Benz', 'C Class', 2011, 201952, 'hybrid', 'suv', 126000, 'Hmr', NULL),
(15, 'Mercedes-Benz', 'E Class', 2010, 223387, 'diesel', 'sedan', 155000, 'Bdf', NULL),
(16, 'Audi', 'A4', 2019, 36250, 'hybrid', 'station wagon', 364900, 'Ksvg', NULL),
(17, 'Audi', 'A3', 2014, 38044, 'hybrid', 'station wagon', 199000, 'Bo', 'Tow hitch'),
(18, 'Mazda', 'CX-30', 2020, 5800, 'hybrid', 'suv', 399000, 'Elv', 'Flawless'),
(19, 'BMW', 'X5', 2020, 26000, 'hybrid', 'suv', 204900, 'Trd', 'Well equipped'),
(20, 'Mitsubishi', 'Outlander', 2004, 168000, 'petrol', 'station wagon', 49500, 'Lhr', 'Well maintained');

CREATE TABLE `car_brand` (
  `make` char(20) COLLATE utf8mb4_danish_ci NOT NULL,
  `description` varchar(300) COLLATE utf8mb4_danish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

INSERT INTO `car_brand` (`make`, `description`) VALUES
('Ford', NULL);

CREATE TABLE `car_model` (
  `make` char(20) COLLATE utf8mb4_danish_ci NOT NULL,
  `model` char(20) COLLATE utf8mb4_danish_ci NOT NULL,
  `description` varchar(300) COLLATE utf8mb4_danish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

INSERT INTO `car_model` (`make`, `model`, `description`) VALUES
('Ford', 'Fiesta', NULL);

CREATE TABLE `county` (
                          `no` int(11) NOT NULL,
                          `name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

INSERT INTO `county` (`no`, `name`) VALUES
(3, 'Oslo'),
(11, 'Rogaland'),
(15, 'Møre og Romsdal'),
(18, 'Nordland'),
(30, 'Viken'),
(34, 'Innlandet'),
(38, 'Vestfold og Telemark'),
(42, 'Agder'),
(46, 'Vestland'),
(50, 'Trøndelag'),
(54, 'Troms og Finnmark');

CREATE TABLE `dealer` (
                          `id` char(8) COLLATE utf8mb4_danish_ci NOT NULL,
                          `city` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
                          `county_no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

INSERT INTO `dealer` (`id`, `city`, `county_no`) VALUES
('Bdf', 'Bardufoss', 54),
('Bo', 'Bodø', 18),
('Elv', 'Elverum', 34),
('Frst', 'Fredrikstad', 30),
('Gjvk', 'Gjøvik', 34),
('Hmr', 'Hamar', 34),
('Hrst', 'Harstad', 54),
('Jsh', 'Jessheim', 30),
('Ksvg', 'Kongsvinger', 34),
('Lhr', 'Lillehammer', 34),
('Mo', 'Mo i Rana', 18),
('Ms', 'Moss', 30),
('Ot', 'Otta', 34),
('Sarp', 'Sarpsborg', 30),
('Trd', 'Trondheim', 50),
('Trms', 'Tromsø', 54),
('Vrd', 'Verdal', 50);

ALTER TABLE `car`
    ADD PRIMARY KEY (`id`),
    ADD KEY `car_dealer_fk` (`dealer_id`);

ALTER TABLE `car_brand`
    ADD PRIMARY KEY (`make`);

ALTER TABLE `car_model`
    ADD PRIMARY KEY (`make`,`model`);

ALTER TABLE `county`
    ADD PRIMARY KEY (`no`);

ALTER TABLE `dealer`
    ADD PRIMARY KEY (`id`),
    ADD KEY `dealer_county_fk` (`county_no`);

ALTER TABLE `car`
    ADD CONSTRAINT `car_dealer_fk` FOREIGN KEY (`dealer_id`) REFERENCES `dealer` (`id`) ON UPDATE CASCADE;

ALTER TABLE `car_model`
    ADD CONSTRAINT `car_model_brand_fk` FOREIGN KEY (`make`) REFERENCES `car_brand` (`make`) ON UPDATE CASCADE;

ALTER TABLE `dealer`
    ADD CONSTRAINT `dealer_county_fk` FOREIGN KEY (`county_no`) REFERENCES `county` (`no`) ON UPDATE CASCADE;
COMMIT;