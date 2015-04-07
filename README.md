# What it is
This is a simple script for monitoring a system. Monitors web server (ping) and email server (imap/ping) and tracks time/date of a modified file. 

#How to use

Configure the variables in the index.php file.


###Further info

The tracking of a files modification time and age can be useful in many ways. I'm using it to track a weatherstations generated data that is 
pushed from a local server (at home) to a VPS. If you're not using that part, comment out the HTML code in index.php.

There is a working example at [status.iurl.no](http://status.iurl.no)

##Important!

This script works for me. That does not imply that it works for you - without any modifications.

##Further work

I'm planning to implement some kind of logging and a small API to use with for example a WordPress plugin.

