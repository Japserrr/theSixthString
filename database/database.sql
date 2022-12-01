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



