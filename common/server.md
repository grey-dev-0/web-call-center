=== "Nginx"

    If you don't have nginx configuration file for the project yet, please create one in `/etc/nginx/conf.d` directory, filename doesn't matter much as long as it ends with `.conf` extension, in that file whether you've just created it or you're editing an existing one please ensure it matches the following, replacing all `<placeholder>` values with your corresponding values:

    ```nginx
    server {
        listen       80;        # Disable this on Production server for security
        listen       [::]:80;   # Disable this too on Production
        listen       443 ssl;
        listen       [::]:443 ssl;
        server_name  <your_domain>;
        ssl_certificate /etc/nginx/ssl/certificate.pem;
        ssl_certificate_key /etc/nginx/ssl/key.pem;
        ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
        ssl_session_tickets off;
        ssl_stapling off;
        ssl_stapling_verify off;
        index index.php;
        root <aboslute_path_to_project>/public;
    
        location / {
            try_files $uri $uri/ /index.php$is_args$args;
        }
    
        location /app/<app_key_in_env_file> {
            proxy_pass http://127.0.0.1:6001;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header Host $host;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
            proxy_http_version 1.1;
            proxy_set_header Upgrade "websocket";
            proxy_set_header Connection "upgrade";
            proxy_read_timeout 30000s;
            proxy_send_timeout 30000s;
            proxy_redirect off;
            proxy_connect_timeout 30000s;
        }
    
        # Replace this with your php-fpm configuration block or correct configuration file.
        include common/php-fpm.conf;
    
        location ~ \.env$ {
            deny all;
        }
    }
    ```

    !!! info "Notice"

        If you have changed the default websocket port with `LARAVEL_WEBSOCKETS_PORT` variable in the `.env` file, please replace the port `6001` in the nginx configuration above with the one you specified in the `.env` file accordingly.

    !!! warning "IMPORTANT"

        After saving the file you'll need to run the following command in terminal to use the new configuration.

        ```shell
        $ nginx -s reload
        ```

=== "Apache"

    You'll need to add the following `VirtualHost` block to your default Apache configuration or, edit the one that corresponds with your server's domain name if you're on a server, replacing all `<placeholder>` values with your corresponding values, the file can be created or edited if exists in `/etc/httpd/conf.d` directory:

    ```apache
    <VirtualHost *:80>      # Remove this block on Production server for security.
        ServerName <your_domain_name>
        DocumentRoot <aboslute_path_to_project>/public
    
        <Location "/app/<app_key_in_env_file>">
            ProxyRequests on
            RequestHeader set X-Forwarded-Proto "http"
            ProxyPass / https://127.0.0.1:6001/
            ProxyPassReverse / https://127.0.0.1:6001/
            
            RewriteEngine on
            RewriteCond %{HTTP:UPGRADE} ^WebSocket$ [NC]
            RewriteCond %{HTTP:CONNECTION} ^Upgrade$ [NC]
            RewriteRule .* ws://127.0.0.1:6001%{REQUEST_URI} [P]
        </Location>
    </VirtualHost>

    <VirtualHost *:443>
        ServerName <your_domain_name>
        DocumentRoot <aboslute_path_to_project>/public
        SSLEngine on
        SSLCertificateFile /etc/apache2/ssl/certificate.pem
        SSLCertificateKeyFile /etc/apache2/ssl/key.pem
    
        <Location "/app/<app_key_in_env_file>">
            ProxyRequests on
            RequestHeader set X-Forwarded-Proto "http"
            ProxyPass / https://127.0.0.1:6001/
            ProxyPassReverse / https://127.0.0.1:6001/
            
            RewriteEngine on
            RewriteCond %{HTTP:UPGRADE} ^WebSocket$ [NC]
            RewriteCond %{HTTP:CONNECTION} ^Upgrade$ [NC]
            RewriteRule .* ws://127.0.0.1:6001%{REQUEST_URI} [P]
        </Location>
    </VirtualHost>
    ```

    !!! info "Notice"

        If you have changed the default websocket port with `LARAVEL_WEBSOCKETS_PORT` variable in the `.env` file, please replace the port `6001` in the apache configuration above with the one you specified in the `.env` file accordingly.

    !!! warning "IMPORTANT"

        After saving the file you'll need to run the following command in terminal to use the new configuration.

        ```shell
        $ systemctl restart httpd
        ```
    
        If that didn't work due to a `command not found` error, you can write:
    
        ```shell
        $ service httpd restart
        ```
