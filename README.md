This is a prototype for which I am developing a Application & Organisation Management Suite.
I was busy updating it b4 my mom passed which derailed this prototype but pushed me to look at a framework instead which is a new project.

I uninstalled and reinstalled Apache2 and PHP and then followed the steps in:
vhost setup.txt

To do the DB setup, these steps:
Apache2 & MySQL & PHP need to be installed.
To add a mysql user, log into mysql as root then run this:
CREATE DATABASE IF NOT EXISTS `api` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
create USER 'api'@'localhost' IDENTIFIED BY 'Peaches_And_Cream_!'; 
GRANT ALL PRIVILEGES ON `api`.* TO 'api'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;

CREATE DATABASE IF NOT EXISTS `app` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
create USER 'app'@'localhost' IDENTIFIED BY 'Peaches_And_Cream_!'; 
GRANT ALL PRIVILEGES ON `app`.* TO 'app'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;

use api;
source /synaptic4u/REPOS/synaptic4u_prototype/api.synaptic4u.local/api_structure_2024_06_08_1.sql;
source /YOUR/PATH/synaptic4u_prototype/api.synaptic4u.local/api_data_2024_06_08_1.sql;

use api;
source /synaptic4u/REPOS/synaptic4u_prototype/api.synaptic4u.local/api_logs_structure_2024_06_08_1.sql;
source /YOUR/PATH/synaptic4u_prototype/api.synaptic4u.local/api_logs_data_2024_06_08_1.sql;

The system currently only works with the default admin user:
user: emiledewilde2@gmail.com
password: DracutInitMilaDaemon

You can try a new user but I was busy updating it b4 my mom passed which derailed this prototype but pushed me to look at a framework instead which is a new project.

Setup the email provider here:
mailer/SendMail.php

Login into the system without a email provider setup.

To get the login link, go to this file:
app/src/logs/activity.txt
Then search for this: 
Location: Synaptic4U\Packages\Communication\Communication::sendConfirmation(): 1

Copy and paste the url link into your broser tab.
The link will look like this:
https://app.synaptic4u.local/index.php?JGFyZ29uMmlkJHY9MTkkbT02NTUzNix0PTIscD0xJGFCdDhYc2s5bVZVK1JjTGpGSWhkdlEkeUJpR29qYmRuV1ZvaEhIeWt2Mm14bFNWVEFDUFIwU29YbVlvQlkyYVNKVQ~~~~~~~~=f10fedefb3cf14ec30e9303b2c4ac7c9027657b90c2353a4081aeb6091e54a33e7a84b5f6bd1951c57672c094a278370eaf8d5394fd3b28dd63a4b79a41e73166c73784324aa3c0b7d4a39f88fcb25b78e99a7e24c6a155c409895907a85eb50&JGFyZ29uMmlkJHY9MTkkbT02NTUzNix0PTIscD0xJGYrTWR0U2ozUUFYOWkrTzQ0LzdpbmckODQ1QlZTY0tpSmlSRmJ3UWpEWDVEdFRxN0FxR1pMc3FwRnEvUEZON2h4QQ~~~~~~~~=237fbfbaf8b2d1cdf92bf83857fe1748c0f68de03d4766c48d0165e3be47933f4f78c9f3db2b612293a5982b4b97bc168aa7675bb8c5db3602d92ac57edfc8496c7f194b56&JGFyZ29uMmlkJHY9MTkkbT02NTUzNix0PTIscD0xJGxadzFmVUtLQTdTQW1iSXdCTC9uSFEkVHA5a1hmM0tkUTB4TWxaZWxHS0VHNEVKTWhGOTVORW51ZkhhdmVveUlGZw~~~~~~~~=2380f79a2222c311aaf3748e792896911891d1ff255b66c48d014a8b9457f887984ebad4f37612884d00e79730e7b75acf3603f49b8b4c70dcab148bc7937deee556a67d3c2853
