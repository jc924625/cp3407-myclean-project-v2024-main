CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100) UNIQUE,
  password VARCHAR(255),
  role ENUM('customer','cleaner')
);

CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  cleaner_id INT,
  date DATE,
  time TIME,
  status VARCHAR(50),
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (cleaner_id) REFERENCES users(id)
);

CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  booking_id INT,
  rating INT,
  comment TEXT,
  FOREIGN KEY (booking_id) REFERENCES bookings(id)
);
