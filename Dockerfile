# Sử dụng PHP Apache
FROM php:8.2-apache

# Cài driver kết nối Database
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Thiết lập thư mục gốc của Web
WORKDIR /var/www/html

# 1. Copy tất cả file trong thư mục frontend ra ngoài cùng
COPY frontend/ .

# 2. Copy tất cả file trong thư mục backend ra ngoài cùng
# (Để api.php nằm cạnh index.html -> Fix lỗi kết nối)
COPY backend/ .

# 3. Copy các file cấu hình database (nếu nằm ở ngoài folder backend)
COPY *.php ./

# 4. Cấp quyền cho Apache (Fix lỗi 403 Forbidden)
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

EXPOSE 80