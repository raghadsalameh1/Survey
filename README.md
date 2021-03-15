# Survey Managment System ![version-badge]
![Product Gif](Survey_managment_system.gif)

This repo for a CakePHP (version 2.9) Survey Management web application.
It gives you the ability to add Survey, take Survey, and view Submissions for each survey in the system. 

The Add Survey page allows the visitor to add as many Yes/No questions as he/she wants as a chain. 
While creating a survey, next question should be specified based on the given answer. 

## Requirements
- HTTP Server. For example: Apache
- PHP 7
- MySQL

Laragon will take care of that. you can download the full edition from [here](https://laragon.org/download/).

## Getting Started
1. Clone the repo to your device to a folder in C:\laragon\www
2. Prepear your database 
   - create new database with survey name.
   - create the tables.
    ```
    -- survey.surveys definition

    CREATE TABLE `surveys` (
    `Id` int(11) NOT NULL AUTO_INCREMENT,
    `Name` varchar(255) NOT NULL,
    `Email` varchar(255) NOT NULL,
    PRIMARY KEY (`Id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;


    -- survey.visitors definition

    CREATE TABLE `visitors` (
    `Id` int(11) NOT NULL AUTO_INCREMENT,
    `Vdate` date DEFAULT NULL,
    PRIMARY KEY (`Id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=latin1;


    -- survey.notes definition

    CREATE TABLE `notes` (
    `Id` int(11) NOT NULL AUTO_INCREMENT,
    `Visitor_id` int(11) NOT NULL,
    `Note` text NOT NULL,
    PRIMARY KEY (`Id`),
    KEY `Visitor_id` (`Visitor_id`),
    CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`Visitor_id`) REFERENCES `visitors` (`Id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=latin1;


    -- survey.questions definition

    CREATE TABLE `questions` (
    `Id` int(11) NOT NULL AUTO_INCREMENT,
    `Question` text NOT NULL,
    `Survey_id` int(11) DEFAULT NULL,
    `Yes_id` int(11) DEFAULT NULL,
    `No_id` int(11) DEFAULT NULL,
    PRIMARY KEY (`Id`),
    KEY `Survey_id` (`Survey_id`),
    KEY `Yes_id` (`Yes_id`),
    KEY `No_id` (`No_id`),
    CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`Survey_id`) REFERENCES `surveys` (`Id`),
    CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`Yes_id`) REFERENCES `questions` (`Id`),
    CONSTRAINT `questions_ibfk_3` FOREIGN KEY (`No_id`) REFERENCES `questions` (`Id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;


    -- survey.responses definition

    CREATE TABLE `responses` (
    `Id` int(11) NOT NULL AUTO_INCREMENT,
    `Visitor_id` int(11) NOT NULL,
    `Question_id` int(11) NOT NULL,
    `Response` tinyint(1) NOT NULL,
    PRIMARY KEY (`Id`),
    KEY `Visitor_id` (`Visitor_id`),
    KEY `Question_id` (`Question_id`),
    CONSTRAINT `responses_ibfk_1` FOREIGN KEY (`Visitor_id`) REFERENCES `visitors` (`Id`),
    CONSTRAINT `responses_ibfk_2` FOREIGN KEY (`Question_id`) REFERENCES `questions` (`Id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=latin1;
    ```
 3. Update the configuration of connection with database in app/Config/database.php
    ```
    public $default = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'root',
		'password' => '',
		'database' => 'Survey',
		'prefix' => '',
		//'encoding' => 'utf8',
	);
    ```
 4. Update the configuration of email in app/Config/email.php
  ```
  public $gmail = array(
		'host' => 'ssl://smtp.gmail.com',
		'port' => 465,
		'username' => 'your email',
		'password' => 'your password',
		'transport' => 'Smtp',
	);

  ```   
 5. Open Laragon and click start all to run Apache and MySQL. Run the project by click Menu-> www -> your project name.

 Have fun :) 
 
 ## References
 [CakePHP 2.x Cookbook](https://book.cakephp.org/2/en/index.html)

 [version-badge]: https://img.shields.io/badge/version-1.0-blue.svg


