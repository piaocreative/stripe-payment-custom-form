--
-- Table structure for table `tbl_payment`
--

CREATE TABLE `tbl_payment` (
  `id` int(11) NOT NULL,
  `order_hash` varchar(255) NOT NULL,
  `payer_email` varchar(100) NOT NULL,
  `amount` double(10,2) NOT NULL,
  `currency` varchar(25) NOT NULL,
  `payment_type` varchar(25) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `order_status` varchar(25) NOT NULL,
  `notes` text NOT NULL,
  `name` varchar(25) NOT NULL,
  `address` varchar(255) NOT NULL,
  `country` varchar(25) NOT NULL,
  `postal_code` varchar(25) NOT NULL,
  `stripe_payment_intent_id` varchar(255) NOT NULL,
  `payment_status` varchar(25) NOT NULL,
  `stripe_payment_status` varchar(25) NOT NULL,
  `stripe_payment_response` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
