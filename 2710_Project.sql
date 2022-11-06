
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

insert into Customers (customerID, name, address, kind)
values ('50','Tom','5525 Columbo St, Pittsburgh, PA 15206','home');

