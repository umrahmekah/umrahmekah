-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.7.19 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for oomrah_app
CREATE DATABASE IF NOT EXISTS `oomrah_app` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `oomrah_app`;

-- Dumping structure for table oomrah_app.banners
CREATE TABLE IF NOT EXISTS `banners` (
  `bannerID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `position_name` varchar(200) DEFAULT 'home',
  `image` varchar(200) DEFAULT NULL,
  `content` longtext,
  `link` varchar(200) DEFAULT NULL,
  `link_button` varchar(200) DEFAULT NULL,
  `sort` int(11) DEFAULT '0',
  `status` enum('enable','disable') DEFAULT 'enable',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`bannerID`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.bookings
CREATE TABLE IF NOT EXISTS `bookings` (
  `bookingsID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bookingno` varchar(255) DEFAULT NULL,
  `travellerID` int(11) DEFAULT NULL,
  `tour` int(11) DEFAULT '0',
  `hotel` int(11) DEFAULT '0',
  `flight` int(11) DEFAULT '0',
  `car` int(11) DEFAULT '0',
  `extraservices` int(11) DEFAULT '0',
  `totaltravellers` int(11) NOT NULL,
  `balance` int(11) DEFAULT '0',
  `type` int(11) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `affiliatelink` varchar(255) DEFAULT NULL,
  `entry_by` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `new_traveler` varchar(255) DEFAULT NULL,
  `old_traveller` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`bookingsID`),
  UNIQUE KEY `bookingno` (`bookingno`)
) ENGINE=InnoDB AUTO_INCREMENT=214 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.book_car
CREATE TABLE IF NOT EXISTS `book_car` (
  `bookcarID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bookingID` int(11) DEFAULT NULL,
  `carbrandID` int(11) DEFAULT NULL,
  `carsID` int(11) DEFAULT NULL,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `rate` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `pickup` varchar(200) DEFAULT NULL,
  `dropoff` varchar(200) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`bookcarID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.book_extra
CREATE TABLE IF NOT EXISTS `book_extra` (
  `bookextraID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bookingID` int(11) DEFAULT NULL,
  `extraserviceID` int(11) DEFAULT NULL,
  `remarks` longtext,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`bookextraID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.book_flight
CREATE TABLE IF NOT EXISTS `book_flight` (
  `bookflightID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bookingID` int(11) DEFAULT NULL,
  `travellersID` varchar(200) DEFAULT NULL,
  `airlineID` varchar(200) DEFAULT NULL,
  `class` int(11) DEFAULT NULL,
  `return` int(11) DEFAULT NULL,
  `depairportID` int(11) DEFAULT NULL,
  `arrairportID` int(11) DEFAULT NULL,
  `departing` datetime DEFAULT NULL,
  `arrFlightNO` varchar(200) DEFAULT NULL,
  `returning` datetime DEFAULT NULL,
  `depFlightNO` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) DEFAULT '2',
  `PNR` varchar(11) DEFAULT NULL,
  `eticketno` varchar(30) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`bookflightID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.book_hotel
CREATE TABLE IF NOT EXISTS `book_hotel` (
  `bookhotelID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bookingID` int(11) DEFAULT NULL,
  `countryID` int(11) DEFAULT NULL,
  `cityID` int(11) DEFAULT NULL,
  `hotelID` int(11) DEFAULT NULL,
  `checkin` date DEFAULT NULL,
  `checkout` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) DEFAULT '2',
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`bookhotelID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.book_room
CREATE TABLE IF NOT EXISTS `book_room` (
  `roomID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bookingID` int(11) DEFAULT NULL,
  `roomtype` int(11) DEFAULT NULL,
  `travellers` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `entry_by` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '2',
  `remarks` text,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`roomID`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.book_tour
CREATE TABLE IF NOT EXISTS `book_tour` (
  `booktourID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bookingID` int(11) DEFAULT NULL,
  `tourcategoriesID` int(11) DEFAULT NULL,
  `tourID` int(11) DEFAULT NULL,
  `tourdateID` int(11) DEFAULT NULL,
  `deposit_paid` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `entry_by` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '2',
  `owner_id` int(11) DEFAULT NULL,
  `admin_read` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`booktourID`)
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.cars
CREATE TABLE IF NOT EXISTS `cars` (
  `carsID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carbrandID` int(200) DEFAULT NULL,
  `model` varchar(200) DEFAULT NULL,
  `description` text,
  `passengers` int(11) DEFAULT NULL,
  `cardoors` int(11) DEFAULT NULL,
  `transmission` int(11) DEFAULT NULL,
  `baggage` varchar(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `featured` int(11) DEFAULT NULL,
  `dayrate` int(11) DEFAULT NULL,
  `weekrate` int(11) DEFAULT NULL,
  `monthrate` int(11) DEFAULT NULL,
  `currencyID` int(11) DEFAULT NULL,
  `airportpickup` int(11) DEFAULT NULL,
  `availableextras` varchar(200) DEFAULT NULL,
  `images` varchar(300) DEFAULT NULL,
  `similarcars` varchar(200) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`carsID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.credittotals
CREATE TABLE IF NOT EXISTS `credittotals` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) DEFAULT NULL,
  `total_credit` int(11) DEFAULT '0',
  `entry_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.credit_package
CREATE TABLE IF NOT EXISTS `credit_package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_name` varchar(255) DEFAULT NULL,
  `credit` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `currency` int(11) DEFAULT NULL,
  `country` int(11) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `entry_by` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.credit_transactions
CREATE TABLE IF NOT EXISTS `credit_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_by` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `agency` int(11) NOT NULL,
  `transaction_id` varchar(50) DEFAULT NULL,
  `amount_paid` int(11) DEFAULT NULL,
  `credit_request` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `payment_gateway_id` int(11) DEFAULT NULL,
  `credit` int(11) DEFAULT NULL,
  `currency` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_airlines
CREATE TABLE IF NOT EXISTS `def_airlines` (
  `airlineID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `airline` varchar(200) DEFAULT NULL,
  `countryID` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`airlineID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_airports
CREATE TABLE IF NOT EXISTS `def_airports` (
  `airportID` int(11) NOT NULL AUTO_INCREMENT,
  `airport_name` varchar(50) NOT NULL DEFAULT '',
  `countryID` int(3) NOT NULL,
  `cityID` int(3) NOT NULL,
  `IATA` varchar(10) DEFAULT NULL,
  `status` int(1) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`airportID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_car_brands
CREATE TABLE IF NOT EXISTS `def_car_brands` (
  `carbrandID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `brand` varchar(200) DEFAULT NULL,
  `description` text,
  `status` int(11) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`carbrandID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_car_extras
CREATE TABLE IF NOT EXISTS `def_car_extras` (
  `carsextrasID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `currency` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`carsextrasID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_city
CREATE TABLE IF NOT EXISTS `def_city` (
  `cityID` int(11) NOT NULL AUTO_INCREMENT,
  `city_name` varchar(128) NOT NULL,
  `countryID` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`cityID`)
) ENGINE=InnoDB AUTO_INCREMENT=4239 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_country
CREATE TABLE IF NOT EXISTS `def_country` (
  `countryID` int(11) NOT NULL AUTO_INCREMENT,
  `country_name` varchar(128) NOT NULL DEFAULT '',
  `country_code` varchar(2) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`countryID`)
) ENGINE=InnoDB AUTO_INCREMENT=258 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_currency
CREATE TABLE IF NOT EXISTS `def_currency` (
  `currencyID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `symbol` varchar(10) DEFAULT NULL,
  `currency_name` varchar(100) DEFAULT NULL,
  `currency_sym` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`currencyID`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_extra_expenses
CREATE TABLE IF NOT EXISTS `def_extra_expenses` (
  `expenseID` int(11) NOT NULL AUTO_INCREMENT,
  `extra_expenses` varchar(200) NOT NULL,
  `cost` int(11) NOT NULL,
  `currencyID` varchar(200) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`expenseID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_extra_services
CREATE TABLE IF NOT EXISTS `def_extra_services` (
  `extraserviceID` int(11) NOT NULL AUTO_INCREMENT,
  `service_name` varchar(250) NOT NULL,
  `price` int(11) NOT NULL,
  `currencyID` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`extraserviceID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_hotel_categories
CREATE TABLE IF NOT EXISTS `def_hotel_categories` (
  `hotelcategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(300) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`hotelcategoryID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_hotel_facilities
CREATE TABLE IF NOT EXISTS `def_hotel_facilities` (
  `hotelfacilityID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `facility` varchar(100) DEFAULT NULL,
  `iconID` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`hotelfacilityID`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_hotel_seasons
CREATE TABLE IF NOT EXISTS `def_hotel_seasons` (
  `seasonID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `seasonname` int(11) DEFAULT NULL,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`seasonID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_hotel_type
CREATE TABLE IF NOT EXISTS `def_hotel_type` (
  `hoteltypeID` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(300) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`hoteltypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_inclusions
CREATE TABLE IF NOT EXISTS `def_inclusions` (
  `inclusionID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `inclusion` varchar(200) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `entry_by` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT '1',
  PRIMARY KEY (`inclusionID`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_languages
CREATE TABLE IF NOT EXISTS `def_languages` (
  `languageID` int(11) NOT NULL AUTO_INCREMENT,
  `language_name` varchar(300) NOT NULL DEFAULT '',
  `status` tinyint(1) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`languageID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_optional_tours
CREATE TABLE IF NOT EXISTS `def_optional_tours` (
  `optionaltourID` int(11) NOT NULL AUTO_INCREMENT,
  `optional_tour` varchar(200) NOT NULL,
  `currencyID` int(11) NOT NULL,
  `price` int(200) NOT NULL,
  `description` mediumtext NOT NULL,
  `status` tinyint(1) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`optionaltourID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_payment_types
CREATE TABLE IF NOT EXISTS `def_payment_types` (
  `paymenttypeID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(20) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`paymenttypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_room_amenities
CREATE TABLE IF NOT EXISTS `def_room_amenities` (
  `roomamenityID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `amenty` varchar(200) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`roomamenityID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_room_types
CREATE TABLE IF NOT EXISTS `def_room_types` (
  `roomtypeID` int(11) NOT NULL AUTO_INCREMENT,
  `room_type` varchar(300) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`roomtypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_shopping_types
CREATE TABLE IF NOT EXISTS `def_shopping_types` (
  `shoppingtypeID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `shopping_type` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`shoppingtypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_sites
CREATE TABLE IF NOT EXISTS `def_sites` (
  `siteID` int(11) NOT NULL AUTO_INCREMENT,
  `site_name` varchar(200) NOT NULL DEFAULT '',
  `countryID` int(3) NOT NULL,
  `cityID` int(3) NOT NULL,
  `admissionfee` int(200) NOT NULL DEFAULT '0',
  `parkingfee_car` int(200) NOT NULL DEFAULT '0',
  `parkingfee_minibus` int(200) NOT NULL DEFAULT '0',
  `parkingfee_bus` int(200) NOT NULL DEFAULT '0',
  `currencyID` int(100) NOT NULL DEFAULT '1',
  `description` mediumtext,
  `image` varchar(200) DEFAULT NULL,
  `featured` int(11) DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`siteID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_supplier
CREATE TABLE IF NOT EXISTS `def_supplier` (
  `supplierID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` mediumtext,
  `suppliertypeID` int(3) DEFAULT NULL,
  `cityID` int(3) DEFAULT NULL,
  `countryID` int(3) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`supplierID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_supplier_type
CREATE TABLE IF NOT EXISTS `def_supplier_type` (
  `suppliertypeID` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_type` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`suppliertypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_time_slots
CREATE TABLE IF NOT EXISTS `def_time_slots` (
  `timeslotID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `time` varchar(10) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`timeslotID`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_tour_categories
CREATE TABLE IF NOT EXISTS `def_tour_categories` (
  `tourcategoriesID` int(11) NOT NULL AUTO_INCREMENT,
  `tourcategoryname` varchar(300) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL,
  `type` tinyint(1) DEFAULT '1',
  `owner_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tourcategoriesID`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_transferman
CREATE TABLE IF NOT EXISTS `def_transferman` (
  `transfermanID` int(11) NOT NULL AUTO_INCREMENT,
  `namesurname` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL,
  `mobilephone` varchar(50) NOT NULL DEFAULT '',
  `notes` text,
  `img` varchar(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`transfermanID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.def_vehicle
CREATE TABLE IF NOT EXISTS `def_vehicle` (
  `vehicleID` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_name` varchar(300) NOT NULL,
  `capacity` int(100) NOT NULL,
  `supplier_id` int(100) NOT NULL,
  `cost` int(11) DEFAULT NULL,
  `currencyID` int(11) DEFAULT NULL,
  `notes` mediumtext,
  `status` tinyint(1) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`vehicleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.faq
CREATE TABLE IF NOT EXISTS `faq` (
  `faqID` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `content` text CHARACTER SET utf8,
  `status` enum('0','1') CHARACTER SET utf8 DEFAULT '1',
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`faqID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.faqitems
CREATE TABLE IF NOT EXISTS `faqitems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sectionID` int(6) DEFAULT NULL,
  `question` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `answer` text CHARACTER SET utf8,
  `ordering` smallint(3) DEFAULT '0',
  `hint` int(11) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.faqsection
CREATE TABLE IF NOT EXISTS `faqsection` (
  `sectionID` int(6) NOT NULL AUTO_INCREMENT,
  `faqID` int(6) DEFAULT NULL,
  `title` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `orderID` int(6) DEFAULT NULL,
  `default` enum('0','1') CHARACTER SET utf8 DEFAULT NULL,
  `note` text CHARACTER SET utf8 NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`sectionID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.flights
CREATE TABLE IF NOT EXISTS `flights` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `iata` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=807 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.flight_booking
CREATE TABLE IF NOT EXISTS `flight_booking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `departure_date` date NOT NULL,
  `pnr` varchar(50) DEFAULT NULL,
  `pax` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `tourdates_id` int(11) DEFAULT NULL,
  `flight_match_id` int(11) NOT NULL,
  `entry_by` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.flight_date
CREATE TABLE IF NOT EXISTS `flight_date` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `flight_company` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `entry_by` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.flight_matching
CREATE TABLE IF NOT EXISTS `flight_matching` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `flight_number_1` varchar(50) NOT NULL,
  `sector_1` varchar(50) NOT NULL,
  `day_1` varchar(50) NOT NULL,
  `dep_time_1` varchar(50) NOT NULL,
  `arr_time_1` varchar(50) NOT NULL,
  `flight_number_2` varchar(50) NOT NULL,
  `sector_2` varchar(50) NOT NULL,
  `day_2` varchar(50) NOT NULL,
  `dep_time_2` varchar(50) NOT NULL,
  `arr_time_2` varchar(50) NOT NULL,
  `number_of_days` varchar(50) NOT NULL,
  `flight_date` int(11) NOT NULL,
  `entry_by` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.guides
CREATE TABLE IF NOT EXISTS `guides` (
  `guideID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `mobilephone` varchar(100) NOT NULL DEFAULT '',
  `address` mediumtext,
  `license_no` varchar(100) NOT NULL,
  `languageID` varchar(100) NOT NULL DEFAULT '',
  `image` varchar(300) DEFAULT NULL,
  `CV` longtext,
  `cityID` int(10) DEFAULT NULL,
  `countryID` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`guideID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.guide_notes
CREATE TABLE IF NOT EXISTS `guide_notes` (
  `guidenotesID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `guideID` int(11) DEFAULT NULL,
  `title` text,
  `note` longtext,
  `style` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT '2016-01-01 08:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `entry_by` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`guidenotesID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.hotels
CREATE TABLE IF NOT EXISTS `hotels` (
  `hotelID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) DEFAULT NULL,
  `hotel_name` varchar(100) DEFAULT NULL,
  `hotel_description` text,
  `hotelcategoryID` int(5) DEFAULT NULL,
  `hoteltypeID` int(11) DEFAULT NULL,
  `countryID` int(50) NOT NULL,
  `cityID` int(50) NOT NULL,
  `hotel_email` varchar(100) DEFAULT NULL,
  `web_site` varchar(100) DEFAULT NULL,
  `hotel_phone` int(20) DEFAULT NULL,
  `hotel_fax` int(20) DEFAULT NULL,
  `address` text,
  `person_in_contact` varchar(100) DEFAULT '',
  `contact_phone` int(20) DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `facilities` varchar(100) DEFAULT '',
  `checkin` int(2) DEFAULT '62',
  `checkout` int(2) DEFAULT '54',
  `paymentoptions` varchar(100) DEFAULT '',
  `policyandterms` longtext,
  `similarhotels` varchar(100) DEFAULT NULL,
  `tripadvisor` varchar(200) DEFAULT NULL,
  `facebook` varchar(200) DEFAULT NULL,
  `twitter` varchar(200) DEFAULT NULL,
  `instagram` varchar(200) DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`hotelID`),
  UNIQUE KEY `hotel_email` (`hotel_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.hotels_note
CREATE TABLE IF NOT EXISTS `hotels_note` (
  `hotel_noteID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hotelID` int(11) DEFAULT NULL,
  `title` text,
  `note` longtext,
  `style` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `entry_by` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`hotel_noteID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.hotel_rates
CREATE TABLE IF NOT EXISTS `hotel_rates` (
  `hotelrateid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hotelID` int(11) DEFAULT NULL,
  `roomtypeID` int(11) DEFAULT NULL,
  `season` int(11) DEFAULT NULL,
  `rate` int(11) DEFAULT NULL,
  `currency` int(11) DEFAULT NULL,
  `images` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`hotelrateid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.icons
CREATE TABLE IF NOT EXISTS `icons` (
  `iconID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `icon` varchar(50) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`iconID`)
) ENGINE=InnoDB AUTO_INCREMENT=718 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.invoice
CREATE TABLE IF NOT EXISTS `invoice` (
  `invoiceID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `travellerID` int(11) DEFAULT NULL,
  `bookingID` varchar(11) DEFAULT NULL,
  `InvTotal` decimal(10,0) DEFAULT NULL,
  `Subtotal` decimal(10,0) DEFAULT NULL,
  `currency` int(200) DEFAULT NULL,
  `payment_type` varchar(200) DEFAULT NULL,
  `notes` text,
  `DateIssued` date DEFAULT NULL,
  `DueDate` date DEFAULT NULL,
  `discount` decimal(10,0) DEFAULT NULL,
  `tax` decimal(10,0) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `entry_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`invoiceID`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.invoice_payments
CREATE TABLE IF NOT EXISTS `invoice_payments` (
  `invoicePaymentID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `travellerID` int(11) DEFAULT NULL,
  `invoiceID` int(11) DEFAULT NULL,
  `amount` int(200) DEFAULT NULL,
  `currency` int(200) DEFAULT NULL,
  `payment_type` int(200) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `notes` text,
  `entry_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `received` int(11) DEFAULT '0',
  `owner_id` int(11) DEFAULT NULL,
  `payment_prove` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`invoicePaymentID`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.invoice_products
CREATE TABLE IF NOT EXISTS `invoice_products` (
  `ItemID` bigint(20) NOT NULL AUTO_INCREMENT,
  `InvID` int(11) DEFAULT NULL,
  `Code` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `Items` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `Qty` int(11) DEFAULT NULL,
  `Amount` decimal(10,0) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`ItemID`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.payment_gateways
CREATE TABLE IF NOT EXISTS `payment_gateways` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gateway_name` varchar(255) DEFAULT NULL,
  `gateway_api_key` varchar(255) DEFAULT NULL,
  `entry_by` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.reviews
CREATE TABLE IF NOT EXISTS `reviews` (
  `reviewID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tourID` int(11) DEFAULT NULL,
  `travellerID` int(11) DEFAULT NULL,
  `overall_experience` int(11) DEFAULT NULL,
  `destinations_visited` int(11) DEFAULT NULL,
  `td_knowledge` int(11) DEFAULT NULL,
  `td_customer_service` int(11) DEFAULT NULL,
  `td_communication` int(11) DEFAULT NULL,
  `driver_courteous` int(11) DEFAULT NULL,
  `driver_ability` int(11) DEFAULT NULL,
  `coach_cleanliness` int(11) DEFAULT NULL,
  `coach_comfort` int(11) DEFAULT NULL,
  `sightseeing` int(11) DEFAULT NULL,
  `local_experts` int(11) DEFAULT NULL,
  `optionals` int(11) DEFAULT NULL,
  `breakfast` int(11) DEFAULT NULL,
  `welcome_dinner` int(11) DEFAULT NULL,
  `hotel_dinners` int(11) DEFAULT NULL,
  `accom_quality` int(11) DEFAULT NULL,
  `accom_location` int(11) DEFAULT NULL,
  `your_comments` text,
  `any_suggestions` text,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`reviewID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.sale_record
CREATE TABLE IF NOT EXISTS `sale_record` (
  `saleID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tourID` int(11) DEFAULT NULL,
  `tourdateID` int(11) DEFAULT NULL,
  `shopping_date` date DEFAULT NULL,
  `supplierID` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `currencyID` int(11) DEFAULT NULL,
  `note` longtext,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`saleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `payload` text CHARACTER SET utf8,
  `last_activity` int(20) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.tbl_tickets
CREATE TABLE IF NOT EXISTS `tbl_tickets` (
  `TicketID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) DEFAULT NULL,
  `Title` varchar(255) DEFAULT NULL,
  `Category` int(11) DEFAULT NULL,
  `Priority` int(11) DEFAULT '0',
  `Description` text,
  `Image` varchar(255) DEFAULT NULL,
  `Status` enum('Pending','Processed','Completed','New') DEFAULT 'New',
  `createdOn` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedOn` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `entry_by` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`TicketID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.tbl_tickets_reply
CREATE TABLE IF NOT EXISTS `tbl_tickets_reply` (
  `ReplyID` bigint(20) NOT NULL AUTO_INCREMENT,
  `TicketID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `Comments` text,
  `createdOn` timestamp NULL DEFAULT NULL,
  `updatedOn` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`ReplyID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.tbl_ticket_category
CREATE TABLE IF NOT EXISTS `tbl_ticket_category` (
  `ticket_category_ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_category` varchar(200) DEFAULT '',
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticket_category_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.tbl_todo_items
CREATE TABLE IF NOT EXISTS `tbl_todo_items` (
  `ItemID` int(11) NOT NULL AUTO_INCREMENT,
  `TaskID` int(11) DEFAULT NULL,
  `Name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `Status` int(1) DEFAULT NULL,
  `createdOn` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedOn` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`ItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.tbl_todo_projects
CREATE TABLE IF NOT EXISTS `tbl_todo_projects` (
  `ProID` int(11) NOT NULL AUTO_INCREMENT,
  `ProTitle` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `ProDesc` text CHARACTER SET utf8,
  `ProUsers` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `ProDueDate` date DEFAULT NULL,
  `ProStatus` int(1) DEFAULT '0',
  `createdOn` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedOn` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `entry_by` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`ProID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.tbl_todo_tasks
CREATE TABLE IF NOT EXISTS `tbl_todo_tasks` (
  `TaskID` int(11) NOT NULL AUTO_INCREMENT,
  `ProID` int(11) DEFAULT NULL,
  `TaskName` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `TaskDesc` text CHARACTER SET utf8,
  `TaskUsers` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `TaskDueDate` date DEFAULT NULL,
  `TaskStatus` int(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`TaskID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.tb_comments
CREATE TABLE IF NOT EXISTS `tb_comments` (
  `commentID` int(11) NOT NULL AUTO_INCREMENT,
  `pageID` int(6) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `comments` longtext CHARACTER SET utf8,
  `posted` datetime DEFAULT NULL,
  `approved` int(11) DEFAULT '0',
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`commentID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.tb_forms
CREATE TABLE IF NOT EXISTS `tb_forms` (
  `formID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `method` enum('eav','table','email') CHARACTER SET utf8 DEFAULT 'table',
  `tablename` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(225) CHARACTER SET utf8 DEFAULT NULL,
  `configuration` longtext CHARACTER SET utf8,
  `success` text CHARACTER SET utf8,
  `failed` text CHARACTER SET utf8,
  `redirect` text CHARACTER SET utf8,
  `sendcopy` int(1) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`formID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.tb_groups
CREATE TABLE IF NOT EXISTS `tb_groups` (
  `group_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `level` int(6) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.tb_groups_access
CREATE TABLE IF NOT EXISTS `tb_groups_access` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `group_id` int(6) DEFAULT NULL,
  `module_id` int(6) DEFAULT NULL,
  `access_data` text,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3239 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.tb_logs
CREATE TABLE IF NOT EXISTS `tb_logs` (
  `auditID` int(20) NOT NULL AUTO_INCREMENT,
  `ipaddress` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `module` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `task` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `note` text CHARACTER SET utf8,
  `logdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`auditID`)
) ENGINE=InnoDB AUTO_INCREMENT=886 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.tb_menu
CREATE TABLE IF NOT EXISTS `tb_menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT '0',
  `module` varchar(50) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `menu_name` varchar(100) DEFAULT NULL,
  `menu_type` char(10) DEFAULT NULL,
  `role_id` varchar(100) DEFAULT NULL,
  `deep` smallint(2) DEFAULT NULL,
  `ordering` int(6) DEFAULT NULL,
  `position` enum('top','sidebar','both','settingbar','definitions') DEFAULT NULL,
  `menu_icons` varchar(50) DEFAULT NULL,
  `active` enum('0','1') DEFAULT '1',
  `access_data` text,
  `allow_guest` enum('0','1') DEFAULT '0',
  `menu_lang` text,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=219 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.tb_module
CREATE TABLE IF NOT EXISTS `tb_module` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(100) DEFAULT NULL,
  `module_title` varchar(100) DEFAULT NULL,
  `module_note` varchar(255) DEFAULT NULL,
  `module_author` varchar(100) DEFAULT NULL,
  `module_created` timestamp NULL DEFAULT NULL,
  `module_desc` text,
  `module_db` varchar(255) DEFAULT NULL,
  `module_db_key` varchar(100) DEFAULT NULL,
  `module_type` enum('master','report','proccess','core','generic','addon','ajax','native','blank') DEFAULT 'master',
  `module_config` longtext,
  `module_lang` text,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=263 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.tb_notification
CREATE TABLE IF NOT EXISTS `tb_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `note` text CHARACTER SET utf8,
  `created` datetime DEFAULT NULL,
  `icon` char(20) CHARACTER SET utf8 DEFAULT NULL,
  `is_read` enum('0','1') CHARACTER SET utf8 DEFAULT '0',
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.tb_owners
CREATE TABLE IF NOT EXISTS `tb_owners` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `domain` varchar(100) NOT NULL DEFAULT '',
  `subdomain` varchar(100) NOT NULL,
  `address` varchar(250) DEFAULT NULL,
  `telephone` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `facebook` varchar(50) DEFAULT NULL,
  `twitter` varchar(50) DEFAULT NULL,
  `instagram` varchar(50) DEFAULT NULL,
  `tripdavisor` varchar(50) DEFAULT NULL,
  `tagline` varchar(250) DEFAULT NULL,
  `description` varchar(2000) NOT NULL,
  `template_color` varchar(50) DEFAULT NULL,
  `meta_keyword` varchar(250) DEFAULT 'Umrah Tours and Travel',
  `meta_description` varchar(250) DEFAULT NULL,
  `group` int(11) DEFAULT NULL,
  `activation` varchar(50) DEFAULT NULL,
  `maintenance` varchar(11) DEFAULT NULL,
  `show_help` varchar(11) DEFAULT NULL,
  `show_testimonial` varchar(11) NOT NULL,
  `show_tour` varchar(11) NOT NULL,
  `multi_language` int(11) DEFAULT NULL,
  `default_language` varchar(11) DEFAULT NULL,
  `avail_language` varchar(11) NOT NULL,
  `default_currency` int(11) NOT NULL,
  `registration` varchar(11) DEFAULT NULL,
  `front` varchar(11) DEFAULT NULL,
  `captcha` varchar(11) DEFAULT NULL,
  `theme` varchar(11) DEFAULT 'modern',
  `mode` varchar(11) DEFAULT 'production',
  `logo` varchar(100) DEFAULT NULL,
  `header_image` varchar(100) DEFAULT NULL,
  `allow_ip` varchar(250) DEFAULT NULL,
  `restrict_ip` varchar(250) DEFAULT NULL,
  `date` varchar(11) DEFAULT 'd/m/y',
  `google_analytics` varchar(20) DEFAULT '173907604',
  `google_calendar` varchar(100) NOT NULL DEFAULT 'en.malaysia#holiday@group.v.calendar.google.com',
  PRIMARY KEY (`id`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.tb_pages
CREATE TABLE IF NOT EXISTS `tb_pages` (
  `pageID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `note` longtext,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `filename` varchar(50) DEFAULT NULL,
  `status` enum('enable','disable') DEFAULT 'enable',
  `access` text,
  `allow_guest` enum('0','1') DEFAULT '0',
  `template` enum('frontend','backend') DEFAULT 'frontend',
  `metakey` varchar(255) DEFAULT NULL,
  `metadesc` text,
  `default` enum('0','1') DEFAULT '0',
  `pagetype` enum('page','post') DEFAULT NULL,
  `labels` text,
  `views` int(11) DEFAULT '0',
  `userid` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `header` varchar(255) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`pageID`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.tb_users
CREATE TABLE IF NOT EXISTS `tb_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(6) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `login_attempt` tinyint(2) DEFAULT '0',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `reminder` varchar(64) DEFAULT NULL,
  `activation` varchar(50) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `last_activity` int(20) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=183 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.termsandconditions
CREATE TABLE IF NOT EXISTS `termsandconditions` (
  `tandcID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` text,
  `tandc` longtext,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT '1',
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`tandcID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.testimonials
CREATE TABLE IF NOT EXISTS `testimonials` (
  `testimonialID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `namesurname` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `country` varchar(200) DEFAULT NULL,
  `city` varchar(200) DEFAULT NULL,
  `tour_name` varchar(200) DEFAULT NULL,
  `tour_date` date DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL,
  `testimonial` longtext,
  `status` int(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`testimonialID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.todo
CREATE TABLE IF NOT EXISTS `todo` (
  `todoID` int(11) NOT NULL AUTO_INCREMENT,
  `todo` text CHARACTER SET utf8,
  `duedate` date DEFAULT NULL,
  `assignedto` int(11) DEFAULT NULL,
  `createdOn` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedOn` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `Status` int(1) DEFAULT NULL,
  `entry_by` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`todoID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.tours
CREATE TABLE IF NOT EXISTS `tours` (
  `tourID` int(11) NOT NULL AUTO_INCREMENT,
  `tour_name` varchar(200) NOT NULL,
  `tourcategoriesID` int(200) NOT NULL,
  `tour_description` longtext,
  `total_days` int(2) DEFAULT NULL,
  `total_nights` int(2) DEFAULT NULL,
  `departs` int(1) DEFAULT NULL,
  `featured` int(1) DEFAULT '0',
  `time` int(11) DEFAULT NULL,
  `flight` varchar(50) DEFAULT NULL,
  `transit` varchar(50) DEFAULT NULL,
  `baggage_limit` varchar(50) DEFAULT NULL,
  `sector` varchar(50) DEFAULT NULL,
  `multicountry` int(1) DEFAULT '0',
  `countryID` int(11) DEFAULT NULL,
  `similartours` varchar(200) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `payment_options` varchar(100) DEFAULT NULL,
  `policyandterms` int(11) DEFAULT NULL,
  `inclusions` varchar(200) DEFAULT '',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `views` int(11) DEFAULT '0',
  `tourimage` varchar(255) DEFAULT NULL,
  `gallery` varchar(10000) DEFAULT NULL,
  `currencyID` int(11) NOT NULL,
  `cost_single` int(11) NOT NULL DEFAULT '0',
  `cost_double` int(11) NOT NULL DEFAULT '0',
  `cost_triple` int(11) NOT NULL DEFAULT '0',
  `cost_quad` int(11) NOT NULL DEFAULT '0',
  `cost_child` int(11) NOT NULL DEFAULT '0',
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`tourID`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.tours_daily
CREATE TABLE IF NOT EXISTS `tours_daily` (
  `dailytourID` int(11) NOT NULL AUTO_INCREMENT,
  `dailytour_name` varchar(200) NOT NULL DEFAULT '',
  `tourcategoriesID` int(200) NOT NULL,
  `tour_description` longtext,
  `capacity` int(11) DEFAULT NULL,
  `startsat` int(11) DEFAULT NULL,
  `duration` int(2) DEFAULT NULL,
  `departs` varchar(255) DEFAULT NULL,
  `cost_adult` int(11) DEFAULT NULL,
  `cost_child` int(11) DEFAULT NULL,
  `currency` int(11) DEFAULT NULL,
  `similartours` varchar(200) DEFAULT NULL,
  `payment_options` varchar(100) DEFAULT NULL,
  `policyandterms` int(11) DEFAULT NULL,
  `inclusions` varchar(200) DEFAULT '',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `views` int(11) DEFAULT '0',
  `tourimage` varchar(255) DEFAULT NULL,
  `gallery` varchar(10000) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`dailytourID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.tours_daily_timeline
CREATE TABLE IF NOT EXISTS `tours_daily_timeline` (
  `TimelineID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `DtID` int(11) DEFAULT NULL,
  `Title` varchar(255) DEFAULT NULL,
  `Timestart` varchar(11) DEFAULT NULL,
  `Timeend` varchar(11) DEFAULT NULL,
  `Icon` int(11) DEFAULT NULL,
  `Description` text,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`TimelineID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.tours_expenses
CREATE TABLE IF NOT EXISTS `tours_expenses` (
  `tourexpenseID` int(11) NOT NULL AUTO_INCREMENT,
  `tourID` int(11) NOT NULL,
  `expensename` varchar(300) NOT NULL DEFAULT '',
  `cost` int(11) NOT NULL,
  `currency` int(11) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`tourexpenseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.tour_date
CREATE TABLE IF NOT EXISTS `tour_date` (
  `tourdateID` int(11) NOT NULL AUTO_INCREMENT,
  `tourcategoriesID` int(11) DEFAULT NULL,
  `tourID` int(50) NOT NULL,
  `tour_code` varchar(10) NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `guideID` int(11) NOT NULL,
  `featured` int(1) DEFAULT '0',
  `definite_departure` int(1) DEFAULT '0',
  `total_capacity` int(10) DEFAULT NULL,
  `currencyID` int(11) DEFAULT NULL,
  `cost_single` int(11) DEFAULT NULL,
  `available_single` int(11) DEFAULT NULL,
  `cost_double` int(11) DEFAULT NULL,
  `available_double` int(11) DEFAULT NULL,
  `cost_triple` int(11) DEFAULT NULL,
  `available_triple` int(11) DEFAULT NULL,
  `cost_quad` int(11) DEFAULT NULL,
  `available_quad` int(11) DEFAULT NULL,
  `cost_quint` int(11) DEFAULT NULL,
  `available_quint` int(11) DEFAULT NULL,
  `cost_sext` int(11) DEFAULT NULL,
  `available_sext` int(11) DEFAULT NULL,
  `cost_child` int(11) DEFAULT NULL,
  `cost_deposit` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '1',
  `remarks` longtext,
  `color` varchar(10) DEFAULT '#4c5667',
  `owner_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tourdateID`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.tour_detail
CREATE TABLE IF NOT EXISTS `tour_detail` (
  `tourdetailID` int(11) NOT NULL AUTO_INCREMENT,
  `tourID` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `countryID` int(11) NOT NULL,
  `cityID` int(11) NOT NULL,
  `hotelID` int(11) NOT NULL,
  `siteID` varchar(200) DEFAULT NULL,
  `meal` varchar(200) DEFAULT '',
  `optionaltourID` varchar(200) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` longtext,
  `icon` varchar(200) DEFAULT 'fa-circle-o-notch',
  `image` varchar(200) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`tourdetailID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.transfers_arrival
CREATE TABLE IF NOT EXISTS `transfers_arrival` (
  `transfer_arrivalID` int(11) NOT NULL AUTO_INCREMENT,
  `arrivalcountry` int(11) DEFAULT NULL,
  `arrivalcity` int(11) DEFAULT NULL,
  `arrivaldate` datetime DEFAULT NULL,
  `arrivalairport` int(11) DEFAULT NULL,
  `arrivalflightno` varchar(10) DEFAULT NULL,
  `transfer_date` datetime DEFAULT NULL,
  `transfer_man` int(11) DEFAULT NULL,
  `arrival_note` text,
  `tourname` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `entry_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`transfer_arrivalID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.transfers_departure
CREATE TABLE IF NOT EXISTS `transfers_departure` (
  `transfer_departureID` int(11) NOT NULL AUTO_INCREMENT,
  `countryID` int(11) DEFAULT NULL,
  `cityID` int(11) DEFAULT NULL,
  `departuredate` datetime DEFAULT NULL,
  `departureairport` int(11) DEFAULT NULL,
  `departureflightno` varchar(10) DEFAULT NULL,
  `departuretransferdate` datetime DEFAULT NULL,
  `departuretransferman` int(11) DEFAULT NULL,
  `departuretransfernotes` text,
  `tourname` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `entry_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`transfer_departureID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.travellers
CREATE TABLE IF NOT EXISTS `travellers` (
  `travellerID` int(11) NOT NULL AUTO_INCREMENT,
  `nameandsurname` varchar(100) DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `phone` varchar(100) DEFAULT '',
  `address` tinytext,
  `city` varchar(200) DEFAULT NULL,
  `countryID` int(3) DEFAULT NULL,
  `dateofbirth` date DEFAULT NULL,
  `passportno` varchar(30) DEFAULT NULL,
  `NRIC` varchar(30) DEFAULT NULL,
  `passportissue` date DEFAULT NULL,
  `passportexpiry` date DEFAULT NULL,
  `passportcountry` int(3) DEFAULT NULL,
  `bedconfiguration` int(10) DEFAULT NULL,
  `dietaryrequirements` varchar(100) DEFAULT NULL,
  `emergencycontactname` varchar(100) DEFAULT NULL,
  `emergencycontanphone` varchar(100) DEFAULT NULL,
  `emergencycontactemail` varchar(100) DEFAULT NULL,
  `insurancecompany` varchar(100) DEFAULT NULL,
  `insurancecompanyphone` varchar(100) DEFAULT NULL,
  `insurancepolicyno` varchar(100) DEFAULT NULL,
  `interests` varchar(400) DEFAULT NULL,
  `mahram_id` int(11) NOT NULL DEFAULT '0',
  `mahram_relation` int(11) NOT NULL DEFAULT '0',
  `image` varchar(200) DEFAULT 'no-profile-image.jpg',
  `status` tinyint(1) DEFAULT '1',
  `roomtype` varchar(255) DEFAULT NULL,
  `entry_by` int(11) DEFAULT '1',
  `owner_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`travellerID`)
) ENGINE=InnoDB AUTO_INCREMENT=327 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.travellers_files
CREATE TABLE IF NOT EXISTS `travellers_files` (
  `fileID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `travellerID` int(11) DEFAULT NULL,
  `file_type` int(11) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `remarks` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `entry_by` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`fileID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.travellers_note
CREATE TABLE IF NOT EXISTS `travellers_note` (
  `travellers_noteID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` text,
  `travellerID` int(11) DEFAULT NULL,
  `note` longtext,
  `style` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `entry_by` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`travellers_noteID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.travel_agent
CREATE TABLE IF NOT EXISTS `travel_agent` (
  `travelagentID` int(11) NOT NULL AUTO_INCREMENT,
  `agency_name` varchar(200) NOT NULL DEFAULT '',
  `legalname` varchar(200) DEFAULT NULL,
  `affiliatelink` varchar(255) DEFAULT NULL,
  `agency_code` varchar(200) DEFAULT NULL,
  `agency_licence_code` varchar(200) DEFAULT NULL,
  `personincontact` varchar(200) DEFAULT NULL,
  `mobilephone` varchar(200) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `website` varchar(250) DEFAULT NULL,
  `agent_logo` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT '',
  `fax` varchar(20) DEFAULT '',
  `countryID` int(11) NOT NULL,
  `cityID` int(11) NOT NULL,
  `address` text NOT NULL,
  `bankname` varchar(200) DEFAULT NULL,
  `ibancode` varchar(200) DEFAULT NULL,
  `holder_name` varchar(200) DEFAULT NULL,
  `vatno` varchar(200) DEFAULT NULL,
  `commissionrate` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`travelagentID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.travel_agent_agent
CREATE TABLE IF NOT EXISTS `travel_agent_agent` (
  `agentID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `travel_agency` int(11) DEFAULT NULL,
  `agent_name` varchar(200) DEFAULT NULL,
  `agent_code` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `phone` varchar(200) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `entry_by` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`agentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.travel_agent_notes
CREATE TABLE IF NOT EXISTS `travel_agent_notes` (
  `travelagentnoteID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `travelagentID` int(11) DEFAULT NULL,
  `title` text,
  `note` longtext,
  `style` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `entry_by` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`travelagentnoteID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.visaapplications
CREATE TABLE IF NOT EXISTS `visaapplications` (
  `applicationID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `travellersID` varchar(220) DEFAULT NULL,
  `countryID` int(11) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `duration2` int(11) DEFAULT NULL,
  `applicationdate` date DEFAULT NULL,
  `processintime` int(11) DEFAULT NULL,
  `payment` int(11) DEFAULT NULL,
  `currencyID` int(11) DEFAULT NULL,
  `paymenttypeID` int(11) DEFAULT NULL,
  `applicationfee` int(11) DEFAULT NULL,
  `currencyID2` int(11) DEFAULT NULL,
  `documents` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `visaexpirydate` date DEFAULT NULL,
  `rejectreason` text,
  `remarks` text,
  `entry_by` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`applicationID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table oomrah_app.visitor_registry
CREATE TABLE IF NOT EXISTS `visitor_registry` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `clicks` int(10) unsigned NOT NULL DEFAULT '0',
  `owner_id` int(4) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
