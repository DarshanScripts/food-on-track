CREATE DATABASE FoodOnTrack;

USE FoodOnTrack;


CREATE TABLE Admin
(
    aId INT PRIMARY KEY AUTO_INCREMENT,
    firstName VARCHAR(15) NOT NULL,
    lastName VARCHAR(15) NOT NULL,
    email VARCHAR(35) NOT NULL,
    password CHAR(32),
    creationDate DATETIME
);


CREATE TABLE Customer
(
    cId INT PRIMARY KEY AUTO_INCREMENT,
    firstName VARCHAR(15) NOT NULL,
    lastName VARCHAR(15) NOT NULL,
    email VARCHAR(35) NOT NULL,
    password CHAR(32),
    creationDate DATETIME
);


CREATE TABLE Menu (
    mId INT(11) PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    description VARCHAR(500) NOT NULL,
    price VARCHAR(50) NOT NULL,
    image VARCHAR(255) NOT NULL
)

INSERT INTO `Menu` (`mId`, `name`, `type`, `description`, `price`, `image`) VALUES
(1, 'Samosa', 'Veg', 'Samosa is a fried or baked pastry with a savoury filling, such as spiced potatoes, onions, peas, cheese, meat, or lentils.', '15', 'samosa.jpg'),
(2, 'Jalebi', 'Veg', 'Jalebi is made by deep-frying maida flour batter in pretzel or circular shapes, which are then soaked in sugar syrup.', '49', 'jalebi.jpg'),
(3, 'Naan', 'Veg', 'Naan is a leavened, oven-baked flatbread.', '12', 'naan.jpg'),
(4, 'Paneer Bhurji', 'Veg', 'Scrambled Indian cottage cheese with onion, tomatoes and spices.', '50', 'paneer-bhurji.jpg'),
(5, 'Pulav', 'Veg', 'Vegetable Pulao (Veg Pulav) is a spicy rice dish prepared by cooking rice with various vegetables and spices.', '60', 'pulav.jpg'),
(6, 'Biryani', 'Non Veg', 'Chicken Biryani is a delicious savory rice dish loaded with spicy marinated chicken, caramelized onions, and flavorful saffron rice.', '100', 'biryani.jpg'),
(7, 'Fish', 'Non Veg', 'Fish fry is a meal containing battered or breaded fried fish.', '100', 'fish.jpg'),
(8, 'Butter Chicken', 'Non Veg', 'Butter chicken or makhan murg is a dish, originating in the Indian subcontinent, of chicken in a mildly spiced tomato sauce.', '99', 'Butter-Chicken.jpg');


CREATE TABLE Cart
(
    cId INT,
    mId INT,
    PRIMARY KEY(cId,mId),
    FOREIGN KEY (cId) REFERENCES Customer(cId),
    FOREIGN KEY (mId) REFERENCES Menu(mId)
);


CREATE TABLE Orders
(
    orderId INT(11) PRIMARY KEY AUTO_INCREMENT,
    cId INT,
    mId INT,
    orderDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cId) REFERENCES Customer(cId),
    FOREIGN KEY (mId) REFERENCES menu(mId)
);
