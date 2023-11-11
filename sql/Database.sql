CREATE TABLE `Account`  (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Username` varchar(255) NULL,
  `Email` varchar(255) NULL,
  `Password` varchar(255) NULL,
  `Fname` varchar(255) NULL,
  `Lname` varchar(255) NULL,
  `Gender` enum('M','F') NULL,
  `RegisterDate` datetime NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE `Booking`  (
  `BookID` varchar(255) NOT NULL,
  `AccID` int NULL,
  `FlightID` varchar(255) NULL,
  `BookDate` datetime NULL,
  `PassengerSeatNumber` varchar(255) NULL,
  `CabinClass` varchar(255) NULL,
  `TicketNumber` varchar(255) NULL,
  `TicketFare` varchar(255) NULL,
  PRIMARY KEY (`BookID`)
);

CREATE TABLE `Flight`  (
  `ID` varchar(255) NOT NULL,
  `FlightName` varchar(255) NULL,
  `FlightSchedule` datetime NULL,
  `PassengerCount` int NULL,
  `Origin` varchar(255) NULL,
  `Distination` varchar(255) NULL,
  `BoardingTime` datetime NULL,
  `DepartureTime` datetime NULL,
  `TicketFare` enum('2500','5000','10000') NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE `SupportTicket`  (
  `TicketNo` varchar(255) NOT NULL,
  `UserId` int NULL,
  `Username` varchar(255) NULL,
  `Message` varchar(255) NULL,
  `ResolvedIssue` enum('Yes','No') NULL,
  PRIMARY KEY (`TicketNo`)
);

ALTER TABLE `Booking` ADD CONSTRAINT `AccountID_FKey` FOREIGN KEY (`AccID`) REFERENCES `Account` (`ID`);
ALTER TABLE `Booking` ADD CONSTRAINT `FlightID_FKey` FOREIGN KEY (`FlightID`) REFERENCES `Flight` (`ID`);

