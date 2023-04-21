-- USE DATABASE
USE myDB;

-- CRREATE CUSTOMER TABLE
CREATE TABLE IF NOT EXISTS customer(
    customer_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20),
    risk_profile VARCHAR(20) NOT NULL,
    portfolio_value DECIMAL(15,2) NOT NULL,
    portfolio_assessed BOOLEAN NOT NULL,
    advice_description VARCHAR(255),
    advice_date DATE
);

-- CREATE INVESTMENT TABLE
CREATE TABLE IF NOT EXISTS investment (
  Investment_id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  asset_name VARCHAR(255) NOT NULL,
  asset_type VARCHAR(50) NOT NULL,
  date_accuired DATE,
  asset_initial_value DECIMAL(15, 2) NOT NULL,
  asset_current_value DECIMAL(15, 2) NOT NULL,
  FOREIGN KEY (customer_id) REFERENCES customer(customer_id)
);

-- Dummy data for customer table
INSERT INTO customer (first_name, last_name, email, phone_number, risk_profile, portfolio_value, portfolio_assessed, advice_description, advice_date) VALUES
('John', 'Doe', 'john.doe@example.com', '555-1234', 'Low Risk', 5000.00, true, 'Invest in Bonds', '2022-02-01'),
('Jane', 'Doe', 'jane.doe@example.com', '555-5678', 'High Risk', 10000.00, true, 'Invest in Stocks', '2022-02-02'),
('Bob', 'Smith', 'bob.smith@example.com', '555-2468', 'Medium Risk', 7500.00, true, 'Invest in Real Estate', '2022-02-03'),
('Alice', 'Johnson', 'alice.johnson@example.com', '555-1357', 'Low Risk', 3000.00, false, NULL, NULL),
('Charlie', 'Brown', 'charlie.brown@example.com', '555-7890', 'High Risk', 15000.00, true, 'Invest in Mutual Funds', '2022-02-04'),
('Emily', 'Davis', 'emily.davis@example.com', '555-3698', 'Low Risk', 6000.00, true, 'Invest in Savings Account', '2022-02-05'),
('Frank', 'White', 'frank.white@example.com', '555-9753', 'Medium Risk', 8000.00, false, NULL, NULL),
('Grace', 'Wilson', 'grace.wilson@example.com', '555-7412', 'High Risk', 20000.00, true, 'Invest in Stocks', '2022-02-06'),
('Henry', 'Lee', 'henry.lee@example.com', '555-8520', 'Low Risk', 4000.00, true, 'Invest in Bonds', '2022-02-07'),
('Isabella', 'Clark', 'isabella.clark@example.com', '555-4680', 'Medium Risk', 9000.00, false, NULL, NULL),
('James', 'Wong', 'james.wong@example.com', '555-2468', 'High Risk', 12000.00, true, 'Invest in Mutual Funds', '2022-02-08'),
('Karen', 'Garcia', 'karen.garcia@example.com', '555-1234', 'Low Risk', 5500.00, true, 'Invest in Real Estate', '2022-02-09'),
('Luke', 'Martinez', 'luke.martinez@example.com', '555-3698', 'Medium Risk', 7000.00, false, NULL, NULL),
('Mary', 'Gonzalez', 'mary.gonzalez@example.com', '555-9753', 'High Risk', 18000.00, true, 'Invest in Savings Account', '2022-02-10'),
('Nathan', 'Johnson', 'nathan.johnson@example.com', '555-8520', 'Low Risk', 4500.00, true, 'Invest in Stocks', '2022-02-11'),
('Olivia', 'Hernandez', 'olivia.hernandez@example.com', '555-1234', 'Medium Risk', 8500.00, false, NULL, NULL);

-- Dummy data for investment table
INSERT INTO investment (customer_id, asset_name, asset_type, date_accuired, asset_initial_value, asset_current_value) VALUES
(1, 'Apple Inc.', 'Stocks', '2020-01-01', 5000.00, 6000.00),
(1, 'Tesla Inc.', 'Stocks', '2020-02-01', 7000.00, 9000.00),
(1, 'Gold', 'Commodities', '2020-03-01', 10000.00, 12000.00),
(1, 'Real Estate', 'Property', '2020-04-01', 150000.00, 160000.00),
(2, 'Microsoft Corporation', 'Stocks', '2020-01-01', 8000.00, 9000.00),
(2, 'Oil', 'Commodities', '2020-02-01', 12000.00, 10000.00),
(2, 'Real Estate', 'Property', '2020-03-01', 200000.00, 220000.00),
(3, 'Amazon.com, Inc.', 'Stocks', '2020-01-01', 6000.00, 7000.00),
(3, 'Gold', 'Commodities', '2020-02-01', 8000.00, 10000.00),
(3, 'Real Estate', 'Property', '2020-03-01', 250000.00, 260000.00),
(4, 'Alphabet Inc.', 'Stocks', '2020-01-01', 10000.00, 11000.00),
(4, 'Tesla Inc.', 'Stocks', '2020-02-01', 12000.00, 14000.00),
(4, 'Oil', 'Commodities', '2020-03-01', 5000.00, 4000.00),
(4, 'Real Estate', 'Property', '2020-04-01', 350000.00, 360000.00),
(5, 'Facebook, Inc.', 'Stocks', '2020-01-01', 5000.00, 6000.00),
(5, 'Gold', 'Commodities', '2020-02-01', 10000.00, 12000.00),
(5, 'Real Estate', 'Property', '2020-03-01', 150000.00, 160000.00),
(6, 'Microsoft Corporation', 'Stocks', '2020-01-01', 8000.00, 9000.00),
(6, 'Tesla Inc.', 'Stocks', '2020-02-01', 7000.00, 9000.00),
(6, 'Gold', 'Commodities', '2020-03-01', 10000.00, 12000.00),
(6, 'Real Estate', 'Property', '2020-04-01', 200000.00, 220000.00),
(7, 'Amazon.com, Inc.', 'Stocks', '2020-01-01', 6000.00, 7000.00),
(7, 'Oil', 'Commodities', '2020-02-01', 8000.00, 10000.00),
(7, 'Real Estate', 'Property', '2020-03-01', 250000.00, 260),
(8, 'Intel Corporation', 'Stocks', '2020-05-01', 9000.00, 10000.00),
(8, 'Oil', 'Commodities', '2020-06-01', 8000.00, 7000.00),
(8, 'Real Estate', 'Property', '2020-07-01', 180000.00, 190000.00),
(9, 'Tesla Inc.', 'Stocks', '2020-05-01', 10000.00, 12000.00),
(9, 'Gold', 'Commodities', '2020-06-01', 15000.00, 17000.00),
(9, 'Real Estate', 'Property', '2020-07-01', 300000.00, 320000.00),
(10, 'Apple Inc.', 'Stocks', '2020-05-01', 5000.00, 6000.00),
(10, 'Oil', 'Commodities', '2020-06-01', 12000.00, 10000.00),
(10, 'Real Estate', 'Property', '2020-07-01', 250000.00, 270000.00),
(11, 'Microsoft Corporation', 'Stocks', '2020-05-01', 8000.00, 9000.00),
(11, 'Gold', 'Commodities', '2020-06-01', 10000.00, 12000.00),
(11, 'Real Estate', 'Property', '2020-07-01', 180000.00, 200000.00),
(12, 'Amazon.com, Inc.', 'Stocks', '2020-05-01', 6000.00, 7000.00),
(12, 'Tesla Inc.', 'Stocks', '2020-06-01', 12000.00, 14000.00),
(12, 'Oil', 'Commodities', '2020-07-01', 5000.00, 4000.00),
(12, 'Real Estate', 'Property', '2020-08-01', 400000.00, 420000.00),
(13, 'Facebook, Inc.', 'Stocks', '2020-05-01', 5000.00, 6000.00),
(13, 'Gold', 'Commodities', '2020-06-01', 15000.00, 17000.00),
(13, 'Real Estate', 'Property', '2020-07-01', 220000.00, 240000.00),
(14, 'Microsoft Corporation', 'Stocks', '2020-05-01', 8000.00, 9000.00),
(14, 'Oil', 'Commodities', '2020-06-01', 7000.00, 6000.00),
(14, 'Real Estate', 'Property', '2020-07-01', 150000.00, 170000.00),
(15, 'Microsoft Corporation', 'Stocks', '2020-01-01', 8000.00, 9000.00),
(15, 'Oil', 'Commodities', '2020-02-01', 12000.00, 10000.00),
(15, 'Real Estate', 'Property', '2020-03-01', 200000.00, 220000.00),
(16, 'Amazon.com, Inc.', 'Stocks', '2020-01-01', 6000.00, 7000.00),
(16, 'Tesla Inc.', 'Stocks', '2020-02-01', 7000.00, 9000.00),
(16, 'Gold', 'Commodities', '2020-03-01', 10000.00, 12000.00),
(16, 'Real Estate', 'Property', '2020-04-01', 150000.00, 160000.00);


-- TESTING SCENARIO
-- Retrive all customer with corresponding investment
SELECT *
FROM customer c
INNER JOIN investment i ON c.customer_id = i.customer_id;



