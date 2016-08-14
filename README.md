robotsimulator 
========================

A robot simulator that tests the various scenarios and commands

Requirements
------------

  * PHP 5.5.9 or higher;
  * MYSQL database;
  * Symfony 3.1 and usual Symfony application requirements;
  
Installation
------------
Download this repository to your local directory (say 'robot') and run the following commands:
```bash
$ cd robot 
$ php composer.phar update
```
Create your local MYSQL database and table:
```bash
CREATE DATABASE demo DEFAULT CHARACTER SET utf8  DEFAULT COLLATE utf8_general_ci;
USE DATABASE demo;

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `shop`
-- ----------------------------
DROP TABLE IF EXISTS `shop`;
CREATE TABLE `shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `robot`
-- ----------------------------
DROP TABLE IF EXISTS `robot`;
CREATE TABLE `robot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) DEFAULT NULL,
  `pos_x` int(11) DEFAULT NULL,
  `pos_y` int(11) DEFAULT NULL,
  `heading` char(1) DEFAULT NULL,
  `commands` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shop_id` (`shop_id`),
  CONSTRAINT `robot_ibfk_1` FOREIGN KEY (`shop_id`) REFERENCES `shop` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

```


Usage
-----
Use the built-in web server:
```bash
$ php bin/console server:run
```

Demo
-----
View a running demo at <a href="http://ec2-52-62-172-4.ap-southeast-2.compute.amazonaws.com/">AWS</a>.
```json 
{
"width": "int"
"height": "int"
}
```
API Usage
<table>
 <tr><th>HTTP method</th><th>Endpoint</th><th>Description</th><th>Request</th><th>Response</th></tr>
 <tr><td>POST</td><td>/shop</td><td>Creates a new shop, with the required dimensions</td><td>
```json 
{
width: int
height: int
}
```
</td><td>
{
id: int
width: int
height: int
}
</td></tr>
</table>
