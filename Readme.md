# Nmap XML → MySQL Reporting Web App

A Linux-based project that automates the ingestion of **Nmap XML scan results** into a **MySQL database** and provides a lightweight **Apache/PHP dashboard** for real-time reporting of hosts and open ports.  
It uses **cron** to schedule scans and keep the inventory continuously refreshed, tested on networks with **50+ devices** and **200+ open ports per scan**.

---

## ✨ Features
- Parse **Nmap XML output** to extract:
  - IP address
  - MAC address & vendor
  - Hostname
  - Open TCP ports
- Store structured results in **MySQL** for historical queries.
- Generate a **web-based report** via Apache/PHP with sub-second page loads.
- Automate scans with **cron** (e.g., hourly) to maintain an up-to-date network view.
- Demonstrates applied knowledge of **Linux, network I/O, and protocols (Ethernet/IP/TCP/UDP)**.

---

## 🛠️ Tech Stack
- **Linux**
- **Apache2** + **PHP**
- **MySQL**
- **Nmap** (XML output)
- **Cron**
- **Git**

---

## 🚀 Setup

### 1. Install Dependencies
```bash
# Linux (Debian/Ubuntu)
sudo apt-get update
sudo apt-get install apache2 php libapache2-mod-php mysql-server nmap
2. Configure MySQL
sql
Copy code
create database nmap;
use nmap;

create table log(
    log_id int auto_increment primary key,
    ip text,
    mac text,
    vendor text,
    hostname text,
    ports text,
    timestamp text
);

create user nmap_user identified by '123456';
grant all privileges on nmap.* to nmap_user;
3. Place Files
Copy nmapParse.php into /var/www/html/

Ensure permissions for web access:

bash
Copy code
sudo chown $USER /var/www/html
sudo chmod 755 /var/www/html
4. Run Nmap & Parse
bash
Copy code
# Run scan and save XML
nmap -oX /var/www/html/nmapTest.xml 192.168.1.0/24

# Parse XML and insert into MySQL
php /var/www/html/nmapParse.php
5. Automate with Cron
bash
Copy code
crontab -e
# Example: run every hour
0 * * * * nmap -oX /var/www/html/nmapTest.xml 192.168.1.0/24 && php /var/www/html/nmapParse.php
