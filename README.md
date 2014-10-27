Helius - A HackerNews Clone
=======
![enter image description here](https://lh5.googleusercontent.com/-d7_mhr-F9k4/VE5vTuGmeFI/AAAAAAAAIXg/xPBheWCTvi8/w910-h460/Screenshot+from+2014-10-27+21:42:29.png)
Introduction
------------
A simple HackerNews clone written using PHP, and EmberJS. It requires users signing in using a Twitter account. Inspired by Product Hunt and HackerNews and all the HackerNews clones focusing on different sub categories (HackerNews for Bitcoin etc)

This was made as a weekend project while learning EmberJS, you can easily customize this and host it as your own HackerNews for your favourite topic. 

It uses Twitter Bootstrap therefor responsive in design.

Installation
------------
The source code is available at [https://github.com/rukshn/helius.git](https://github.com/rukshn/helius.git). Clone the repository by:

    [root@localhost ~]# git clone https://github.com/rukshn/helius.git

How to use
------------------
Here is a brief introduction to the directories in the repository.

**API :** Contains all the files that handle the backend functions.

**Assets:** Contains the CSS and image files.

**After installation,** 

 1. Create a Twitter app from developer page and replace the consumer key, consumer secret and callback URL variables in the **/api/config.php** file.
 2. Add your database database name, username, password in the **/api/mysql.php** file.
 3. Add the same database name, username, password to db.php file in the root folder and run it to create necessary tables and delete it after that.
 4. Modify the index.htm according to your project by replacing the names of my project and links to Facebook, Twitter, Newsletter etc.
 5. You are good to go after that.

Before you finish reading
-------

 1. It was a weekend project so the coding was in a rush and it might not follow your best practices in coding.
 2. The documentation might be little.
 3. There is lot of room for improvement, please improve on this, fork it, correct errors.
 4. If you hate PHP don't blame me, it may not be perfect but it works.

Dependencies 
-------

 - Jquery
 - Twitter Bootstrap
 - Handlebars JS
 - Ember JS
 
