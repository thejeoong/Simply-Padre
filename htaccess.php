DirectorySlash Off

RewriteEngine On
RewriteBase /

RewriteCond %{HTTP:X-Forwarded-Proto} !https
RewriteCond %{HTTPS} !=on
RewriteRule ^.*$ https://%{SERVER_NAME}%{REQUEST_URI} [R=301,L]
RewriteCond %{HTTP_HOST} !^www.simplypadre.com$ [NC]
RewriteRule ^(.*)$ https://www.simplypadre.com/$1 [R=301,L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . index.php [L]
<IfModule mod_maxminddb.c>
# Allow specific IP addresses before processing country rules
    <RequireAny>
        ### SERVER IP, DO NOT REMOVE
        Require ip 66.147.237.19
        Require ip 66.147.238.50
        ### SERVER IP, DO NOT REMOVE
    </RequireAny>
    
# Setting to allow or deny by country enabled. 
# Edit in the general settings > localization tab
    SetEnvIf MM_COUNTRY_CODE ^(AF|AL|BD|IN|PK|SY)$ BlockCountry
    <RequireAll>
        Require all granted
        Require not env BlockCountry
    </RequireAll>
</IfModule>


# Block /photo-albums page
RedirectMatch 301 ^/photo-albums/?$ /

# php -- BEGIN cPanel-generated handler, do not edit
# Set the “ea-php72” package as the default “PHP” programming language.
<IfModule mime_module>
  AddHandler application/x-httpd-ea-php72 .php .php7 .phtml
</IfModule>
# php -- END cPanel-generated handler, do not edit
