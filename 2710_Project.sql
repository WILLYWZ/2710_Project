
# Customers
CREATE TABLE Customers (
    customerID INT,
    name VARCHAR(255),
    address VARCHAR(255),
    kind VARCHAR(255),
    
    primary key (customerID)
);

# Products
CREATE TABLE Products (
    productID INT,
    name VARCHAR(255),
    inventoryAmount int,
    price DECIMAL(10,2),
    type VARCHAR(255),
    
    primary key (productID)
);


# Transactions
CREATE TABLE Transactions (
    transactionID INT,
    orderNumber INT,
    date DATE,
    SalespersonName VARCHAR(255),
    
    productID int,
    price DECIMAL(10,2),
    quantity int,
    customerID int,
    
    PRIMARY KEY (transactionID),
    
    foreign Key (productID) references Products(productID),
    foreign Key (customerID) references Customers(customerID)
);


# Region
CREATE TABLE Region (
    regionID INT,
    regionName VARCHAR(255),
    regionManager VARCHAR(255),
    
    primary key (regionID)
);

# Store
CREATE TABLE Store (
    storeID INT,
    address VARCHAR(255),
    manager VARCHAR(255),
    salesHeadCount INT,
    regionID INT,
    
    primary key (storeID),
    foreign Key (regionID) references Region(regionID)
);

# Salespersons
CREATE TABLE Salespersons (
    name VARCHAR(255),
    address VARCHAR(255),
    email VARCHAR(255),
    jobTitile VARCHAR(255),
    storeAssigned INT,
    salary DECIMAL(10,2),
    
    primary key (name),
    foreign Key (storeAssigned) references Store(storeID)
);



# INSERT DATA
insert into customers (customerID, name, address, kind) values 
	('50', 'Tom', '5525 Columbo St, Pittsburgh, PA 15206', 'home'),
	('38', 'Melinda', '1315 Kentucky St, Export, PA 15632', 'home'),
	('61', 'Alisa', '7953 Susquehanna St, Pittsburgh, PA 15221', 'home'),
	('74', 'Rick', '2810 Spring St, Pittsburgh, PA 15210', 'home'),
	('19', 'Cameron', '196 Martha Ave, Pittsburgh, PA 15209', 'home');


insert into products (productID, name, inventoryAmount, price, type) values 
	('1', 'umbella', 11, 19.99, 'home'),
	('2', 'chicken wings', 4, 14.99 ,'food'),
	('3', 'bowl', 9, 1.99, 'kitchen'),
	('4', 'notebook', 21, 2.50, 'office'),
	('5', 'bicycle', 7 , 299.00, 'outdoor');


# DO NOT CHANGE REGION TABLE
insert into region (regionID, regionName, RegionManager) values 
	('1', 'North', 'Oscar'),
	('2', 'South' ,'Jeff'),
	('3', 'East' , 'Mike'),
	('4', 'West', 'Selina'),
	('5', 'Central', 'Will');
    
select * from customers;
select * from products;
select * from region;
select * from salespersons;
select * from store;
select * from transactions;


