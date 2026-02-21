# 🔐 SECURITY HARDENING - IMPLEMENTATION GUIDE

## ⚠️ CRITICAL: Execute These Steps IMMEDIATELY

### STEP 1: Secure Database Password

**Current Risk**: Password `M4rufmuchlisin` is weak and potentially exposed.

**Action Required**:

```bash
# 1. Generate strong password
NEW_DB_PASSWORD=$(openssl rand -base64 32)

# 2. Change MySQL password
mysql -u root -p  # Enter current password: M4rufmuchlisin
```

**In MySQL console**:

```sql
ALTER USER 'root'@'localhost' IDENTIFIED BY 'YOUR_NEW_STRONG_PASSWORD';
FLUSH PRIVILEGES;
EXIT;
```

**3. Update .env file**:

```bash
# Edit .env
nano /home/suntree/Videos/kpum/.env

# Change line 29:
DB_PASSWORD=YOUR_NEW_STRONG_PASSWORD
```

**4. Test connection**:

```bash
cd /home/suntree/Videos/kpum
php artisan config:clear
php artisan migrate:status  # Should connect successfully
```

---

### STEP 2: Enable Redis Authentication

**Current Risk**: Redis accessible without password (REDIS_PASSWORD=null)

**Action Required**:

```bash
# 1. Generate Redis password
NEW_REDIS_PASSWORD=$(openssl rand -base64 32)

# 2. Edit Redis config
sudo nano /etc/redis/redis.conf

# Find and uncomment/add this line:
requirepass YOUR_REDIS_PASSWORD_HERE

# Also add (for extra security):
bind 127.0.0.1 ::1
protected-mode yes

# 3. Restart Redis
sudo systemctl restart redis
sudo systemctl status redis  # Verify it's running

# 4. Test Redis auth
redis-cli
> AUTH YOUR_REDIS_PASSWORD_HERE
> PING  # Should return PONG

# 5. Update .env
nano /home/suntree/Videos/kpum/.env

# Change line 48:
REDIS_PASSWORD=YOUR_REDIS_PASSWORD_HERE

# 6. Clear cache
php artisan config:clear
php artisan cache:clear
php artisan queue:restart
```

---

### STEP 3: Lock Down Database External Access

**Action Required**:

```bash
# 1. Check if MySQL is exposed to internet
sudo netstat -tulpn | grep 3306

# 2. Configure MySQL to bind only to localhost
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf

# Find and set:
bind-address = 127.0.0.1

# 3. Restart MySQL
sudo systemctl restart mysql

# 4. Verify
sudo netstat -tulpn | grep 3306
# Should show: 127.0.0.1:3306 (not 0.0.0.0:3306)
```

---

### STEP 4: Firewall Configuration

```bash
# Enable UFW firewall
sudo ufw enable

# Allow only necessary ports
sudo ufw allow 22     # SSH
sudo ufw allow 80     # HTTP
sudo ufw allow 443    # HTTPS

# Block database ports from external access
sudo ufw deny 3306    # MySQL
sudo ufw deny 6379    # Redis

# Check status
sudo ufw status verbose
```

---

## ✅ VERIFICATION CHECKLIST

After completing above steps, verify:

- [ ] MySQL password changed and connection works
- [ ] Redis requires authentication
- [ ] MySQL binds only to 127.0.0.1
- [ ] Redis binds only to 127.0.0.1
- [ ] Firewall blocks external DB access
- [ ] Application still works (test login, voting)
- [ ] Queue worker can connect to Redis

**Estimated Time**: 15-20 minutes
**Requires**: Root/sudo access

---

**Next Steps**: After securing infrastructure, proceed to implement Authorization Policies (see files being created now).
