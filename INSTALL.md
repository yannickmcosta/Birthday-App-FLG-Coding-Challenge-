## Please ensure you update your URI and other configuration terms from the examples given

### Installing the App

**Requirements**

* Debian 9, Ubuntu 18.10 or equivalent Linux Operating System
* MySQL/MariaDB v5.5 or later
* PHP 7.0 or later
* nginx 1.10 or later

### Nginx Config
```
server {
	listen	443 ssl;
	listen	[::]:443 ssl;

	root	/path/to/webroot;
	index	index.php index.html index.htm @extensionless-php;

	server_name SERVER_NAME;

	location / {
		try_files $uri $uri/ @extensionless-php;
	}

	error_page 404 /404.php;
	error_page 413 /413.php;
	error_page 500 502 503 504 /500.php;
	location = /50x.html {
		root /path/to/webroot;
	}

	location ~ \.php$ {
		try_files $uri =404;
		fastcgi_split_path_info ^(.+\.php)(/.+)$;
		fastcgi_pass unix:/path/to/php7.0-fpm.sock;
		fastcgi_index index.php;
		fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
		include fastcgi_params;
	}

	location @extensionless-php {
		rewrite ^(.*)$ $1.php last;
	}
	ssl					on;
	ssl_certificate				/path/to/cert.pem;
	ssl_certificate_key			/path/to/key.pem;
   	ssl_dhparam				/path/to/dhparams.pem;
	ssl_protocols				TLSv1.1 TLSv1.2;
	ssl_ciphers				EECDH+AESGCM:EDH+AESGCM:AES256+EECDH:AES256+EDH;
	ssl_prefer_server_ciphers		on;
	ssl_session_cache			shared:SSL:10m;
	add_header				Strict-Transport-Security "max-age=31536000; includeSubdomains";
	server_tokens				off;
}
```

### MySQL Schema

`CREATE DATABASE birthday-app;`

```
CREATE TABLE `birthdays` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(63) NOT NULL DEFAULT '' COMMENT 'Name of the individual',
  `user_dob` date NOT NULL COMMENT 'The individuals date of birth',
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The timestamp this record was added',
  `is_public` bit(1) NOT NULL DEFAULT b'1' COMMENT 'If this record is publically viewable, 1 = yes, 0 = no',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_name_dob_key` (`user_name`,`user_dob`),
  KEY `user_name` (`user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
```

### Install the source

Install the sourcecode in your webroot and ensure that you update `config/config.php` to match your machines variables, credentials and workspace.