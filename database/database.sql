DROP DATABASE IF EXISTS the_sixth_string;
CREATE DATABASE IF NOT EXISTS the_sixth_string;

CREATE TABLE brand (
    id int(11) NOT NULL AUTO_INCREMENT,
    brand_name varchar(50) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE product (
    id int(11) NOT NULL AUTO_INCREMENT,
    product_name varchar(255) NOT NULL,
    brand_id int(4) NOT NULL,
    price float(7,2) NOT NULL,
    quantity int(4) NOT NULL,
    description text NULL,
    video_url varchar(255) NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (brand_id) REFERENCES brand(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- maybe create tables to add multiple images and videos to a product?

CREATE TABLE category (
    id int(11) NOT NULL AUTO_INCREMENT,
    category_name varchar(50) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE product_category (
    product_id int(11) NOT NULL,
    category_id int(11) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES product(id),
    FOREIGN KEY (category_id) REFERENCES category(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE user (
    id int(11) NOT NULL AUTO_INCREMENT,
    first_name varchar(50) NOT NULL,
    infix varchar(20) NULL,
    last_name varchar(50) NOT NULL,
    phone_number int(11) NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE auth (
    user_id int(11) NOT NULL,
    password varchar(256) NOT NULL,
    email varchar(50) NOT NULL,
    active tinyint(1) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE employee (
    user_id int(11) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE address (
  id INT NOT NULL AUTO_INCREMENT,
  street_name VARCHAR(50) NOT NULL,
  zipcode VARCHAR(6) NOT NULL,
  house_number VARCHAR(45) NOT NULL,
  city VARCHAR(45) NULL DEFAULT NULL,
  country VARCHAR(45) NULL DEFAULT NULL,
  address_type VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (id))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

CREATE TABLE user_has_address (
  id INT NOT NULL AUTO_INCREMENT,
 address_id INT NOT NULL,
  user_id INT NOT NULL,
  PRIMARY KEY (id),
 FOREIGN KEY (address_id)
REFERENCES the_sixth_string.address (id),   
    FOREIGN KEY (user_id)
    REFERENCES the_sixth_string.user (id)
  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;
