CREATE TABLE USERS 
(
  USERID INT NOT NULL,
  USERNAME VARCHAR(20) NOT NULL,
  PASSWORD VARCHAR(50) NOT NULL,
  NAME VARCHAR(20) NOT NULL,
  SURNAME VARCHAR(20) NOT NULL,
  AUTHORITY NUMBER(1, 0) NOT NULL
  CONSTRAINT USERS_PK PRIMARY KEY(USERID)
);

CREATE TABLE PUBLISHERS 
(
  publisherID INT NOT NULL,
  p_name VARCHAR(20) NOT NULL
  CONSTRAINT PUBLISHERS_PK PRIMARY KEY(publisherID)
);

CREATE TABLE AUTHORS 
(
  authorID INT NOT NULL,
  a_name VARCHAR(20) NOT NULL,
  a_surname VARCHAR(20) NOT NULL,
  CONSTRAINT author_pk PRIMARY KEY(authorID)
);

CREATE TABLE BOOKS 
(
  bookID INT NOT NULL,
  title VARCHAR(50) NOT NULL,
  stock_num INT NOT NULL,
  authorID not null CONSTRAINT authorID_fk REFERENCES authors(authoID),
  publisherID not null CONSTRAINT publisherID_fk REFERENCES publishers(publisherID,
  CONSTRAINT BOOKS_PK PRIMARY KEY(bookID)
);

CREATE TABLE BORROWS 
(
  borrowID INT NOT NULL,
  borrow_date DATE,
  delivery_date DATE,
  userID not null CONSTRAINT fk_userID REFERENCES users(userID),
  bookID not null CONSTRAINT fk_bookID REFERENCES books(bookID),
  CONSTRAINT borrow_pk PRIMARY KEY(borrowID)
);

CREATE TABLE WAITINGS 
(
  waitingID INT NOT NULL,
  userID not null CONSTRAINT fkUserID REFERENCES users(userID),
  bookID not null CONSTRAINT fkBookID REFERENCES books(bookID),
  CONSTRAINT waiting_pk PRIMARY KEY(waitingID)
);

CREATE TRIGGER `BORROW_TRG` BEFORE INSERT ON `borrows`
FOR EACH ROW 
SET NEW.borrow_date = IFNULL(NEW.borrow_date, NOW()),
	NEW.delivery_date = IFNULL(NEW.delivery_date, DATE_ADD(NOW(),INTERVAL 30 DAY))
	
	