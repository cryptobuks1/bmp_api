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

--
-- Indexes for dumped tables
--

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
COMMIT;

ALTER TABLE `users` ADD `is_wallet_user` ENUM('1','2') NOT NULL DEFAULT '2' COMMENT '1:Yes 2: No' AFTER `platform`;

ALTER TABLE `users` ADD `is_admin_user` ENUM('1','2') NOT NULL DEFAULT '2' COMMENT '1:Yes 2: No' AFTER `is_wallet_user`;