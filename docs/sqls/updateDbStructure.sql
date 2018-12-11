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


CREATE TABLE `bmp_pool_benifits` (
  `id` int(11) NOT NULL,
  `pool_name` varchar(250) DEFAULT NULL,
  `pool_table_name` varchar(250) DEFAULT NULL,
  `daily_bonus` decimal(14,4) NOT NULL DEFAULT '0.0000',
  `monthly_bonus` decimal(14,4) NOT NULL DEFAULT '0.0000',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bmp_pool_benifits`
--

INSERT INTO `bmp_pool_benifits` (`id`, `pool_name`, `pool_table_name`, `daily_bonus`, `monthly_bonus`, `created_at`, `updated_at`) VALUES
(1, 'Starter', 'starterpack', '1.5000', '30.0000', '2018-09-10 14:23:08', '2018-09-10 14:23:08'),
(2, 'Mini', 'minipack', '3.0000', '90.0000', '2018-09-10 14:23:08', '2018-09-10 14:23:08'),
(3, 'Medium', 'mediumpack', '6.0000', '180.0000', '2018-09-10 14:25:05', '2018-09-10 14:25:05'),
(4, 'Grand', 'grandpack', '12.0000', '360.0000', '2018-09-10 14:25:05', '2018-09-10 14:25:05'),
(5, 'Ultimate', 'ultimatepack', '24.0000', '420.0000', '2018-09-10 14:25:05', '2018-09-10 14:25:05');


--
-- Indexes for dumped tables
--

--
-- Indexes for table `bmp_pool_benifits`
--
ALTER TABLE `bmp_pool_benifits`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bmp_pool_benifits`
--
ALTER TABLE `bmp_pool_benifits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;



/*After 1st release on 12-09-2018*/

ALTER TABLE `dailymine`  ADD `Username` VARCHAR(250) NULL DEFAULT NULL  AFTER `Status`,  ADD `is_monthly_mining` ENUM('1','0') NOT NULL DEFAULT '0' COMMENT '1:Yes 0:No'  AFTER `Username`,  ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `is_monthly_mining`,  ADD `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `created_at`;

ALTER TABLE `dailymine` CHANGE `Date` `Date` DATE NULL DEFAULT NULL;

ALTER TABLE `dailymine` CHANGE `Btc` `Btc` DECIMAL(14,4) NOT NULL DEFAULT '0', CHANGE `Usd` `Usd` DECIMAL(14,4) NOT NULL DEFAULT '0';

ALTER TABLE `mining` CHANGE `Balance` `Balance` DECIMAL(14,4) NOT NULL DEFAULT '0.0';


CREATE TABLE `bmp_wallet_withdrawl_transactions` (
  `id` int(11) NOT NULL,
  `user_name` varchar(250) DEFAULT NULL,
  `to_address` varchar(250) DEFAULT NULL,
  `amount` decimal(14,4) NOT NULL DEFAULT '0.0000',
  `status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1:Pending 2:Processed 3:Rejected',
  `response` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bmp_wallet_withdrawl_transactions`
--
ALTER TABLE `bmp_wallet_withdrawl_transactions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bmp_wallet_withdrawl_transactions`
--
ALTER TABLE `bmp_wallet_withdrawl_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `support` CHANGE `Ticketid` `ticket_id` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, CHANGE `Date` `date` DATE NULL DEFAULT NULL, CHANGE `Username` `user_name` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, CHANGE `Issue` `issue` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `Status` `status` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, CHANGE `Category` `category` ENUM('1','2','3','4') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '4' COMMENT '1:Registration 2:Account Activation 3:Payment 4: Others';


ALTER TABLE `support`  ADD `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `category`,  ADD `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `created_at`;

ALTER TABLE `support` CHANGE `status` `status` ENUM('1','2','3') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '1' COMMENT '1:Pending 2:Processed 3:Rejected';


/*26-09-2018 */


CREATE TABLE `bmp_bonus_commission_earn_log` (
  `id` int(11) NOT NULL,
  `user_name` varchar(250) DEFAULT NULL,
  `reason_id` enum('0','1','2','3','4','5') NOT NULL DEFAULT '0' COMMENT '0: N/A,1:Direct Commission , 2:Indirect Commission, 3:Matching Bonus, 4:Residual Bonus, 5:Mining Earning ',
  `reason_description` text,
  `is_added_by_cron` enum('1','0') NOT NULL DEFAULT '0' COMMENT '1:Yes 0:No',
  `amount` decimal(14,4) NOT NULL,
  `added_in` varchar(250) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `bmp_bonus_commission_earn_log`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `bmp_bonus_commission_earn_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
-- 01/10/2018

ALTER TABLE `accountbalance` CHANGE `Register` `Register` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `accountbalance` CHANGE `Balance` `Balance` DECIMAL(14,4) NOT NULL DEFAULT '0';

--15/11/2018

CREATE TABLE `site_options` (
  `id` int(11) NOT NULL,
  `option_name` varchar(250) DEFAULT NULL,
  `option_value` varchar(250) DEFAULT NULL,
  `option_description` varchar(500) DEFAULT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1:Active 2:Inactive',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `site_options`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `site_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `site_options` (`id`, `option_name`, `option_value`, `option_description`, `status`, `created_at`, `updated_at`) VALUES (NULL, 'transaction_percentage', '0', 'This is transaction percentage which will be charged for each transaction', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);


-- 11-12-2018


CREATE TABLE `system_emails` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_to` text COLLATE utf8_unicode_ci,
  `email_cc` text COLLATE utf8_unicode_ci,
  `email_bcc` text COLLATE utf8_unicode_ci,
  `email_from` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text1` text COLLATE utf8_unicode_ci NOT NULL,
  `text2` text COLLATE utf8_unicode_ci NOT NULL,
  `email_type` tinyint(1) UNSIGNED NOT NULL COMMENT '1 : Email to Admin, 2 : Email to User, 3 : Email to Others',
  `tags` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 : Active, 0 : Inactive',
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Indexes for table `system_emails`
--
ALTER TABLE `system_emails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `system_emails_name_index` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `system_emails`
--
ALTER TABLE `system_emails`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

  INSERT INTO `system_emails` (`id`, `name`, `description`, `email_to`, `email_cc`, `email_bcc`, `email_from`, `subject`, `text1`, `text2`, `email_type`, `tags`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'INVOICE_PAID', 'Email trigger after invoice received.', NULL, NULL, NULL, 'register@bitminepool.com', 'register@bitminepool.com', '&lt;table style=\"font-family: Arial,Helvetica,sans-serif; font-size: 13px; color: #000000; line-height: 22px; width: 600px;\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\"&gt;\r\n  &lt;tbody&gt;\r\n  &lt;tr&gt;\r\n  &lt;td style=\"border-top: 3px solid #a1c13a; height: 3px;\" align=\"center\" valign=\"top\"&gt;&amp;nbsp;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n  &lt;td style=\"padding: 10px 0;\" align=\"center\" valign=\"middle\" width=\"90\"&gt;&lt;a href=\"http://www.bitminepool.com/\" target=\"_blank\"&gt; &lt;img class=\"CToWUd\" src=\"http://www.bitminepool.com/images/logo.png\" alt=\"bitminepool\" height=\"80\" /&gt; &lt;/a&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n  &lt;td style=\"border-bottom: 1px solid #ececec; height: 1px;\" align=\"center\" valign=\"top\"&gt;&amp;nbsp;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n  &lt;td style=\"background-color: #f6f6f6;\" align=\"center\" valign=\"top\"&gt;\r\n  &lt;table style=\"width: 94%;\" cellspacing=\"12\" cellpadding=\"0\"&gt;\r\n  &lt;tbody&gt;\r\n  &lt;tr&gt;\r\n  &lt;td style=\"font-size: 14px; color: #454545; line-height: 24px; padding-bottom: 10px;\" align=\"left\" valign=\"top\"&gt;{$productDetails}&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n  &lt;td style=\"border-top: 1px solid #e1e1e1; height: 1px;\" align=\"center\" valign=\"top\"&gt;&amp;nbsp;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;/tbody&gt;\r\n  &lt;/table&gt;\r\n  &lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n  &lt;td style=\"background-color: #8cb53f; padding: 20px 0; text-transform: uppercase; font-weight: bold;\" align=\"center\" valign=\"top\"&gt;&lt;strong style=\"color: #3c4d1b;\"&gt;If you have any queries, feedback and suggestions,&lt;br /&gt; please feel free to write to us at &lt;a style=\"color: #ffffff; text-decoration: none;\" href=\"mailto:talk@smat-loyal.com\" target=\"_blank\"&gt;support@bitminepool.com&lt;/a&gt;&lt;/strong&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n  &lt;td style=\"width: 94%; padding: 0 20px;\"&gt;\r\n  &lt;table style=\"width: 94%;\" cellspacing=\"12\" cellpadding=\"0\"&gt;\r\n  &lt;tbody style=\"font-size: 14px; color: #454545;\"&gt;\r\n  &lt;tr&gt;\r\n  &lt;td align=\"left\" valign=\"top\"&gt;Regards,&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n  &lt;td align=\"left\" valign=\"top\"&gt;&lt;span style=\"color: #8cb53f; text-transform: uppercase; font-weight: bold;\"&gt;Team Bitminepool.com&lt;/span&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;/tbody&gt;\r\n  &lt;/table&gt;\r\n  &lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n  &lt;td style=\"border-top: 1px solid #e1e1e1; height: 1px;\" align=\"center\" valign=\"top\"&gt;&amp;nbsp;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n  &lt;td align=\"center\" valign=\"middle\"&gt;\r\n  \r\n  &lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;/tbody&gt;\r\n  &lt;/table&gt;', '', 1, NULL, 1, 0, 0, '2018-12-10 18:30:00', '2018-12-10 18:30:00');
