
create database project;
use project;

CREATE TABLE Customers (
    customerID VARCHAR(20),
    name VARCHAR(255),
    address VARCHAR(255),
    kind VARCHAR(255),
    PRIMARY KEY (customerID)
);


CREATE TABLE Products (
    productID VARCHAR(20),
    name VARCHAR(255),
    inventoryAmount INT,
    price DECIMAL(10 , 2 ),
    type VARCHAR(255),
    PRIMARY KEY (productID)
);


CREATE TABLE Transactions (
    transactionID VARCHAR(20),
    orderNumber VARCHAR(20),
    date DATE,
    SalespersonName VARCHAR(255),
    productID VARCHAR(20),
    price DECIMAL(10 , 2 ),
    quantity INT,
    customerID VARCHAR(20),
    PRIMARY KEY (transactionID),
    FOREIGN KEY (productID)
        REFERENCES Products (productID),
    FOREIGN KEY (customerID)
        REFERENCES Customers (customerID)
);


CREATE TABLE Region (
    regionID VARCHAR(20),
    regionName VARCHAR(255),
    regionManager VARCHAR(255),
    PRIMARY KEY (regionID)
);


CREATE TABLE Store (
    storeID VARCHAR(20),
    address VARCHAR(255),
    manager VARCHAR(255),
    salesHeadCount INT,
    regionID VARCHAR(20),
    PRIMARY KEY (storeID),
    FOREIGN KEY (regionID)
        REFERENCES Region (regionID)
);


CREATE TABLE Salespersons (
    name VARCHAR(255),
    address VARCHAR(255),
    email VARCHAR(255),
    jobTitle VARCHAR(255),
    storeAssigned VARCHAR(20),
    salary DECIMAL(10 , 2 ),
    PRIMARY KEY (name),
    FOREIGN KEY (storeAssigned)
        REFERENCES Store (storeID)
);


select * from customers;
select * from products;
select * from region;
select * from salespersons;
select * from store;
select * from transactions;