-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 27, 2018 at 10:59 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `minehub`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertCustomer` (IN `name` VARCHAR(250), IN `country` VARCHAR(250), IN `email` VARCHAR(250), IN `telephone` VARCHAR(250), IN `gender` VARCHAR(250), IN `user_name` VARCHAR(250), IN `password` VARCHAR(250), IN `token` VARCHAR(250), IN `account` VARCHAR(250), IN `sponsor_account` VARCHAR(250), IN `status` VARCHAR(250), IN `activation` VARCHAR(250), IN `platform` TINYINT(4))  insertCustomer: BEGIN
        DECLARE success INT(11);
        DECLARE approve_bill, custBillCount, customerId,lastInsertedId INT(11);
        DECLARE responseMessage VARCHAR(250) DEFAULT '';

        
            SET success = 0;
            SET lastInsertedId = 0;
            
                
                START TRANSACTION;
                IF NOT EXISTS (SELECT * FROM `users` WHERE  Username=user_name AND Password=password order by id desc limit 1) THEN

                INSERT INTO users (Fullname, Country, Email, Telephone, Gender, Username, Password, Sponsor, Token, Account, Status, Activation, treestatus,platform) VALUES(name,country,email,telephone,gender,user_name,password,sponsor_account,token,account,status,activation,'notree',platform);
                SET lastInsertedId = LAST_INSERT_ID(); 
                SET customerId = LAST_INSERT_ID();
                INSERT INTO accountbalance (Balance, Username) VALUES('0',user_name);
                INSERT INTO binaryincome(userid, day_bal, current_bal, total_bal) VALUES(user_name,'0','0','0');
                INSERT INTO hubcoin (Balance, Username) VALUES('0',user_name);
                INSERT INTO team (Balance, Username) VALUES('0',user_name);
                INSERT INTO teamvolume (Balance, Username) VALUES('0',user_name);
                INSERT INTO rank (Rank, Rankid, Username, Sponsor) VALUES('Miner','1',user_name,sponsor_account);
                INSERT INTO mining (Balance, Username) VALUES('0',user_name);
                INSERT INTO commission (Balance, Username) VALUES('0',user_name);
                INSERT INTO starterpack (PurchaseDate, MiningDate, Username, Status, CompletionDate, TotalMinable,Withdrawal, Comment) VALUES('0', '0', user_name, 'Inactive', '0', '547.50', '0', 'Not-Purchased');
                INSERT INTO minipack (PurchaseDate, MiningDate, Username, Status, CompletionDate, TotalMinable,Withdrawal, Comment) VALUES('0', '0', user_name, 'Inactive', '0', '1095', '0', 'Not-Purchased');
                INSERT INTO mediumpack (PurchaseDate, MiningDate, Username, Status, CompletionDate, TotalMinable,Withdrawal, Comment) VALUES('0', '0', user_name, 'Inactive', '0', '2190', '0', 'Not-Purchased');
                INSERT INTO grandpack (PurchaseDate, MiningDate, Username, Status, CompletionDate, TotalMinable,Withdrawal, Comment) VALUES('0', '0', user_name, 'Inactive', '0', '4380', '0', 'Not-Purchased');
                INSERT INTO ultimatepack (PurchaseDate, MiningDate, Username, Status, CompletionDate, TotalMinable, Withdrawal, Comment) VALUES('0', '0', user_name, 'Inactive', '0', '8760', '0', 'Not-Purchased');
                INSERT INTO register (EntryDate, Amount, Username) VALUES('0', '0', user_name);


                
                IF lastInsertedId = 0 THEN
                        SET responseMessage = 'Problem to add customer.';
                        SET success = 0;
                        ROLLBACK;
                        SELECT success AS response,lastInsertedId,responseMessage; 
                        LEAVE insertCustomer;		
                ELSE    
                        SET responseMessage = 'Customer added succesfully.';
                        SET success = 1;
                    	COMMIT;
                        SELECT success AS response,lastInsertedId,responseMessage; 
                        LEAVE insertCustomer;

                END IF;
            ELSE 
                SET responseMessage = 'Customer already exist.';
                SET success = 0;
            END IF;
        SELECT success AS response, lastInsertedId,responseMessage; 
        
        COMMIT;   
        END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `accountbalance`
--

CREATE TABLE `accountbalance` (
  `id` int(100) NOT NULL,
  `Balance` varchar(250) NOT NULL,
  `Username` varchar(250) NOT NULL,
  `Register` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accountbalance`
--

INSERT INTO `accountbalance` (`id`, `Balance`, `Username`, `Register`, `created_at`, `updated_at`) VALUES
(88, '0', 'mshai', '', '2018-08-27 19:38:14', '2018-08-27 19:38:14'),
(89, '0', 'meettomangesh@gmail.com', '', '2018-08-27 19:38:14', '2018-08-27 19:38:14'),
(90, '0', 'test1@gmail.com', '', '2018-08-27 19:38:14', '2018-08-27 19:38:14'),
(91, '0', 'test2@gmail.com', '', '2018-08-27 19:38:14', '2018-08-27 19:38:14'),
(92, '0', 'test6@gmail.com', '', '2018-08-27 23:52:22', '2018-08-27 23:52:22'),
(93, '0', 'test7@gmail.com', '', '2018-08-28 00:06:51', '2018-08-28 00:06:51'),
(94, '0', 'test8@gmail.com', '', '2018-08-28 00:20:59', '2018-08-28 00:20:59');

-- --------------------------------------------------------

--
-- Table structure for table `binaryincome`
--

CREATE TABLE `binaryincome` (
  `id` int(11) NOT NULL,
  `userid` varchar(250) NOT NULL,
  `day_bal` varchar(250) NOT NULL,
  `current_bal` varchar(250) NOT NULL,
  `total_bal` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `binaryincome`
--

INSERT INTO `binaryincome` (`id`, `userid`, `day_bal`, `current_bal`, `total_bal`, `created_at`, `updated_at`) VALUES
(44, 'mshai', '0', '0', '0', '2018-08-27 19:39:00', '2018-08-27 19:39:00'),
(45, 'meettomangesh@gmail.com', '0', '0', '0', '2018-08-27 19:39:00', '2018-08-27 19:39:00'),
(46, 'test1@gmail.com', '0', '0', '0', '2018-08-27 19:39:00', '2018-08-27 19:39:00'),
(47, 'test2@gmail.com', '0', '0', '0', '2018-08-27 19:39:00', '2018-08-27 19:39:00'),
(48, 'test6@gmail.com', '0', '0', '0', '2018-08-27 23:52:22', '2018-08-27 23:52:22'),
(49, 'test7@gmail.com', '0', '0', '0', '2018-08-28 00:06:51', '2018-08-28 00:06:51'),
(50, 'test8@gmail.com', '0', '0', '0', '2018-08-28 00:20:59', '2018-08-28 00:20:59');

-- --------------------------------------------------------

--
-- Table structure for table `bmp_wallet`
--

CREATE TABLE `bmp_wallet` (
  `id` int(11) NOT NULL,
  `user_name` varchar(250) DEFAULT NULL,
  `email_address` varchar(250) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL,
  `label` varchar(250) DEFAULT NULL,
  `guid` varchar(250) DEFAULT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1:Active 2:Inactive',
  `is_register_for_bmp` enum('1','2') NOT NULL DEFAULT '2' COMMENT '1:Yes 2:No',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bmp_wallet`
--

INSERT INTO `bmp_wallet` (`id`, `user_name`, `email_address`, `address`, `password`, `label`, `guid`, `status`, `is_register_for_bmp`, `created_at`, `updated_at`) VALUES
(1, 'test8@gmail.com', 'test4@gmail.com', NULL, '7890', 'Main address of wallet of test8@gmail.com', '7e40a36a-d61a-4636-aa0e-a4ed3b06d237', '1', '2', '2018-08-28 01:36:59', '2018-08-28 01:36:59'),
(2, 'test8@gmail.com', 'test4@gmail.com', NULL, '7890', 'Main address of wallet of test8@gmail.com', '7e40a36a-d61a-4636-aa0e-a4ed3b06d237', '1', '2', '2018-08-28 01:38:46', '2018-08-28 01:38:46'),
(3, 'test8@gmail.com', 'test4@gmail.com', '18SPT5NUNzkvibfw9J1ANkaF1y5NRFm1KS', '7890', 'Main address of wallet of test8@gmail.com', '7e40a36a-d61a-4636-aa0e-a4ed3b06d237', '1', '2', '2018-08-28 01:39:29', '2018-08-28 01:39:29'),
(4, 'test8@gmail.com', 'test4@gmail.com', '18SPT5NUNzkvibfw9J1ANkaF1y5NRFm1KS', '7u8i9o0p', 'Main address of wallet of test8@gmail.com', '7e40a36a-d61a-4636-aa0e-a4ed3b06d237', '1', '2', '2018-08-28 01:40:29', '2018-08-28 01:40:29');

-- --------------------------------------------------------

--
-- Table structure for table `bmp_wallet_sent_receive_transactions`
--

CREATE TABLE `bmp_wallet_sent_receive_transactions` (
  `id` int(11) NOT NULL,
  `user_name` varchar(250) DEFAULT NULL,
  `invoice_id` varchar(250) DEFAULT NULL,
  `sent_receive_flag` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1:Sent 2:Receive',
  `amount` varchar(250) DEFAULT NULL,
  `from_address` varchar(250) DEFAULT NULL,
  `to_address` varchar(250) DEFAULT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1:Unpaid/Pending 2:Paid',
  `response` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `commission`
--

CREATE TABLE `commission` (
  `id` int(11) NOT NULL,
  `Balance` varchar(250) NOT NULL,
  `Username` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `commission`
--

INSERT INTO `commission` (`id`, `Balance`, `Username`, `created_at`, `updated_at`) VALUES
(56, '120', 'mshai', '2018-08-27 19:46:46', '2018-08-27 19:46:46'),
(57, '0', 'meettomangesh@gmail.com', '2018-08-27 19:46:46', '2018-08-27 19:46:46'),
(58, '0', 'test1@gmail.com', '2018-08-27 19:46:46', '2018-08-27 19:46:46'),
(59, '0', 'test2@gmail.com', '2018-08-27 19:46:46', '2018-08-27 19:46:46'),
(60, '0', 'test6@gmail.com', '2018-08-27 23:52:22', '2018-08-27 23:52:22'),
(61, '0', 'test7@gmail.com', '2018-08-28 00:06:51', '2018-08-28 00:06:51'),
(62, '0', 'test8@gmail.com', '2018-08-28 00:20:59', '2018-08-28 00:20:59');

-- --------------------------------------------------------

--
-- Table structure for table `dailymine`
--

CREATE TABLE `dailymine` (
  `id` int(11) NOT NULL,
  `Date` varchar(250) NOT NULL,
  `Pack` varchar(250) NOT NULL,
  `Btc` varchar(250) NOT NULL,
  `Usd` varchar(250) NOT NULL,
  `Status` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `grandpack`
--

CREATE TABLE `grandpack` (
  `id` int(11) NOT NULL,
  `PurchaseDate` varchar(250) NOT NULL,
  `MiningDate` varchar(250) NOT NULL,
  `Username` varchar(250) NOT NULL,
  `Status` varchar(250) NOT NULL,
  `CompletionDate` varchar(250) NOT NULL,
  `TotalMinable` varchar(250) NOT NULL,
  `Withdrawal` varchar(250) NOT NULL,
  `Comment` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `grandpack`
--

INSERT INTO `grandpack` (`id`, `PurchaseDate`, `MiningDate`, `Username`, `Status`, `CompletionDate`, `TotalMinable`, `Withdrawal`, `Comment`, `created_at`, `updated_at`) VALUES
(55, '0', '0', 'mshai', 'Inactive', '0', '4380', '0', 'Not-Purchased', '2018-08-27 19:49:24', '2018-08-27 19:49:24'),
(56, '0', '0', 'meettomangesh@gmail.com', 'Inactive', '0', '4380', '0', 'Not-Purchased', '2018-08-27 19:49:24', '2018-08-27 19:49:24'),
(57, '0', '0', 'test1@gmail.com', 'Inactive', '0', '4380', '0', 'Not-Purchased', '2018-08-27 19:49:24', '2018-08-27 19:49:24'),
(58, '0', '0', 'test2@gmail.com', 'Inactive', '0', '4380', '0', 'Not-Purchased', '2018-08-27 19:49:24', '2018-08-27 19:49:24'),
(59, '0', '0', 'test6@gmail.com', 'Inactive', '0', '4380', '0', 'Not-Purchased', '2018-08-27 23:52:22', '2018-08-27 23:52:22'),
(60, '0', '0', 'test7@gmail.com', 'Inactive', '0', '4380', '0', 'Not-Purchased', '2018-08-28 00:06:51', '2018-08-28 00:06:51'),
(61, '0', '0', 'test8@gmail.com', 'Inactive', '0', '4380', '0', 'Not-Purchased', '2018-08-28 00:20:59', '2018-08-28 00:20:59');

-- --------------------------------------------------------

--
-- Table structure for table `hangbtc`
--

CREATE TABLE `hangbtc` (
  `id` int(11) NOT NULL,
  `SendDate` varchar(250) NOT NULL,
  `Username` varchar(250) NOT NULL,
  `Btcamount` varchar(250) NOT NULL,
  `Btcaddress` varchar(250) NOT NULL,
  `Purpose` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hubcoin`
--

CREATE TABLE `hubcoin` (
  `id` int(100) NOT NULL,
  `Balance` varchar(250) NOT NULL,
  `Username` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hubcoin`
--

INSERT INTO `hubcoin` (`id`, `Balance`, `Username`, `created_at`, `updated_at`) VALUES
(88, '0', 'mshai', '2018-08-27 19:39:56', '2018-08-27 19:39:56'),
(89, '0', 'meettomangesh@gmail.com', '2018-08-27 19:39:56', '2018-08-27 19:39:56'),
(90, '0', 'test1@gmail.com', '2018-08-27 19:39:56', '2018-08-27 19:39:56'),
(91, '0', 'test2@gmail.com', '2018-08-27 19:39:56', '2018-08-27 19:39:56'),
(92, '0', 'test6@gmail.com', '2018-08-27 23:52:22', '2018-08-27 23:52:22'),
(93, '0', 'test7@gmail.com', '2018-08-28 00:06:51', '2018-08-28 00:06:51'),
(94, '0', 'test8@gmail.com', '2018-08-28 00:20:59', '2018-08-28 00:20:59');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int(100) NOT NULL,
  `Paydate` varchar(250) NOT NULL,
  `Invoiceid` varchar(250) NOT NULL,
  `Purpose` varchar(250) NOT NULL,
  `Btcaddress` varchar(250) NOT NULL,
  `Amount` varchar(250) NOT NULL,
  `Btcamount` varchar(250) NOT NULL,
  `Status` varchar(250) NOT NULL,
  `Username` varchar(250) NOT NULL,
  `api_response` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id`, `Paydate`, `Invoiceid`, `Purpose`, `Btcaddress`, `Amount`, `Btcamount`, `Status`, `Username`, `api_response`, `created_at`, `updated_at`) VALUES
(63, '22-07-2018', '2591182', 'Registration', '1FM3mZdwvfYGBYBhwD3jEj8c6YvH6KFm28', '100', '0.01407895', 'Paid', 'mshai', NULL, '2018-08-12 14:15:32', '2018-08-12 14:15:32'),
(73, '15-08-2018', '8443413', 'Registration', '1GVq2C3rZezXgU4cTvkXcjPVxSvUpRe6zc', '100', '0.01663408', 'Paid', 'test1@gmail.com', '{\n  \"address\" : \"1GVq2C3rZezXgU4cTvkXcjPVxSvUpRe6zc\",\n  \"index\" : 36,\n  \"callback\" : \"https://bitminepool.com/bitcoin_system/production/payment/callback.php?invoice=8443413&secret=10081988Mangesh\"\n}', '2018-08-15 13:28:11', '2018-08-15 13:28:11'),
(65, '22-07-2018', '3801286', 'Starter', '1CPvdSmjvQt48ZiCYUnEMywPf1og1K3nwE', '300', '0.04116418', 'Paid', 'mshai', NULL, '2018-08-12 14:15:32', '2018-08-12 14:15:32'),
(66, '22-07-2018', '4237888', 'Starter', '1GxnZhQdwVwgg87JrrsKMtW3dVKmY7uezB', '300', '0.04116418', 'Unpaid', 'mshai', NULL, '2018-08-12 14:15:32', '2018-08-12 14:15:32'),
(68, '13-08-2018', '1234', 'Registration', '18jDWHD6ono1FyGf4eDKF4reQu9ZAkMGCj', '100', '0.1234', 'Paid', 'test@gmail.com', '{\"btc_address\":\"18jDWHD6ono1FyGf4eDKF4reQu9ZAkMGCj\",\"index\":8,\"callback\":\"https:\\/\\/bitminepool.com\\/bitcoin_system\\/production\\/payment\\/callback.php?invoice=1234&secret=10081988Bmp\"}', '2018-08-13 23:52:00', '2018-08-13 23:52:00'),
(69, '13-08-2018', '1234', 'Registration', '17EH21T3dyZ1h8RJCsGbNE2scy3NvBCkRA', '100', '0.1234', 'Paid', 'test2@gmail.com', 'Exception: The environment parameters are missing. in G:\\xampp\\htdocs\\bmp_api\\src\\Controllers\\ReceiveController.php:34\nStack trace:\n#0 G:\\xampp\\htdocs\\bmp_api\\src\\Bootstrap.php(67): Api\\Controllers\\ReceiveController->generateAddressToRecivePayment(Array)\n#1 G:\\xampp\\htdocs\\bmp_api\\public\\index.php(13): require(\'G:\\\\xampp\\\\htdocs...\')\n#2 {main}', '2018-08-13 23:59:10', '2018-08-13 23:59:10'),
(70, '13-08-2018', '1234', 'Registration', '17EH21T3dyZ1h8RJCsGbNE2scy3NvBCkRA', '100', '0.1234', 'Unpaid', 'test3@gmail.com', 'Exception: The environment parameters are missing. in G:\\xampp\\htdocs\\bmp_api\\src\\Controllers\\ReceiveController.php:34\nStack trace:\n#0 G:\\xampp\\htdocs\\bmp_api\\src\\Bootstrap.php(67): Api\\Controllers\\ReceiveController->generateAddressToRecivePayment(Array)\n#1 G:\\xampp\\htdocs\\bmp_api\\public\\index.php(13): require(\'G:\\\\xampp\\\\htdocs...\')\n#2 {main}', '2018-08-14 00:04:22', '2018-08-14 00:04:22'),
(71, '13-08-2018', '1234', 'Registration', '17EH21T3dyZ1h8RJCsGbNE2scy3NvBCkRA', '100', '0.1234', 'Paid', 'test4@gmail.com', '{\"btc_address\":\"17EH21T3dyZ1h8RJCsGbNE2scy3NvBCkRA\",\"index\":9,\"callback\":\"https:\\/\\/bitminepool.com\\/bitcoin_system\\/production\\/payment\\/callback.php?invoice=1234&secret=10081988Bmp\"}', '2018-08-14 00:05:59', '2018-08-14 00:05:59'),
(72, '13-08-2018', '2643300', 'Registration', '1NoQ8bV2r3wjZ4jUqvGfq3ut4eLobz6KPL', '100', '0.01672678', 'Paid', 'meettomangesh@gmail.com', NULL, '2018-08-14 00:35:34', '2018-08-14 00:35:34'),
(74, '15-08-2018', '4291339', 'Starter', '1LvyrttP3uoG7xSCjWqn5e46bRfZqmnsq4', '300', '0.04772899', 'Paid', 'meettomangesh2@gmail.com', '{\n  \"address\" : \"1LvyrttP3uoG7xSCjWqn5e46bRfZqmnsq4\",\n  \"index\" : 37,\n  \"callback\" : \"https://bitminepool.com/bitcoin_system/production/payment/callback.php?invoice=4291339&secret=10081988Mangesh\"\n}', '2018-08-15 15:24:42', '2018-08-15 15:24:42'),
(75, '15-08-2018', '2376757', 'Starter', '1AMWyUqCd8AT8HZrbzMYJQS14668cSff1T', '300', '0.04772899', 'Unpaid', 'meettomangesh@gmail.com', '{\n  \"address\" : \"1AMWyUqCd8AT8HZrbzMYJQS14668cSff1T\",\n  \"index\" : 38,\n  \"callback\" : \"https://bitminepool.com/bitcoin_system/production/payment/callback.php?invoice=2376757&secret=10081988Mangesh\"\n}', '2018-08-15 15:48:15', '2018-08-15 15:48:15');

-- --------------------------------------------------------

--
-- Table structure for table `mediumpack`
--

CREATE TABLE `mediumpack` (
  `id` int(11) NOT NULL,
  `PurchaseDate` varchar(250) NOT NULL,
  `MiningDate` varchar(250) NOT NULL,
  `Username` varchar(250) NOT NULL,
  `Status` varchar(250) NOT NULL,
  `CompletionDate` varchar(250) NOT NULL,
  `TotalMinable` varchar(250) NOT NULL,
  `Withdrawal` varchar(250) NOT NULL,
  `Comment` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mediumpack`
--

INSERT INTO `mediumpack` (`id`, `PurchaseDate`, `MiningDate`, `Username`, `Status`, `CompletionDate`, `TotalMinable`, `Withdrawal`, `Comment`, `created_at`, `updated_at`) VALUES
(55, '0', '0', 'mshai', 'Inactive', '0', '2190', '0', 'Not-Purchased', '2018-08-27 19:49:24', '2018-08-27 19:49:24'),
(56, '0', '0', 'meettomangesh@gmail.com', 'Inactive', '0', '2190', '0', 'Not-Purchased', '2018-08-27 19:49:24', '2018-08-27 19:49:24'),
(57, '0', '0', 'test1@gmail.com', 'Inactive', '0', '2190', '0', 'Not-Purchased', '2018-08-27 19:49:24', '2018-08-27 19:49:24'),
(58, '0', '0', 'test2@gmail.com', 'Inactive', '0', '2190', '0', 'Not-Purchased', '2018-08-27 19:49:24', '2018-08-27 19:49:24'),
(59, '0', '0', 'test6@gmail.com', 'Inactive', '0', '2190', '0', 'Not-Purchased', '2018-08-27 23:52:22', '2018-08-27 23:52:22'),
(60, '0', '0', 'test7@gmail.com', 'Inactive', '0', '2190', '0', 'Not-Purchased', '2018-08-28 00:06:51', '2018-08-28 00:06:51'),
(61, '0', '0', 'test8@gmail.com', 'Inactive', '0', '2190', '0', 'Not-Purchased', '2018-08-28 00:20:59', '2018-08-28 00:20:59');

-- --------------------------------------------------------

--
-- Table structure for table `mining`
--

CREATE TABLE `mining` (
  `id` int(100) NOT NULL,
  `Balance` varchar(250) NOT NULL,
  `Username` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mining`
--

INSERT INTO `mining` (`id`, `Balance`, `Username`, `created_at`, `updated_at`) VALUES
(56, '0', 'mshai', '2018-08-27 19:45:39', '2018-08-27 19:45:39'),
(57, '0', 'meettomangesh@gmail.com', '2018-08-27 19:45:39', '2018-08-27 19:45:39'),
(58, '0', 'test1@gmail.com', '2018-08-27 19:45:39', '2018-08-27 19:45:39'),
(59, '0', 'test2@gmail.com', '2018-08-27 19:45:39', '2018-08-27 19:45:39'),
(60, '0', 'test6@gmail.com', '2018-08-27 23:52:22', '2018-08-27 23:52:22'),
(61, '0', 'test7@gmail.com', '2018-08-28 00:06:51', '2018-08-28 00:06:51'),
(62, '0', 'test8@gmail.com', '2018-08-28 00:20:59', '2018-08-28 00:20:59');

-- --------------------------------------------------------

--
-- Table structure for table `minipack`
--

CREATE TABLE `minipack` (
  `id` int(11) NOT NULL,
  `PurchaseDate` varchar(250) NOT NULL,
  `MiningDate` varchar(250) NOT NULL,
  `Username` varchar(250) NOT NULL,
  `Status` varchar(250) NOT NULL,
  `CompletionDate` varchar(250) NOT NULL,
  `TotalMinable` varchar(250) NOT NULL,
  `Withdrawal` varchar(250) NOT NULL,
  `Comment` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `minipack`
--

INSERT INTO `minipack` (`id`, `PurchaseDate`, `MiningDate`, `Username`, `Status`, `CompletionDate`, `TotalMinable`, `Withdrawal`, `Comment`, `created_at`, `updated_at`) VALUES
(55, '0', '0', 'mshai', 'Inactive', '0', '1095', '0', 'Not-Purchased', '2018-08-27 19:48:19', '2018-08-27 19:48:19'),
(56, '0', '0', 'meettomangesh@gmail.com', 'Inactive', '0', '1095', '0', 'Not-Purchased', '2018-08-27 19:48:19', '2018-08-27 19:48:19'),
(57, '0', '0', 'test1@gmail.com', 'Inactive', '0', '1095', '0', 'Not-Purchased', '2018-08-27 19:48:19', '2018-08-27 19:48:19'),
(58, '0', '0', 'test2@gmail.com', 'Inactive', '0', '1095', '0', 'Not-Purchased', '2018-08-27 19:48:19', '2018-08-27 19:48:19'),
(59, '0', '0', 'test6@gmail.com', 'Inactive', '0', '1095', '0', 'Not-Purchased', '2018-08-27 23:52:22', '2018-08-27 23:52:22'),
(60, '0', '0', 'test7@gmail.com', 'Inactive', '0', '1095', '0', 'Not-Purchased', '2018-08-28 00:06:51', '2018-08-28 00:06:51'),
(61, '0', '0', 'test8@gmail.com', 'Inactive', '0', '1095', '0', 'Not-Purchased', '2018-08-28 00:20:59', '2018-08-28 00:20:59');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `access_token` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` varchar(80) DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scope` varchar(4000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`access_token`, `client_id`, `user_id`, `expires`, `scope`) VALUES
('3241da18cae0d9ee2cab4ca0633bfa507a56c673', 'web8989dsad8ff365fdg843839b', NULL, '2018-09-10 16:08:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_authorization_codes`
--

CREATE TABLE `oauth_authorization_codes` (
  `authorization_code` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` varchar(80) DEFAULT NULL,
  `redirect_uri` varchar(2000) DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scope` varchar(4000) DEFAULT NULL,
  `id_token` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `client_id` varchar(80) NOT NULL,
  `client_secret` varchar(80) DEFAULT NULL,
  `redirect_uri` varchar(2000) DEFAULT NULL,
  `grant_types` varchar(80) DEFAULT NULL,
  `scope` varchar(4000) DEFAULT NULL,
  `user_id` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`client_id`, `client_secret`, `redirect_uri`, `grant_types`, `scope`, `user_id`) VALUES
('web8989dsad8ff365fdg843839b', '4c7f6f8fa93ghwd4302c0ae8c4aweb', 'https://bitminepool.com/', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_jwt`
--

CREATE TABLE `oauth_jwt` (
  `client_id` varchar(80) NOT NULL,
  `subject` varchar(80) DEFAULT NULL,
  `public_key` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `refresh_token` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` varchar(80) DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scope` varchar(4000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_scopes`
--

CREATE TABLE `oauth_scopes` (
  `scope` varchar(80) NOT NULL,
  `is_default` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_users`
--

CREATE TABLE `oauth_users` (
  `username` varchar(80) NOT NULL,
  `password` varchar(80) DEFAULT NULL,
  `first_name` varchar(80) DEFAULT NULL,
  `last_name` varchar(80) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT NULL,
  `scope` varchar(4000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `Paydate` varchar(250) NOT NULL,
  `Payuser` varchar(250) NOT NULL,
  `Amountbtc` varchar(250) NOT NULL,
  `Amountusd` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `Paydate`, `Payuser`, `Amountbtc`, `Amountusd`) VALUES
(29, '2018-07-22', 'mshai', '0.01356391', '101.15882695'),
(30, '2018-07-22', 'meettomangesh@gmail.com', '0.01356391', '101.15882695'),
(31, '2018-07-22', 'mshai', '0.01356391', '101.15882695'),
(32, '2018-07-22', 'meettomangesh@gmail.com', '0.01356391', '101.15882695'),
(33, '2018-08-15', 'meettomangesh@gmail.com', '0', '0'),
(34, '2018-08-15', 'meettomangesh@gmail.com', '0.01584199', '0.00631234'),
(35, '2018-08-15', 'test1@gmail.com', '0.01584199', '0.00631234'),
(36, '2018-08-15', 'test4@gmail.com', '0.01584199', '0.00631234'),
(37, '2018-08-15', 'meettomangesh@gmail.com', '0.0155469', '0.00643215'),
(38, '2018-08-15', 'meettomangesh@gmail.com', '0.0155469', '0.00643215'),
(39, '2018-08-15', 'meettomangesh@gmail.com', '0.0155469', '0.00643215'),
(40, '2018-08-15', 'meettomangesh@gmail.com', '0.0155469', '0.00643215'),
(41, '2018-08-15', 'meettomangesh@gmail.com', '0.0155469', '0.00643215'),
(42, '2018-08-15', 'meettomangesh@gmail.com', '0.0155469', '0.00643215'),
(43, '2018-08-15', 'meettomangesh@gmail.com', '0.0155469', '0.00643215'),
(44, '2018-08-15', 'meettomangesh@gmail.com', '0.0155469', '0.00643215'),
(45, '2018-08-15', 'meettomangesh@gmail.com', '0.0155469', '0.00643215'),
(46, '2018-08-15', 'meettomangesh@gmail.com', '0.0155469', '0.00643215'),
(47, '2018-08-15', 'meettomangesh@gmail.com', '0.0155469', '0.00643215'),
(48, '2018-08-15', 'meettomangesh@gmail.com', '0.0155469', '0.00643215'),
(49, '2018-08-15', 'meettomangesh@gmail.com', '0.0155469', '0.00643215'),
(50, '2018-08-15', 'meettomangesh@gmail.com', '369.51206051', '152.87657538'),
(51, '2018-08-15', 'meettomangesh@gmail.com', '369.51206051', '152.87657538'),
(52, '2018-08-15', 'meettomangesh@gmail.com', '0.0466407', '0.01929645'),
(53, '2018-08-15', 'meettomangesh@gmail.com', '0.01579454', '0.0063313'),
(54, '2018-08-15', 'meettomangesh@gmail.com', '0.01579454', '0.0063313');

-- --------------------------------------------------------

--
-- Table structure for table `payment_callback_log`
--

CREATE TABLE `payment_callback_log` (
  `id` int(10) NOT NULL,
  `username` varchar(250) DEFAULT NULL,
  `invoice_id` varchar(250) DEFAULT NULL,
  `amount_btc` varchar(250) DEFAULT NULL,
  `current_amount_btc` varchar(250) DEFAULT NULL,
  `amount_usd` varchar(250) DEFAULT NULL,
  `response` text,
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1:Unpaid/Pending',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_callback_log`
--

INSERT INTO `payment_callback_log` (`id`, `username`, `invoice_id`, `amount_btc`, `current_amount_btc`, `amount_usd`, `response`, `status`, `created_at`) VALUES
(2, 'meettomangesh@gmail.com', '4291339', '0.04772899', '0.0155469', '0.00643215', '{\"invoice\":\"4291339\",\"transaction_hash\":\"ttyytuytf\",\"secret\":\"10081988Mangesh\",\"value\":\"100\"}', '1', '2018-08-15 15:37:35'),
(3, 'meettomangesh@gmail.com', '2643300', '0.01672678', '0.0155469', '0.00643215', '{\"invoice\":\"2643300\",\"transaction_hash\":\"ttyytuytf\",\"secret\":\"10081988Mangesh\",\"value\":\"100\"}', '1', '2018-08-15 15:46:45'),
(4, 'meettomangesh@gmail.com', '2376757', '0.04772899', '369.51206051', '152.87657538', '{\"invoice\":\"2376757\",\"transaction_hash\":\"ttyytuytf\",\"secret\":\"10081988Mangesh\",\"value\":\"2376757\"}', '1', '2018-08-15 15:49:22'),
(5, 'meettomangesh@gmail.com', '2376757', '0.04772899', '0.0466407', '300 - ', '{\"invoice\":\"2376757\",\"transaction_hash\":\"ttyytuytf\",\"secret\":\"10081988Mangesh\",\"value\":\"300\"}', '1', '2018-08-15 16:02:56'),
(6, 'meettomangesh@gmail.com', '2643300', '0.01672678', '0.01579454', '100', '{\"invoice\":\"2643300\",\"transaction_hash\":\"ttyytuytf\",\"secret\":\"10081988Mangesh\",\"value\":\"100\"}', '1', '2018-08-15 16:45:29'),
(7, 'meettomangesh@gmail.com', '2643300', '0.01672678', '0.01579454', '100', '{\"invoice\":\"2643300\",\"transaction_hash\":\"ttyytuytf\",\"secret\":\"10081988Mangesh\",\"value\":\"100\",\"confirmations\":\"4\"}', '2', '2018-08-15 16:47:04');

-- --------------------------------------------------------

--
-- Table structure for table `rank`
--

CREATE TABLE `rank` (
  `id` int(100) NOT NULL,
  `Rank` varchar(250) NOT NULL,
  `Rankid` varchar(50) NOT NULL,
  `Username` varchar(250) NOT NULL,
  `Sponsor` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rank`
--

INSERT INTO `rank` (`id`, `Rank`, `Rankid`, `Username`, `Sponsor`, `created_at`, `updated_at`) VALUES
(59, 'Miner', '1', 'mshai', 'mshai', '2018-08-27 19:42:41', '2018-08-27 19:42:41'),
(60, 'Dealer', '2', 'meettomangesh@gmail.com', 'mshai ', '2018-08-27 19:42:41', '2018-08-27 19:42:41'),
(61, 'Miner', '1', 'test1@gmail.com', 'meettomangesh@gmail.com ', '2018-08-27 19:42:41', '2018-08-27 19:42:41'),
(62, 'Miner', '1', 'test2@gmail.com', 'meettomangesh@gmail.com ', '2018-08-27 19:42:41', '2018-08-27 19:42:41'),
(63, 'Miner', '1', 'test6@gmail.com', 'mshai', '2018-08-27 23:52:22', '2018-08-27 23:52:22'),
(64, 'Miner', '1', 'test7@gmail.com', 'mshai', '2018-08-28 00:06:51', '2018-08-28 00:06:51'),
(65, 'Miner', '1', 'test8@gmail.com', 'mshai', '2018-08-28 00:20:59', '2018-08-28 00:20:59');

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `id` int(11) NOT NULL,
  `EntryDate` varchar(250) NOT NULL,
  `Amount` varchar(250) NOT NULL,
  `Username` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`id`, `EntryDate`, `Amount`, `Username`, `created_at`, `updated_at`) VALUES
(53, '2018-07-22', '100', 'mshai', '2018-08-27 19:50:09', '2018-08-27 19:50:09'),
(54, '2018-08-15', '100', 'meettomangesh@gmail.com', '2018-08-27 19:50:09', '2018-08-27 19:50:09'),
(55, '2018-08-15', '100', 'test1@gmail.com', '2018-08-27 19:50:09', '2018-08-27 19:50:09'),
(56, '0', '0', 'test2@gmail.com', '2018-08-27 19:50:09', '2018-08-27 19:50:09'),
(57, '0', '0', 'test6@gmail.com', '2018-08-27 23:52:22', '2018-08-27 23:52:22'),
(58, '0', '0', 'test7@gmail.com', '2018-08-28 00:06:51', '2018-08-28 00:06:51'),
(59, '0', '0', 'test8@gmail.com', '2018-08-28 00:20:59', '2018-08-28 00:20:59');

-- --------------------------------------------------------

--
-- Table structure for table `starterpack`
--

CREATE TABLE `starterpack` (
  `id` int(11) NOT NULL,
  `PurchaseDate` varchar(250) NOT NULL,
  `MiningDate` varchar(250) NOT NULL,
  `Username` varchar(250) NOT NULL,
  `Status` varchar(250) NOT NULL,
  `CompletionDate` varchar(250) NOT NULL,
  `TotalMinable` varchar(250) NOT NULL,
  `Withdrawal` varchar(250) NOT NULL,
  `Comment` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `starterpack`
--

INSERT INTO `starterpack` (`id`, `PurchaseDate`, `MiningDate`, `Username`, `Status`, `CompletionDate`, `TotalMinable`, `Withdrawal`, `Comment`, `created_at`, `updated_at`) VALUES
(56, '2018-07-22', '2018-08-21', 'mshai', 'Active', '2019-07-22', '547.50', '0', 'Purchased', '2018-08-27 19:47:31', '2018-08-27 19:47:31'),
(58, '0', '0', 'test1@gmail.com', 'Inactive', '0', '547.50', '0', 'Not-Purchased', '2018-08-27 19:47:31', '2018-08-27 19:47:31'),
(59, '0', '0', 'test2@gmail.com', 'Inactive', '0', '547.50', '0', 'Not-Purchased', '2018-08-27 19:47:31', '2018-08-27 19:47:31'),
(60, '0', '0', 'meettomangesh@gmail.com', 'Inactive', '0', '547.50', '0', 'Not-Purchased', '2018-08-27 19:47:31', '2018-08-27 19:47:31'),
(61, '0', '0', 'test6@gmail.com', 'Inactive', '0', '547.50', '0', 'Not-Purchased', '2018-08-27 23:52:22', '2018-08-27 23:52:22'),
(62, '0', '0', 'test7@gmail.com', 'Inactive', '0', '547.50', '0', 'Not-Purchased', '2018-08-28 00:06:51', '2018-08-28 00:06:51'),
(63, '0', '0', 'test8@gmail.com', 'Inactive', '0', '547.50', '0', 'Not-Purchased', '2018-08-28 00:20:59', '2018-08-28 00:20:59');

-- --------------------------------------------------------

--
-- Table structure for table `support`
--

CREATE TABLE `support` (
  `id` int(100) NOT NULL,
  `Ticketid` varchar(250) NOT NULL,
  `Date` varchar(250) NOT NULL,
  `Username` varchar(250) NOT NULL,
  `Issue` varchar(250) NOT NULL,
  `Status` varchar(250) NOT NULL,
  `Category` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `id` int(100) NOT NULL,
  `Balance` varchar(250) NOT NULL,
  `Username` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id`, `Balance`, `Username`, `created_at`, `updated_at`) VALUES
(94, '0', 'mshai', '2018-08-27 19:40:42', '2018-08-27 19:40:42'),
(95, '0', 'meettomangesh@gmail.com', '2018-08-27 19:40:42', '2018-08-27 19:40:42'),
(96, '0', 'test1@gmail.com', '2018-08-27 19:40:42', '2018-08-27 19:40:42'),
(97, '0', 'test2@gmail.com', '2018-08-27 19:40:42', '2018-08-27 19:40:42'),
(98, '0', 'test6@gmail.com', '2018-08-27 23:52:22', '2018-08-27 23:52:22'),
(99, '0', 'test7@gmail.com', '2018-08-28 00:06:51', '2018-08-28 00:06:51'),
(100, '0', 'test8@gmail.com', '2018-08-28 00:20:59', '2018-08-28 00:20:59');

-- --------------------------------------------------------

--
-- Table structure for table `teamvolume`
--

CREATE TABLE `teamvolume` (
  `id` int(100) NOT NULL,
  `Balance` varchar(250) NOT NULL,
  `Username` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teamvolume`
--

INSERT INTO `teamvolume` (`id`, `Balance`, `Username`, `created_at`, `updated_at`) VALUES
(88, '300', 'mshai', '2018-08-27 19:42:01', '2018-08-27 19:42:01'),
(89, '2400', 'meettomangesh@gmail.com', '2018-08-27 19:42:01', '2018-08-27 19:42:01'),
(90, '0', 'test1@gmail.com', '2018-08-27 19:42:01', '2018-08-27 19:42:01'),
(91, '0', 'test2@gmail.com', '2018-08-27 19:42:01', '2018-08-27 19:42:01'),
(92, '0', 'test6@gmail.com', '2018-08-27 23:52:22', '2018-08-27 23:52:22'),
(93, '0', 'test7@gmail.com', '2018-08-28 00:06:51', '2018-08-28 00:06:51'),
(94, '0', 'test8@gmail.com', '2018-08-28 00:20:59', '2018-08-28 00:20:59');

-- --------------------------------------------------------

--
-- Table structure for table `tree`
--

CREATE TABLE `tree` (
  `id` int(11) NOT NULL,
  `userid` varchar(250) DEFAULT NULL,
  `left` varchar(250) DEFAULT NULL,
  `right` varchar(250) DEFAULT NULL,
  `leftcount` int(50) DEFAULT '0',
  `rightcount` int(50) DEFAULT '0',
  `leftcredits` int(50) DEFAULT '0',
  `rightcredits` int(50) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tree`
--

INSERT INTO `tree` (`id`, `userid`, `left`, `right`, `leftcount`, `rightcount`, `leftcredits`, `rightcredits`) VALUES
(74, 'mshai', 'meettomangesh@gmail.com', '', 3, 0, 0, 0),
(75, 'meettomangesh@gmail.com', 'test1@gmail.com', 'test2@gmail.com', 0, 3, 0, 0),
(76, 'test1@gmail.com', NULL, NULL, 0, 0, 0, 0),
(81, 'test2@gmail.com', NULL, NULL, 0, 0, 0, 0),
(80, 'test2@gmail.com', NULL, NULL, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ultimatepack`
--

CREATE TABLE `ultimatepack` (
  `id` int(11) NOT NULL,
  `PurchaseDate` varchar(250) NOT NULL,
  `MiningDate` varchar(250) NOT NULL,
  `Username` varchar(250) NOT NULL,
  `Status` varchar(250) NOT NULL,
  `CompletionDate` varchar(250) NOT NULL,
  `TotalMinable` varchar(250) NOT NULL,
  `Withdrawal` varchar(250) NOT NULL,
  `Comment` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ultimatepack`
--

INSERT INTO `ultimatepack` (`id`, `PurchaseDate`, `MiningDate`, `Username`, `Status`, `CompletionDate`, `TotalMinable`, `Withdrawal`, `Comment`, `created_at`, `updated_at`) VALUES
(55, '0', '0', 'mshai', 'Inactive', '0', '8760', '0', 'Not-Purchased', '2018-08-27 19:49:24', '2018-08-27 19:49:24'),
(56, '0', '0', 'meettomangesh@gmail.com', 'Inactive', '0', '8760', '0', 'Not-Purchased', '2018-08-27 19:49:24', '2018-08-27 19:49:24'),
(57, '0', '0', 'test1@gmail.com', 'Inactive', '0', '8760', '0', 'Not-Purchased', '2018-08-27 19:49:24', '2018-08-27 19:49:24'),
(58, '0', '0', 'test2@gmail.com', 'Inactive', '0', '8760', '0', 'Not-Purchased', '2018-08-27 19:49:24', '2018-08-27 19:49:24'),
(59, '0', '0', 'test6@gmail.com', 'Inactive', '0', '8760', '0', 'Not-Purchased', '2018-08-27 23:52:22', '2018-08-27 23:52:22'),
(60, '0', '0', 'test7@gmail.com', 'Inactive', '0', '8760', '0', 'Not-Purchased', '2018-08-28 00:06:51', '2018-08-28 00:06:51'),
(61, '0', '0', 'test8@gmail.com', 'Inactive', '0', '8760', '0', 'Not-Purchased', '2018-08-28 00:20:59', '2018-08-28 00:20:59');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `under_userid` varchar(50) NOT NULL,
  `side` enum('left','right') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `Username`, `under_userid`, `side`) VALUES
(54, 'meettomangesh@gmail.com', 'mshai', 'left'),
(55, 'test1@gmail.com', 'meettomangesh@gmail.com', 'left'),
(60, 'test2@gmail.com', 'meettomangesh@gmail.com', 'right');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `Fullname` varchar(250) NOT NULL,
  `Country` varchar(250) NOT NULL,
  `Email` varchar(250) NOT NULL,
  `Telephone` varchar(250) NOT NULL,
  `Gender` varchar(50) NOT NULL,
  `Username` varchar(250) NOT NULL,
  `Password` varchar(250) NOT NULL,
  `Status` varchar(250) NOT NULL,
  `Sponsor` varchar(250) NOT NULL,
  `Token` varchar(250) NOT NULL,
  `Account` varchar(250) NOT NULL,
  `Activation` varchar(250) NOT NULL,
  `treestatus` varchar(250) NOT NULL,
  `platform` enum('1','2','3') NOT NULL DEFAULT '3' COMMENT '1:Android 2:IOS,3:Web',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `Fullname`, `Country`, `Email`, `Telephone`, `Gender`, `Username`, `Password`, `Status`, `Sponsor`, `Token`, `Account`, `Activation`, `treestatus`, `platform`, `created_at`, `updated_at`) VALUES
(89, 'Mshai', 'Kenya', 'family88@bitcoinminehub.com', '33446611', '2', 'mshai', '123', 'Close', '', '14685', '24rgxpwex1b4ko88owko ', '1', 'notree', '3', '2018-08-27 19:37:23', '2018-08-27 19:37:23'),
(90, 'Mangesh Navale', 'India', 'meettomangesh@gmail.com', '09730872969', '1', 'meettomangesh@gmail.com', '7u8i9o0p', 'Open', 'mshai ', '730756', '42pswl2ze2gwg8c8gcg8 ', '1', 'tree', '3', '2018-08-27 19:37:23', '2018-08-27 19:37:23'),
(91, 'Mangesh Navale', 'India', 'test1@gmail.com', '65765765', '1', 'test1@gmail.com', '7u8i9o0p', 'Close', 'meettomangesh@gmail.com ', '646148', 'darlrulbrvsokwoookk0 ', '1', 'tree', '3', '2018-08-27 19:37:23', '2018-08-27 19:37:23'),
(92, 'Mangesh Navale', 'India', 'test2@gmail.com', '645656', '1', 'test2@gmail.com', '7u8i9o0p', 'Close', 'meettomangesh@gmail.com ', '854139', '2n4xpqp6fygww80gggkk ', '1', 'tree', '3', '2018-08-27 19:37:23', '2018-08-27 19:37:23'),
(93, 'Mangesh Navale', 'India', 'test6@gmail.com', '9730872969', 'Male', 'test6@gmail.com', '7u8i9o0p', 'Open', 'mshai', '455ter', 'ABIABIB&yt78tgg8g', '0', 'notree', '3', '2018-08-27 23:52:22', '2018-08-27 23:52:22'),
(94, 'Mangesh Navale', 'India', 'test4@gmail.com', '9730872969', 'Male', 'test7@gmail.com', '7u8i9o0p', 'Open', 'mshai', '223749', 'cxtm97b1a74k048sg4o4', '0', 'notree', '3', '2018-08-28 00:06:51', '2018-08-28 00:06:51'),
(95, 'Mangesh Navale', 'India', 'test4@gmail.com', '9730872969', 'Male', 'test8@gmail.com', '7u8i9o0p', 'Open', 'mshai', '355048', '26fxnu32t7ok0o4s0kok', '0', 'notree', '3', '2018-08-28 00:20:59', '2018-08-28 00:20:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accountbalance`
--
ALTER TABLE `accountbalance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `binaryincome`
--
ALTER TABLE `binaryincome`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bmp_wallet`
--
ALTER TABLE `bmp_wallet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bmp_wallet_sent_receive_transactions`
--
ALTER TABLE `bmp_wallet_sent_receive_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `commission`
--
ALTER TABLE `commission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dailymine`
--
ALTER TABLE `dailymine`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grandpack`
--
ALTER TABLE `grandpack`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hangbtc`
--
ALTER TABLE `hangbtc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hubcoin`
--
ALTER TABLE `hubcoin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mediumpack`
--
ALTER TABLE `mediumpack`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mining`
--
ALTER TABLE `mining`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `minipack`
--
ALTER TABLE `minipack`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`access_token`);

--
-- Indexes for table `oauth_authorization_codes`
--
ALTER TABLE `oauth_authorization_codes`
  ADD PRIMARY KEY (`authorization_code`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`refresh_token`);

--
-- Indexes for table `oauth_scopes`
--
ALTER TABLE `oauth_scopes`
  ADD PRIMARY KEY (`scope`);

--
-- Indexes for table `oauth_users`
--
ALTER TABLE `oauth_users`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_callback_log`
--
ALTER TABLE `payment_callback_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rank`
--
ALTER TABLE `rank`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `starterpack`
--
ALTER TABLE `starterpack`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support`
--
ALTER TABLE `support`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teamvolume`
--
ALTER TABLE `teamvolume`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tree`
--
ALTER TABLE `tree`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ultimatepack`
--
ALTER TABLE `ultimatepack`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accountbalance`
--
ALTER TABLE `accountbalance`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;
--
-- AUTO_INCREMENT for table `binaryincome`
--
ALTER TABLE `binaryincome`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `bmp_wallet`
--
ALTER TABLE `bmp_wallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `bmp_wallet_sent_receive_transactions`
--
ALTER TABLE `bmp_wallet_sent_receive_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `commission`
--
ALTER TABLE `commission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
--
-- AUTO_INCREMENT for table `dailymine`
--
ALTER TABLE `dailymine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=474;
--
-- AUTO_INCREMENT for table `grandpack`
--
ALTER TABLE `grandpack`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;
--
-- AUTO_INCREMENT for table `hangbtc`
--
ALTER TABLE `hangbtc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hubcoin`
--
ALTER TABLE `hubcoin`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;
--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;
--
-- AUTO_INCREMENT for table `mediumpack`
--
ALTER TABLE `mediumpack`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;
--
-- AUTO_INCREMENT for table `mining`
--
ALTER TABLE `mining`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
--
-- AUTO_INCREMENT for table `minipack`
--
ALTER TABLE `minipack`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;
--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT for table `payment_callback_log`
--
ALTER TABLE `payment_callback_log`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `rank`
--
ALTER TABLE `rank`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;
--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT for table `starterpack`
--
ALTER TABLE `starterpack`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
--
-- AUTO_INCREMENT for table `support`
--
ALTER TABLE `support`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;
--
-- AUTO_INCREMENT for table `teamvolume`
--
ALTER TABLE `teamvolume`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;
--
-- AUTO_INCREMENT for table `tree`
--
ALTER TABLE `tree`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;
--
-- AUTO_INCREMENT for table `ultimatepack`
--
ALTER TABLE `ultimatepack`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
