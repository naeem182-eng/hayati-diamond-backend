CREATE TABLE `customers` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `phone` varchar(255),
  `note` text,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `products` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `category` varchar(255),
  `is_active` boolean,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `stock_items` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `product_id` bigint NOT NULL,
  `serial_no` varchar(255) UNIQUE,
  `ring_size` varchar(255),
  `gold_weight_actual` decimal,
  `gold_price_at_make` decimal,
  `diamond_detail` text,
  `total_cost` decimal,
  `status` varchar(255),
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `invoices` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `customer_id` bigint NOT NULL,
  `total_amount` decimal,
  `payment_type` varchar(255),
  `status` varchar(255),
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `invoice_items` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `invoice_id` bigint NOT NULL,
  `product_id` bigint NOT NULL,
  `stock_item_id` bigint UNIQUE,
  `price_at_sale` decimal,
  `quantity` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `installment_plans` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `invoice_id` bigint UNIQUE NOT NULL,
  `total_amount` decimal,
  `months` int,
  `status` varchar(255),
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `installment_schedules` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `installment_plan_id` bigint NOT NULL,
  `month_no` int,
  `due_date` date,
  `amount` decimal,
  `status` varchar(255),
  `paid_at` datetime,
  `created_at` datetime,
  `updated_at` datetime
);

ALTER TABLE `invoices` ADD FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

ALTER TABLE `stock_items` ADD FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

ALTER TABLE `invoice_items` ADD FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`);

ALTER TABLE `invoice_items` ADD FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

ALTER TABLE `invoice_items` ADD FOREIGN KEY (`stock_item_id`) REFERENCES `stock_items` (`id`);

ALTER TABLE `installment_plans` ADD FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`);

ALTER TABLE `installment_schedules` ADD FOREIGN KEY (`installment_plan_id`) REFERENCES `installment_plans` (`id`);
