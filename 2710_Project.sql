CREATE TABLE Customers (
    customerID VARCHAR(20),
    name VARCHAR(255),
    address VARCHAR(255),
    kind VARCHAR(255),
    
    primary key (customerID)
);


CREATE TABLE Products (
    productID VARCHAR(20),
    name VARCHAR(255),
    inventoryAmount int,
    price DECIMAL(10,2),
    type VARCHAR(255),
    
    primary key (productID)
);


CREATE TABLE Transactions (
    transactionID VARCHAR(20),
    orderNumber VARCHAR(20),
    date DATE,
    SalespersonName VARCHAR(255),
    
    productID VARCHAR(20),
    price DECIMAL(10,2),
    quantity int,
    customerID VARCHAR(20),
    
    PRIMARY KEY (transactionID),
    foreign Key (productID) references Products(productID),
    foreign Key (customerID) references Customers(customerID)
);


CREATE TABLE Region (
    regionID VARCHAR(20),
    regionName VARCHAR(255),
    regionManager VARCHAR(255),
    
    primary key (regionID)
);


CREATE TABLE Store (
    storeID VARCHAR(20),
    address VARCHAR(255),
    manager VARCHAR(255),
    salesHeadCount INT,
    regionID VARCHAR(20),
    
    primary key (storeID),
    foreign Key (regionID) references Region(regionID)
);


CREATE TABLE Salespersons (
    name VARCHAR(255),
    address VARCHAR(255),
    email VARCHAR(255),
    jobTitle VARCHAR(255),
    storeAssigned VARCHAR(20),
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
('19', 'Cameron', '196 Martha Ave, Pittsburgh, PA 15209', 'home'),
('100', 'Bruce', '653 Broadway, New York, NY 10012', 'business'),
('101', 'Frank', '111 Dryden Rd, Ithaca, NY 15213', 'home'),
('102', 'Alice', '475 5th Ave, New York, NY 10017', 'business'),
('103', 'Adele', '475 Garner Court, Pittsburgh, PA 15213', 'home'),
('104', 'Chris', '655 Madison Ave, New York, NY 10065', 'business'),
('105', 'Kathrina', '1402 3rd Street Promenade, Santa Monica, CA 90401', 'business');


insert into products (productID, name, inventoryAmount, price, type) values 
('1', 'umbrella', 11, 19.99, 'home'),
('2', 'chicken wings', 4, 14.99 ,'food'),
('3', 'bowl', 9, 1.99, 'kitchen'),
('4', 'notebook', 21, 2.50, 'office'),
('5', 'bicycle', 7 , 299.00, 'outdoor'),
('11', 'earrings', 20, 89.05, 'jewelry'),
('12', 'helmet', 45, 15.00, 'outdoor'),
('13', 'flatware', 23, 9.98, 'kitchen'),
('14', 'pajama', 34, 63.33, 'clothes'),
('15', 'table', 5, 129, 'home'),
('16', 'americano', 100, 3.99 ,'food');


insert into region (regionID, regionName, RegionManager) values 
('1', 'North', 'Oscar'),
('2', 'South' ,'Jeff'),
('3', 'East' , 'Mike'),
('4', 'West', 'Selina'),
('5', 'Central', 'Will');
    

insert into store (storeID, address, manager, salesHeadCount, regionID) values
('1111', '50 University Ave, Los Gatos, CA 95030', 'Doris', 3, '4'),
('1112', '469 Broome St, New York, NY 10013', 'Isabel', 5, '3'),
('1113', '424 Park Ave S, New York, NY 10016', 'Patrick', 15, '3'),
('1114', '1000 Universal Studios Plaza, Orlando, FL 32819', 'Boggie', 19, '2'),
('1115', '1402 S Havana St, Aurora, CO 80012', 'Magic', 22, '5');


insert into salespersons (name, address, email, jobTitle, storeAssigned, salary) values
('Jane', '26 Vandam St, New York, NY 10013', 'jane11@gmail.com', 'sales rep', '1112', 3500),
('Jimmy', '8 Spruce St, New York, NY 10038', 'jimmylookingforjobs@gmail.com', 'store manager', '1113', 4200),
('Spencer', '1641 Lincoln Blvd, Santa Monica, CA 90404', 'spencerlee@hotmail.com', 'store manager', '1111', 5400),
('Alex', '1641 Lincoln Blvd, Santa Monica, CA 90404', 'alex97@gmail.com', 'sales rep', '1111', 3890),
('Mindy', '13675 Lake Vining Dr, Orlando, FL 32821', 'mindycso@outlook.com', 'chief sales officer', '1114', 6550);


insert into transactions (transactionID, orderNumber, date, SalespersonName, productID, price, quantity, customerID) values
('1999A01', 'A11110', '2019-1-1', 'Alex', '14', 63.33, 2, '100'),
('1999A11', 'A11111', '2019-1-13', 'Alex', '11', 89.05, 1, '103'),
('2000B98', 'N11112', '2018-11-11', 'Spencer', '1', 19.99, 5, '50'),
('2000B17', 'N11113', '2019-12-12', 'Jane', '2', 14.99, 2, '19'),
('2001A77', '500001', '2020-9-19', 'Jimmy', '13', 9.98, 15, '74'),
('2001N11', '500002', '2017-6-25', 'Jane', '16', 3.99, 1, '104');


select * from customers;
select * from products;
select * from region;
select * from salespersons;
select * from store;
select * from transactions;
