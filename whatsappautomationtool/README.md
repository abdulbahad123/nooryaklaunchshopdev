# WhatsApp Automation SaaS Platform (Replix v9.0.2)

Automated WhatsApp Bulk Messaging, Chatbot, and Marketing Platform built with Laravel (Backend/Frontend) and Node.js + Baileys (WhatsApp Web Multi-Device Gateway).

---

## 📁 Repository Structure

```
├── Install/              # Laravel Web Application (Web UI, API, Modules)
├── whatsapp_server/      # Node.js + Baileys WhatsApp Gateway Service
├── replix_automation.sql # Database schema dump
└── README.md             # Documentation and deployment instructions
```

---

## 🚀 Deployment Guide on cPanel

### 1. Server Paths
- **Laravel Web Application:** `/home/nooryak/public_html/waziper.nooryak.in`
- **WhatsApp Node.js Service:** `/home/nooryak/whatsapp_server`
- **Git Repository Directory (for syncing):** `/home/nooryak/repo`

### 2. How to Pull Updates on cPanel

Whenever you push new updates to GitHub, follow these simple steps to update your cPanel server:

#### Step A: Pull the Latest Code
Connect via SSH or open the cPanel Terminal and run:
```bash
# Navigate to the repo or clone if not already cloned:
cd /home/nooryak
if [ ! -d "/home/nooryak/repo" ]; then
    git clone https://github.com/abdulbahad123/Whatsappautomation2.git repo
else
    cd /home/nooryak/repo
    git pull origin main
fi
```

#### Step B: Sync Laravel Application Files
```bash
# Sync updated Laravel files (excluding .env and storage)
rsync -av --exclude='.env' --exclude='storage' --exclude='vendor' /home/nooryak/repo/Install/ /home/nooryak/public_html/waziper.nooryak.in/

# Clear Laravel caches to apply updates:
cd /home/nooryak/public_html/waziper.nooryak.in
php artisan view:clear
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

#### Step C: Sync WhatsApp Node.js Server (if node files changed)
```bash
# Sync whatsapp_server files (excluding node_modules and sessions)
rsync -av --exclude='node_modules' --exclude='sessions' /home/nooryak/repo/whatsapp_server/ /home/nooryak/whatsapp_server/

# Restart the WhatsApp service via PM2:
pm2 restart whatsapp_server
# or
pm2 restart all
```

---

## ⚙️ Requirements & Server Configuration
- **PHP Version:** PHP 8.2 or 8.3
- **PHP Extensions:** `intl` (Required), `fileinfo`, `gd`, `zip`, `pdo_mysql`, `curl`, `mbstring`
- **Node.js:** Node v20+ with PM2
- **MySQL / MariaDB:** Strict SQL mode compatible
