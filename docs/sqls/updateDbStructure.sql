ALTER TABLE `users`  ADD `platform` ENUM('1','2','3') NOT NULL DEFAULT '3' COMMENT '1:Android 2:IOS,3:Web'  AFTER `treestatus`;

ALTER TABLE `users`  ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `platform`,  ADD `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `created_at`;
ALTER TABLE `accountbalance`  ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `Register`,  ADD `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `created_at`;
ALTER TABLE `binaryincome`  ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `total_bal`,  ADD `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `created_at`;
ALTER TABLE `hubcoin`  ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `Username`,  ADD `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `created_at`;
ALTER TABLE `team`  ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `Username`,  ADD `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `created_at`;
ALTER TABLE `teamvolume`  ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `Username`,  ADD `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `created_at`;
ALTER TABLE `rank`  ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `Sponsor`,  ADD `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `created_at`;
ALTER TABLE `mining`  ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `Username`,  ADD `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `created_at`;
ALTER TABLE `commission`  ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `Username`,  ADD `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `created_at`;
ALTER TABLE `starterpack`  ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `Comment`,  ADD `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `created_at`;
ALTER TABLE `minipack`  ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `Comment`,  ADD `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `created_at`;
ALTER TABLE `mediumpack`  ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `Comment`,  ADD `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `created_at`;
ALTER TABLE `grandpack`  ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `Comment`,  ADD `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `created_at`;
ALTER TABLE `ultimatepack`  ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `Comment`,  ADD `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `created_at`;
ALTER TABLE `register`  ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `Username`,  ADD `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `created_at`;

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

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bmp_wallet`
--
ALTER TABLE `bmp_wallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `bmp_wallet_sent_receive_transactions`
--
ALTER TABLE `bmp_wallet_sent_receive_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;COMMIT;

