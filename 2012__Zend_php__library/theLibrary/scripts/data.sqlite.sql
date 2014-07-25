INSERT INTO users (firstName, lastName, username, email, password, role, active, activationCode) VALUES
    ('Philipp', 'Schaffer', 'philippadmin', 'p.schaffer@absolut-gps.com', 'philipp', 'admin', '0', '234567655325');

INSERT INTO users (firstName, lastName, username, email, password, role, active, activationCode) VALUES
   ('Philipp', 'Schaffer', 'philippmember', 'member@noemail.com', 'philipp', 'member', '1', '23553254810');
    
INSERT INTO users (firstName, lastName, username, email, password, role, active, activationCode) VALUES
    ('Philipp', 'Schaffer', 'philippguest', 'guest@noemail.com', 'philipp', 'guest', '1', '234567655325');    
    
INSERT INTO users (firstName, lastName, username, email, password, role, active, activationCode) VALUES
    ('Jesus', 'Fernandez', 'jesfer9', 'foo@bar.com', 'abcabc', 'guest', '1', '67678542556576');
    
INSERT INTO users (firstName, lastName, username, email, password, role, activationCode) VALUES
    ('Raul', 'Rodriguez', 'raurod', 'fau@bar.com', 'abcabc', 'member', '44575645556');
    
INSERT INTO users (firstName, lastName, username, email, password, role, active, activationCode) VALUES
    ('Ana', 'Fernandez', 'anafer9', 'ana@bar.com', 'abcabc', 'admin', '1', '234567654325');
    
INSERT INTO users (firstName, lastName, username, email, password, role, active, activationCode) VALUES
    ('Jesus', 'Real', 'jrs10', 'jesusrealserran@hotmail.com', 'jesus', 'admin', '1', '234567655325');
    
    
INSERT INTO books (asin, ean, title, content, edition, publisher, publicationDate, numberOfPages, formattedPrice, detailPageUrl, imageUrl) VALUES
    ('1dfda8f987', 'ef51dfda8f987', 'A comprehensive list of paths near Leipzig', 
    'bla bla bla bla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla bla',
    '2', 'McKinley', '2012-10-02', '224', '&euro;45',
    'http://www.amazon.co.uk/Learning-MySQL-JavaScript-Step-Step/dp/1449319262%3FSubscriptionId%3DAKIAIQSGFKEGEGRAA52A%26tag%3Dphp%26linkCode%3Dxm2%26camp%3D2025%26creative%3D165953%26creativeASIN%3D1449319262',
    'http://ecx.images-amazon.com/images/I/41NSMXp2hWL.jpg');    
    
INSERT INTO books (asin, ean, title, content, edition, publisher, publicationDate, numberOfPages, detailPageUrl, imageUrl) VALUES
    ('riovrif423', 'dfdaiovrif423', 'A comprehensive list of paths near Munich', 
    'bla bla bla bla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla bla',
    '6', 'NorthWest', '2011-05-26', '143',
    'http://www.amazon.co.uk/Learning-MySQL-JavaScript-Step-Step/dp/1449319262%3FSubscriptionId%3DAKIAIQSGFKEGEGRAA52A%26tag%3Dphp%26linkCode%3Dxm2%26camp%3D2025%26creative%3D165953%26creativeASIN%3D1449319262',    
    'http://ecx.images-amazon.com/images/I/41NSMXp2hWL.jpg');

INSERT INTO books (asin, ean, title, content, edition, publisher, publicationDate, numberOfPages, formattedPrice, detailPageUrl, imageUrl) VALUES
    ('49fk3f844d', '4f9fk35fs844d', 'A comprehensive list of paths near Berlin', 
    'bla bla bla bla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla bla',
    '1', 'McCalaghan', '2012-02-12', '248', '&euro;20.99',
    'http://www.amazon.co.uk/Learning-MySQL-JavaScript-Step-Step/dp/1449319262%3FSubscriptionId%3DAKIAIQSGFKEGEGRAA52A%26tag%3Dphp%26linkCode%3Dxm2%26camp%3D2025%26creative%3D165953%26creativeASIN%3D1449319262',
    'http://ecx.images-amazon.com/images/I/41NSMXp2hWL.jpg');    



INSERT INTO loans (email, asin, overdue, mailSent) VALUES
    ('ralph.schindler@zend.com',
    '1449319262',
    DATETIME('NOW'), '1');
INSERT INTO loans (email, asin, overdue, mailSent) VALUES
    ('jesusrealserrano@hotmail.com',
    '1dfda8f987',
    DATETIME('NOW'), '0');
INSERT INTO loans (email, asin, overdue, mailSent) VALUES
    ('p.schaffer@absolut-gps.com',
     'riovrif423',
     DATETIME('NOW'), '0');