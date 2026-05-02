CREATE DATABASE IF NOT EXISTS design_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE design_store;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL UNIQUE,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  phone VARCHAR(30) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  profile_picture VARCHAR(255) DEFAULT 'default_avatar.png',
  address TEXT,
  bio TEXT,
  role ENUM('user', 'designer', 'admin') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_name VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT,
  name VARCHAR(120) NOT NULL,
  description TEXT,
  price_design DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  price_print DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  image_url VARCHAR(255),
  designer_id INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_services_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
  CONSTRAINT fk_services_designer FOREIGN KEY (designer_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  service_id INT NOT NULL,
  design_brief TEXT,
  reference_file VARCHAR(255),
  quantity INT NOT NULL DEFAULT 1,
  print_option ENUM('ya', 'tidak') NOT NULL DEFAULT 'tidak',
  shipping_address TEXT NOT NULL,
  total_amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  proof_of_payment VARCHAR(255),
  payment_status ENUM('pending', 'paid', 'cancelled') NOT NULL DEFAULT 'pending',
  order_status ENUM('baru', 'diproses', 'revisi', 'selesai', 'dibatalkan') NOT NULL DEFAULT 'baru',
  result_file VARCHAR(255),
  revision_count INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_orders_service FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS order_revisions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  revision_note TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_revisions_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS shipments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  courier_name VARCHAR(50) DEFAULT 'Grab',
  tracking_number VARCHAR(100),
  delivery_status ENUM('pending', 'pickup', 'on_the_way', 'delivered') DEFAULT 'pending',
  shipped_at DATETIME NULL,
  CONSTRAINT fk_shipments_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  user_id INT NOT NULL,
  rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
  comment TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reviews_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_reviews_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
