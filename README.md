# Encodeon Sales Rep Manager

## Description:
A plugin that adds a custom table for managing sales representatives. The final plugin will have a world map display that will show all sales rep in a state when that state is clicked.

## Installation:
1. Clone the main encodeon-sales-rep-manager directory to your WordPress plugins folder.
2. Install dependencies by running composer install in the encodeon-sales-rep-manager directory
3. Activate the plugin in your WordPress plugins manager.

Optional PHP Unit Testing:
1. If you need to do PHP Unit testing, you need to install the WordPress Developer Tools by running the following command in the encodeon-sales-rep-manager directory:
git clone git://develop.git.wordpress.org/ wordpress-develop
2. Create an empty database for the unit test.
3. In the wordpress-develop directory, copy the wp-test-config-sample.php to wp-test-config.php. Enter the credentials to your empty database for the test.
### DO NOT enter your regular WordPress database credentials into the wp-test-config.php file. Your data will get wiped from the test. You must use an empty database for the test.
