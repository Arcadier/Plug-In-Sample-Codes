![Arcadier](https://theme.zdassets.com/theme_assets/2008942/9566e69f67b1ee67fdfbcd79b1e580bdbbc98874.svg "Arcadier")

Sample Packages
===============
The term package here refers to Plug-ins. Their purpose is to add very specific features to our core product, which are not needed by every marketplace owner, but still valuable to some. Hence, we allow those who need them to install/uninstall those features as plug-ins.

These customers sometimes approach developers to help them build these plug ins. So, on top of our [API documentation](apiv2.arcadier.com), and [Plug-In Documentation](#), this repository provides examples of how everything is brought together to create a fully functional plug-in. These plug-ins only work on Arcadier's marketplaces which are on a **Growth**, **Scale**, or **Enterprise** plans. 

This repository contains recipes' examples. Those recipes aim at showing basic concepts behind creating plug-ins. I.e, most commonly coded functions like
* Creating new pages for Admin/Merchant/Buyer/All
--* Using HTML only
--* Using JS only
--* Using both HTML and JS
* Adding changes to existing pages for Admin/Merchant/Buyer/All
--* Using JS and relevant libraries (jQuery), combined with APIs
* How to store/retrieve data on Arcadier's Database
--* Using JS and Custom fields on the [Developer Dashboard](#)
* How to use our API's the most efficiently
--* Using JS and common code snippets like:

```javascript
//This function is used in almost all API calls when Authenticating                             
function getCookie(name){
    var value = '; ' + document.cookie;
    var parts = value.split('; ' + name + '=');
    if (parts.length === 2) {
        return parts.pop().split(';').shift();
    }
}
```
* Combining those basic concepts make whole plug-ins, and are reflected in Honestbee's and Mailchimp's examples.
***

Languages
---------
Since our Marketplaces are web apps, these plug-ins are coded in 
* HTML
* CSS 
* JavaScript

Our server-side language is in PHP.
***

File Structure
--------------
As explained in our [Plug-in documentation](#), the file structure of each Plug-in's source code is very specific and is reflected in this repository. For example, the honestbee plug in has `honestbee` as root, with 2 subfolders `admin` and `user`. 

`admin` contains the part of the code that executes for the marketplace administrator. `user` has the code that executes for the merchants and buyers.

***

How to install them as plug in
------------------------------
1. Download the repository as .zip
2. Extract everything
3. Choose the plug-in you want to install
   * Honestbee,
   * Stripe, or
   * Mailchimp
4. Compress the `admin` and `user` folders together into a single root folder.
5. Upload the .zip folder on your [Developer Dashboard](#)

