CREATE DATABASE IF NOT EXISTS design_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE design_store;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL UNIQUE,
  phone VARCHAR(30) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('user','designer','admin') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  description TEXT,
  price_design DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  price_print DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  image_url VARCHAR(255),
  designer_id INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_services_designer FOREIGN KEY (designer_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  service_id INT NOT NULL,
  quantity INT NOT NULL DEFAULT 1,
  print_option ENUM('ya','tidak') NOT NULL DEFAULT 'tidak',
  shipping_address TEXT NOT NULL,
  payment_status ENUM('pending','paid','cancelled') NOT NULL DEFAULT 'pending',
  order_status ENUM('baru','diproses','selesai','dibatalkan') NOT NULL DEFAULT 'baru',
  total_amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_orders_service FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE RESTRICT
);
