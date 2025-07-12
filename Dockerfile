# 1. เลือกต้นแบบ (Image) ที่มี PHP และ Apache ติดตั้งมาให้แล้ว
FROM php:8.1-apache

# 2. คัดลอกไฟล์โปรเจคทั้งหมดเข้าไปในเว็บเซิร์ฟเวอร์
COPY . /var/www/html/

# 3. เปิดใช้งาน module ของ apache ที่จำเป็น
RUN a2enmod rewrite