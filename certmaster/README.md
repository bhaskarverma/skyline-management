# FreeSSL.tech Auto

Complete automation for all the Let's Encrypt processes! 

This app/client issues and installs free SSL certificates automatically in <b>shared hosting cPanel</b>. Root access is NOT required. Command line experience NOT needed. This app/client also works on non-cPanel hosting except auto-installation.

 - Less tech-savvy people can manage this app efficiently with the browser-based dashboard.
 - Wildcard SSL supported.
 - This app is mobile responsive. So you can manage this app with your mobile phone also!

# Features

1.    You can install and configure this client/app using your favorite web browser.
2.    Option to generate a free SSL certificate before you install the app. You get this option if you attempt to install the app over HTTP. So, no need to enter database and login credentials on an insecure page during installation of this app.
3.    Let’s Encrypt ACME version 2 API supported.
4.    Get WildCard SSL certificate for free!
5.    This app installs free SSL certificate automatically on cPanel shared hosting.
6.    Choose the number of days before the expiry date you want to renew the SSL. The default value is 30, which is recommended by Let's Encrypt.
7.    Save SSL certificate and private key above the document root of your web hosting account (usually public_html).
8.    You can custom name the directory in which the app stores private keys and SSL certificates.
9.    How long is the SSL certificate key length? As per your choice. The default is 2048 bytes/bit.
10.  Save the sensitive information (password/API secret) using open SSL encryption.
11.   You can create a cron job with one click, which runs daily. No Linux command knowledge required.
12.    For free wildcard SSL certificate, available DNS service providers are cPanel, Godaddy, Cloudflare, and Namecheap. If your DNS service provider is other than the four, please set DNS TXT record manually. The app sends you an automated email with the required DNS TXT record details when needed.
13.  If your DNS service provider is supported, this app sets DNS TXT record automatically. Then the app waits 2 minutes before sending challenges to the Let’s Encrypt server to validate the domains. If your DNS service provider usually takes more than 2 minutes to propagates TXT records, the app has an option for you to make the app wait more than 2 minutes.
14.   In the case of manually set DNS TXT record, the app waits until the record propagates out. However, some hosts may terminate the cron job if your DNS provider takes hours to propagate. In that case, you need to choose non-wildcard SSL for each sub-domain. The app has an option for this.
15. The admin interface of this app is mobile responsive.
16. You can revoke SSL cert or change the Let's Encrypt account key if required.


# Minimum System Requirements

1.    Linux hosting (this client/app is NOT compatible with Windows server)
2.    PHP 5.6
3.    MySQL 5 or MariaDB 10
4.    OpenSSL extension
5.    Curl extension
6.    MySQLi extension
7.    PHP directive allow_url_fopen = On
8.    For automatic SSL installation, you need the SSL installation feature enabled for your cPanel.

<b>Case 1:</b> This app uses cPanel API to install SSL certificate. So, if your web hosting control panel is NOT cPanel, but you have SSL installation option, this app doesn't install SSL automatically.  You can install the free SSL certificate manually.

<b>Case 2:</b> If your web hosting control panel is cPanel but SSL installation feature is DISABLED, you can contact your web hosting service provider for installation of the SSL certificate.

In either case, all the processes (Let’s Encrypt account registration, domain validation, issue/renew free SSL certificate) are automatic except the installation of free SSL certificate. You’ll receive the automated email in the event of issue/renewal of free SSL certificate with the path details of the SSL certificate, private key and CA bundle. You need to install the SSL certificate yourself (case 1) manually or with the help of your web hosting provider (case 2).

----------------------------------------------------------------------
# Dependencies:

'FreeSSL.tech Auto' has the dependency of the following packages and uses composer for autoloading.
1.    <a href="https://github.com/usrflo/registered-domain-libs">registered-domain-libs</a>
2.    <a href="https://github.com/ircmaxell/password_compat">password_compat</a>
3.    <a href="https://github.com/indigophp/hash-compat">hash_*() compat</a>

# Documentation

Please click this link (https://freessl.tech/documentation-free-ssl-certificate-automation) for the detail documentation with screenshots.

# Credits

 - <a href="https://letsencrypt.org">Let's Encrypt</a>
 - We have developed this app/client with a huge rewrite of <a href="https://github.com/analogic/lescript">Lescript</a>.
 - <a href="https://cpanel.com" target="_blank">cPanel</a>
