# Step 2 — DNS Configuration for thambucomputers.com

## Where to Configure
Log in to your **domain registrar** (GoDaddy / Hostinger / Namecheap / BigRock / etc.)
and navigate to: **DNS Management → Manage DNS Records**

---

## Required DNS Records

| Type  | Name         | Value              | TTL  |
|-------|--------------|--------------------|----- |
| A     | @            | YOUR_SERVER_IP     | 3600 |
| A     | www          | YOUR_SERVER_IP     | 3600 |
| A     | admin        | YOUR_SERVER_IP     | 3600 |
| A     | dealer       | YOUR_SERVER_IP     | 3600 |
| A     | customer     | YOUR_SERVER_IP     | 3600 |
| A     | technician   | YOUR_SERVER_IP     | 3600 |
| A     | delivery     | YOUR_SERVER_IP     | 3600 |
| MX    | @            | mail.thambucomputers.com | 3600 |
| TXT   | @            | v=spf1 include:_spf.google.com ~all | 3600 |

> **Replace `YOUR_SERVER_IP`** with the actual IPv4 of your VPS/server.

---

## Verifying DNS Propagation

DNS changes take 5 minutes – 48 hours. Check propagation with:

```bash
nslookup admin.thambucomputers.com
nslookup dealer.thambucomputers.com
nslookup customer.thambucomputers.com

# or use online tool:
# https://www.whatsmydns.net/#A/admin.thambucomputers.com
```

---

## For cPanel Hosting (Hostinger / BigRock)

1. cPanel → **Subdomains** section
2. Create:
   - Subdomain: `admin` → Document Root: `public_html/public`
   - Subdomain: `dealer` → Document Root: `public_html/public`
   - Subdomain: `customer` → Document Root: `public_html/public`
3. Point all to same Laravel `/public` folder

---

## Nameserver Configuration (if using Cloudflare CDN)

1. Go to [cloudflare.com](https://cloudflare.com) → Add Site → `thambucomputers.com`
2. Update nameservers at your registrar to Cloudflare's nameservers
3. In Cloudflare DNS panel, add all A records above
4. Enable **Orange Cloud (proxied)** for DDoS protection + free SSL

> Tip: Cloudflare gives you **free SSL + DDoS protection + CDN** — highly recommended for production.
