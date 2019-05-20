![Arcadier](https://theme.zdassets.com/theme_assets/2008942/9566e69f67b1ee67fdfbcd79b1e580bdbbc98874.svg "Arcadier")

These files contain the source code of Arcadier's internally developed Plug-Ins, because we love sharing. Each folder contains one separate Plug-In's source code.

Languages in the files
---------
Since our Marketplaces are web apps, these plug-ins are coded in 
* HTML
* CSS 
* JavaScript
* PHP
***

File Structure
--------------
The file structure of each Plug-in's source code is very specific and is reflected in this repository. For example, the honestbee plug in has `honestbee` as root, with 2 subfolders `admin` and `user`. 

`admin` contains the part of the code that executes for the marketplace administrator. `user` has the code that executes for the merchants and buyers.

***

How to install them as plug in
------------------------------
1. Download the repository as .zip
2. Extract everything.
3. Choose the plug-in you want to install.
   * Honestbee,
   * Stripe, or
   * Mailchimp.
4. Compress the `admin` and `user` folders together into a single root folder. *It has to be this way.*
5. Upload the .zip folder on your Developer Dashboard.
6. Log in to your marketplace.
7. Navigate to Plug-in marketplace
8. Locate the Plug-in and install it.
9. Magic!
