<?php
if(!is_dir(__DIR__.'/db'))
    mkdir(__DIR__.'/db');
if(!defined('db_file')) define('db_file',__DIR__.'.././db/cashier_queuing_db.db');
if(!defined('tZone')) define('tZone',"Asia/Manila");
if(!defined('dZone')) define('dZone',ini_get('date.timezone'));
function my_udf_md5($string) {
return md5($string);
}

Class DBConnection extends SQLite3{
    protected $db;
    function __construct(){
        $this->open(db_file);
        $this->createFunction('md5', 'my_udf_md5');
        // $this->exec("PRAGMA foreign_keys = ON;");

        $this->exec("CREATE TABLE IF NOT EXISTS `superadmin_list` (
            `admin_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `fullname` INTEGER NOT NULL,
            `username` TEXT NOT NULL,
            `password` TEXT NOT NULL,
            `status` INTEGER NOT NULL Default 1,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"); 
    
        $this->exec("CREATE TABLE IF NOT EXISTS `administrator_list` (
            `user_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `lastname` INTEGER NOT NULL,
            `firstname` INTEGER NOT NULL,
            `MI` INTEGER,
            `username` TEXT NOT NULL,
            `password` TEXT NOT NULL,
            `status` INTEGER NOT NULL Default 1,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"); 

        $this->exec("CREATE TABLE IF NOT EXISTS `cashier_list` (
            `cashier_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `name` TEXT NOT NULL,
            `log_status` INTEGER NOT NULL DEFAULT 2,
            `status` INTEGER NOT NULL DEFAULT 1,
            `username` TEXT NOT NULL,
            `password` TEXT NOT NULL
        )");
        $this->exec("CREATE TABLE IF NOT EXISTS `queue_list` (
            `queue_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `queue` TEXT NOT NULL,
            `customer_name` TEXT NOT NULL,
            `status` INTEGER NOT NULL DEFAULT 2,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `cashier_id` INTEGER,
            `teller_id` INTEGER,
            `student_id` TEXT,
            `guest_id` INTEGER,
            FOREIGN KEY(`student_id`) REFERENCES `student_list`(`student_id`),
            FOREIGN KEY(`teller_id`) REFERENCES `teller_list`(`teller_id`),
            FOREIGN KEY(`cashier_id`) REFERENCES `cashier_list`(`cashier_id`),
            FOREIGN KEY(`guest_id`) REFERENCES `guest_list`(`guest_id`)
        )");
        $this->exec("CREATE TABLE IF NOT EXISTS `queue_list_liv` (
            `queue_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `queue` TEXT NOT NULL,
            `customer_name` TEXT NOT NULL,
            `status` INTEGER NOT NULL DEFAULT 2,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `cashier_id` INTEGER,
            `teller_id` INTEGER,
            `student_id` TEXT,
            `guest_id` INTEGER,
            FOREIGN KEY(`student_id`) REFERENCES `student_list`(`student_id`),
            FOREIGN KEY(`teller_id`) REFERENCES `teller_list`(`teller_id`),
            FOREIGN KEY(`cashier_id`) REFERENCES `cashier_list`(`cashier_id`),
            FOREIGN KEY(`guest_id`) REFERENCES `guest_list`(`guest_id`)
        )");
        // sa
        $this->exec("CREATE TABLE IF NOT EXISTS `queue_list_sa` (
            `queue_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `queue` TEXT NOT NULL,
            `customer_name` TEXT NOT NULL,
            `status` INTEGER NOT NULL DEFAULT 2,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `cashier_id` INTEGER,
            `teller_id` INTEGER,
            `student_id` TEXT,
            `guest_id` INTEGER,
            FOREIGN KEY(`student_id`) REFERENCES `student_list`(`student_id`),
            FOREIGN KEY(`teller_id`) REFERENCES `teller_list`(`teller_id`),
            FOREIGN KEY(`cashier_id`) REFERENCES `cashier_list`(`cashier_id`),
            FOREIGN KEY(`guest_id`) REFERENCES `guest_list`(`guest_id`)
        )");
        // end sa

        // enrollent
        $this->exec("CREATE TABLE IF NOT EXISTS `enrollment` (
            `queue_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `queue` TEXT NOT NULL,
            `customer_name` TEXT NOT NULL,
            `status` INTEGER NOT NULL DEFAULT 2,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `cashier_id` INTEGER,
            `teller_id` INTEGER,
            `student_id` TEXT,
            `guest_id` INTEGER,
            FOREIGN KEY(`student_id`) REFERENCES `student_list`(`student_id`),
            FOREIGN KEY(`teller_id`) REFERENCES `teller_list`(`teller_id`),
            FOREIGN KEY(`cashier_id`) REFERENCES `cashier_list`(`cashier_id`),
            FOREIGN KEY(`guest_id`) REFERENCES `guest_list`(`guest_id`)
        )");
        // end enrollment

        // medicine
        $this->exec("CREATE TABLE IF NOT EXISTS `medicine` (
            `queue_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `queue` TEXT NOT NULL,
            `customer_name` TEXT NOT NULL,
            `status` INTEGER NOT NULL DEFAULT 2,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `cashier_id` INTEGER,
            `teller_id` INTEGER,
            `student_id` TEXT,
            `guest_id` INTEGER,
            FOREIGN KEY(`student_id`) REFERENCES `student_list`(`student_id`),
            FOREIGN KEY(`teller_id`) REFERENCES `teller_list`(`teller_id`),
            FOREIGN KEY(`cashier_id`) REFERENCES `cashier_list`(`cashier_id`),
            FOREIGN KEY(`guest_id`) REFERENCES `guest_list`(`guest_id`)
        )");
        // end medicine


        $this->exec("CREATE TABLE IF NOT EXISTS `guest_list` (
            `guest_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `guest_name` TEXT 
        )");
        $this->exec("CREATE TABLE IF NOT EXISTS `student_list` (
            `student_id` TEXT NOT NULL PRIMARY KEY,
            `student_FN` TEXT,
            `student_LN` TEXT,
            `student_MI` TEXT,
            `student_email` TEXT,
            `student_course` TEXT
        )");
        $this->exec("CREATE TABLE IF NOT EXISTS `teller_list` (
            `teller_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `teller_name` TEXT,
            `status` INTEGER NOT NULL DEFAULT 1,
            `log_status` INTEGER NOT NULL DEFAULT 2
        )");
        $this->exec("CREATE TABLE IF NOT EXISTS `trasaction_list` (
            `trasaction_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `trasaction_name` TEXT
        )");
        $this->exec("CREATE TABLE IF NOT EXISTS `queuing_start_end` (
            `start_end_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            'default_start_time'	TIMESTAMP,
	        'default_cutoff_time'	TIMESTAMP,
	        'manual_cutoff_time'	TIMESTAMP
        )");


         //$this->exec("INSERT or IGNORE INTO `user_list` VALUES (1,'Administrator','admin',md5('admin123'),1, CURRENT_TIMESTAMP)");
        
    }
    function __destruct(){
        $this->close();
    }
}

$conn = new DBConnection();