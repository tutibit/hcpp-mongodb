# MongoDB Admin Plugin for HestiaCP

MongoDB administration interface that integrates with HestiaCP alongside phpMyAdmin and phpPgAdmin.

## Prerequisites

⚠️ **IMPORTANT**: This plugin does NOT install MongoDB. You must install MongoDB manually first.

### 1. Install MongoDB 8.0 (Latest Version)

**Step 1: Update System**
```bash
sudo apt update && sudo apt upgrade -y
```

**Step 2: Import MongoDB GPG Key**
```bash
curl -fsSL https://www.mongodb.org/static/pgp/server-8.0.asc | \
   sudo gpg -o /usr/share/keyrings/mongodb-server-8.0.gpg \
   --dearmor
```

**Step 3: Add MongoDB Repository**
Choose the command for your Ubuntu version:

**Ubuntu 24.04 (Noble):**
```bash
echo "deb [ arch=amd64,arm64 signed-by=/usr/share/keyrings/mongodb-server-8.0.gpg ] https://repo.mongodb.org/apt/ubuntu noble/mongodb-org/8.0 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-8.0.list
```

**Ubuntu 22.04 (Jammy):**
```bash
echo "deb [ arch=amd64,arm64 signed-by=/usr/share/keyrings/mongodb-server-8.0.gpg ] https://repo.mongodb.org/apt/ubuntu jammy/mongodb-org/8.0 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-8.0.list
```

**Ubuntu 20.04 (Focal):**
```bash
echo "deb [ arch=amd64,arm64 signed-by=/usr/share/keyrings/mongodb-server-8.0.gpg ] https://repo.mongodb.org/apt/ubuntu focal/mongodb-org/8.0 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-8.0.list
```

**Step 4: Install MongoDB**
```bash
sudo apt update
sudo apt install -y mongodb-org
```

**Step 5: Start and Enable MongoDB**
```bash
sudo systemctl start mongod
sudo systemctl enable mongod
```

**Step 6: Verify Installation**
```bash
sudo systemctl status mongod
mongod --version
```

### 2. Install PHP MongoDB Extension

```bash
sudo apt install php-mongodb
sudo systemctl restart php*-fpm
```

## Installation

```bash
# Method 1: From GitHub
git clone https://github.com/your-repo/MongoDB-HestiaCP-Plugin.git /tmp/mongodb-plugin
sudo cp -r /tmp/mongodb-plugin/hcpp-mongodb /usr/local/hestia/plugins/
cd /usr/local/hestia/plugins/hcpp-mongodb
sudo ./install

# Method 2: Direct upload
# Upload hcpp-mongodb folder to /usr/local/hestia/plugins/
# Then: cd /usr/local/hestia/plugins/hcpp-mongodb && sudo ./install
```

## What It Does

✅ **Adds MongoDB button** next to phpMyAdmin and phpPgAdmin in HestiaCP  
✅ **Integrates Adminer** with MongoDB support  
✅ **No MongoDB installation** - connects to existing MongoDB  
✅ **Auto-detection** - only appears if MongoDB is running  

## Access

After installation, MongoDB will appear in:

1. **Database section** alongside phpMyAdmin/phpPgAdmin buttons
2. **Direct URL**: `https://your-server:8083/adminer/?mongo=`

## Connection

- **Server**: `localhost:27017`
- **Username**: _(leave empty if no auth)_
- **Password**: _(leave empty if no auth)_
- **Database**: _(select from dropdown)_

## Troubleshooting

### MongoDB button doesn't appear
```bash
# Check MongoDB is running
sudo systemctl status mongod

# Check PHP extension
php -m | grep mongodb

# Reinstall plugin
cd /usr/local/hestia/plugins/hcpp-mongodb
sudo ./uninstall && sudo ./install
```

### Can't connect to MongoDB
```bash
# Test MongoDB connection
mongosh --eval "db.runCommand('ping')"

# Check port
sudo netstat -tlnp | grep 27017
```

## Uninstall

```bash
cd /usr/local/hestia/plugins/hcpp-mongodb
sudo ./uninstall
```

Note: This only removes the plugin, NOT MongoDB itself.