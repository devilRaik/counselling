# SET FOREIGN_KEY_CHECKS = 0; <----This is for unset current table from referenced
# TRUNCATE TABLE referenced_table_name; <----- Work in current table
# SET FOREIGN_KEY_CHECKS = 1; <----This is for set current table as referenced
<!--  -->
# CREATE TABLE `states` (`state_id` INT AUTO_INCREMENT PRIMARY KEY,`state_name` VARCHAR(100) NOT NULL);
<!-- City Table is Taking referenct of state table by id -->
# CREATE TABLE `cities` (`city_id` INT AUTO_INCREMENT PRIMARY KEY, `city_name` VARCHAR(100) NOT NULL, `state_id` INT NOT NULL, FOREIGN KEY (`state_id`) REFERENCES `states`(`state_id`) ON DELETE CASCADE);

# CREATE TABLE `schools` (`school_id` INT AUTO_INCREMENT PRIMARY KEY, `school_name` VARCHAR(100) NOT NULL, `city_id` INT NOT NULL, FOREIGN KEY (`city_id`) REFERENCES `cities`(`city_id`) ON DELETE CASCADE);
);
