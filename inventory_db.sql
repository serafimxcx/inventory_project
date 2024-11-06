-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2024 at 12:28 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bin_inventory`
--

CREATE TABLE `bin_inventory` (
  `id` int(11) NOT NULL,
  `bin_id` int(11) DEFAULT NULL,
  `product_variant_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bin_inventory`
--

INSERT INTO `bin_inventory` (`id`, `bin_id`, `product_variant_id`, `quantity`, `created_at`) VALUES
(1, 3, 5, 50, '2024-06-03 09:12:06');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Electrical'),
(2, 'Fragile');

-- --------------------------------------------------------

--
-- Table structure for table `customer_returns`
--

CREATE TABLE `customer_returns` (
  `id` int(11) NOT NULL,
  `return_no` varchar(100) DEFAULT NULL,
  `return_date` datetime DEFAULT current_timestamp(),
  `sales_order_id` int(11) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT 0.00,
  `reorder_point` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `code`, `name`, `description`, `category_id`, `tags`, `discount`, `reorder_point`, `created_at`) VALUES
(2, 'LB1', 'Light Bulb', 'solar and waterproof', 1, 'decoration', '0.00', 25, '2024-06-02 00:51:36'),
(4, 'WG1', 'Wine Glass', 'thin glass very fragile', 2, 'kitchenware', '0.00', 15, '2024-06-02 01:29:53'),
(5, 'WM1', 'Wall Mirror ', 'made in russia', 2, 'decoration', '0.00', 5, '2024-06-02 01:31:25'),
(16, 'EW1', 'Electrical Wire', 'high quality and durable', 1, 'wiring', '0.00', 20, '2024-06-02 02:21:28');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `variant_name` varchar(100) DEFAULT NULL,
  `variant_desc` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `variant_name`, `variant_desc`, `price`, `created_at`) VALUES
(1, 16, 'EW5M', '5 meters, color black', '15.00', '2024-06-02 03:25:28'),
(2, 16, 'EW10M', '10 meters, color red', '20.00', '2024-06-02 03:31:05'),
(5, 2, 'LED3W', '3 watts', '30.75', '2024-06-02 07:18:52'),
(7, 2, 'LED5W', '5 watts', '45.99', '2024-06-02 07:19:26'),
(8, 2, 'LED7W', '7 watts', '53.75', '2024-06-02 07:19:36'),
(9, 2, 'LED9W', '9 watts', '69.75', '2024-06-02 07:19:49');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int(11) NOT NULL,
  `purchase_code` varchar(50) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `product_variant_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `status` enum('pending','completed','cancelled') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `purchase_code`, `supplier_id`, `order_date`, `product_variant_id`, `quantity`, `status`) VALUES
(347, 'QER23', 6, '2024-06-02 00:00:00', 5, 90, 'completed'),
(354, 'ELECTRIC01', 5, '2024-06-03 12:10:00', 1, 90, 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `return_purchase`
--

CREATE TABLE `return_purchase` (
  `id` int(11) NOT NULL,
  `return_no` varchar(50) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `return_date` datetime DEFAULT current_timestamp(),
  `product_variant_id` int(11) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `return_purchase`
--

INSERT INTO `return_purchase` (`id`, `return_no`, `supplier_id`, `return_date`, `product_variant_id`, `reason`, `quantity`) VALUES
(1, 'RET123', 6, '2024-06-03 06:19:00', 5, 'Broken', 10);

-- --------------------------------------------------------

--
-- Table structure for table `sales_orders`
--

CREATE TABLE `sales_orders` (
  `id` int(11) NOT NULL,
  `order_no` varchar(50) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `product_variant_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `status` enum('pending','shipped','delivered','cancelled') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_orders`
--

INSERT INTO `sales_orders` (`id`, `order_no`, `customer_name`, `order_date`, `product_variant_id`, `quantity`, `status`) VALUES
(1, 'ORD1234', 'Gabie Coronado', '2024-06-03 09:00:00', 5, 10, 'delivered'),
(3, 'ORD0002', 'Mark Boticario', '2024-06-03 15:01:00', 1, 2, 'delivered');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `contact_no` text DEFAULT NULL,
  `product_offerings` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact_no`, `product_offerings`) VALUES
(4, 'Babylyn Aragon', '09321234321', 'Food Ingredients'),
(5, 'Allen Pascual', '09567534567', 'Electric materials'),
(6, 'John Bryan Pesa', '09576463456', 'Glassworks');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','manager','staff') NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `name`, `email`) VALUES
(2, 'admin_gabie', '123456789', 'admin', 'John Gabriel Coronado', 'jg22coronado@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `location` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `name`, `location`) VALUES
(1, 'Lomeda Warehouse', 'Lipa City, Batangas'),
(3, 'Pesa Warehouse', 'Alabang, Manila'),
(4, 'Eula Warehouse', 'Tanauan, Batangas');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_bins`
--

CREATE TABLE `warehouse_bins` (
  `id` int(11) NOT NULL,
  `zone_id` int(11) DEFAULT NULL,
  `bin_code` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warehouse_bins`
--

INSERT INTO `warehouse_bins` (`id`, `zone_id`, `bin_code`) VALUES
(3, 1, 'BINLOM-0101'),
(5, 1, 'BINLOM-0102');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_transfers`
--

CREATE TABLE `warehouse_transfers` (
  `id` int(11) NOT NULL,
  `transfer_no` varchar(50) NOT NULL,
  `product_variant_id` int(11) DEFAULT NULL,
  `from_warehouse` int(11) DEFAULT NULL,
  `to_warehouse` int(11) DEFAULT NULL,
  `transfer_date` date DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warehouse_transfers`
--

INSERT INTO `warehouse_transfers` (`id`, `transfer_no`, `product_variant_id`, `from_warehouse`, `to_warehouse`, `transfer_date`, `quantity`) VALUES
(2, 'TRANSFER01', 5, 1, 3, '2024-06-03', 12);

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_zones`
--

CREATE TABLE `warehouse_zones` (
  `id` int(11) NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `zone_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warehouse_zones`
--

INSERT INTO `warehouse_zones` (`id`, `warehouse_id`, `zone_name`) VALUES
(1, 1, 'LOM-01'),
(4, 1, 'LOM-02');

-- --------------------------------------------------------

--
-- Table structure for table `wastages`
--

CREATE TABLE `wastages` (
  `id` int(11) NOT NULL,
  `wastage_no` varchar(50) DEFAULT NULL,
  `wastage_date` datetime DEFAULT current_timestamp(),
  `product_variant_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wastages`
--

INSERT INTO `wastages` (`id`, `wastage_no`, `wastage_date`, `product_variant_id`, `quantity`, `reason`) VALUES
(2, 'WASTE01', '2024-06-03 22:48:00', 5, 10, 'Broken');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bin_inventory`
--
ALTER TABLE `bin_inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bin_inventory_ibfk_1` (`bin_id`),
  ADD KEY `bin_inventory_ibfk_2` (`product_variant_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_returns`
--
ALTER TABLE `customer_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_returns_ibfk_1` (`sales_order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_ibfk_1` (`category_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_orders_ibfk_1` (`supplier_id`),
  ADD KEY `purchase_orders_ibfk_2` (`product_variant_id`);

--
-- Indexes for table `return_purchase`
--
ALTER TABLE `return_purchase`
  ADD PRIMARY KEY (`id`),
  ADD KEY `return_purchase_ibfk_1` (`supplier_id`),
  ADD KEY `return_purchase_ibfk_2` (`product_variant_id`);

--
-- Indexes for table `sales_orders`
--
ALTER TABLE `sales_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_orders_ibfk_1` (`product_variant_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warehouse_bins`
--
ALTER TABLE `warehouse_bins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warehouse_bins_ibfk_1` (`zone_id`);

--
-- Indexes for table `warehouse_transfers`
--
ALTER TABLE `warehouse_transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warehouse_transfers_ibfk_1` (`product_variant_id`),
  ADD KEY `warehouse_transfers_ibfk_2` (`from_warehouse`),
  ADD KEY `warehouse_transfers_ibfk_3` (`to_warehouse`);

--
-- Indexes for table `warehouse_zones`
--
ALTER TABLE `warehouse_zones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warehouse_zones_ibfk_1` (`warehouse_id`);

--
-- Indexes for table `wastages`
--
ALTER TABLE `wastages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wastages_ibfk_1` (`product_variant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bin_inventory`
--
ALTER TABLE `bin_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customer_returns`
--
ALTER TABLE `customer_returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=355;

--
-- AUTO_INCREMENT for table `return_purchase`
--
ALTER TABLE `return_purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sales_orders`
--
ALTER TABLE `sales_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `warehouse_bins`
--
ALTER TABLE `warehouse_bins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `warehouse_transfers`
--
ALTER TABLE `warehouse_transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `warehouse_zones`
--
ALTER TABLE `warehouse_zones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wastages`
--
ALTER TABLE `wastages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bin_inventory`
--
ALTER TABLE `bin_inventory`
  ADD CONSTRAINT `bin_inventory_ibfk_1` FOREIGN KEY (`bin_id`) REFERENCES `warehouse_bins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bin_inventory_ibfk_2` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customer_returns`
--
ALTER TABLE `customer_returns`
  ADD CONSTRAINT `customer_returns_ibfk_1` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_orders_ibfk_2` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `return_purchase`
--
ALTER TABLE `return_purchase`
  ADD CONSTRAINT `return_purchase_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `return_purchase_ibfk_2` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sales_orders`
--
ALTER TABLE `sales_orders`
  ADD CONSTRAINT `sales_orders_ibfk_1` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `warehouse_bins`
--
ALTER TABLE `warehouse_bins`
  ADD CONSTRAINT `warehouse_bins_ibfk_1` FOREIGN KEY (`zone_id`) REFERENCES `warehouse_zones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `warehouse_transfers`
--
ALTER TABLE `warehouse_transfers`
  ADD CONSTRAINT `warehouse_transfers_ibfk_1` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `warehouse_transfers_ibfk_2` FOREIGN KEY (`from_warehouse`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `warehouse_transfers_ibfk_3` FOREIGN KEY (`to_warehouse`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `warehouse_zones`
--
ALTER TABLE `warehouse_zones`
  ADD CONSTRAINT `warehouse_zones_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wastages`
--
ALTER TABLE `wastages`
  ADD CONSTRAINT `wastages_ibfk_1` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
