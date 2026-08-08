# Quick Start Guide - LaunchShop Template Updates

## 🚀 Quick Implementation Steps

### Step 1: Update Database for Existing Users (Task 1)
```bash
# Navigate to project directory
cd d:\xamp\htdocs\luanchshop\nooryak_launchshop

# Run the SQL script to update currency to INR
mysql -u root -p nooryak_launchshopp < update_currency_to_inr.sql
```

**What this does:** Updates all existing template users to use INR (₹) as default currency.

---

### Step 2: Clear Cache
```bash
# Clear application cache
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

**Why:** Ensures Laravel picks up the new changes.

---

### Step 3: Test the Changes

#### ✅ Task 1: INR Currency
1. Login to admin panel
2. Create a new user or check existing user
3. Verify currency shows as INR (₹)

#### ✅ Task 2: Category Slider
1. Open any template homepage (currently working in Grocery template)
2. Look for the sliding categories below the navigation
3. Hover over categories to see them pause
4. Check on desktop (>1200px width) - should be visible
5. Check on mobile (<1200px) - should be hidden

#### ✅ Task 3: Centered Layout (>1600px)
1. Open browser in full screen (or set to 1920px width)
2. Verify content is centered with white space on sides
3. Check that header, content, and footer are all centered
4. Verify backgrounds extend full width

---

## 📁 Files Changed

### Modified:
- ✏️ `app/Http/Controllers/Admin/RegisterUserController.php`
- ✏️ `app/Http/Controllers/Front/CheckoutController.php`
- ✏️ `app/Http/Controllers/UserFront/CheckoutController.php`
- ✏️ `resources/views/user-front/grocery/partials/header.blade.php`
- ✏️ `resources/views/user-front/styles.blade.php`
- ✏️ `resources/views/user-front/scripts.blade.php`

### Created:
- ➕ 10 custom-styles.css files (one for each template)
- ➕ `update_currency_to_inr.sql`
- ➕ `IMPLEMENTATION_NOTES.md`
- ➕ `QUICK_START_GUIDE.md`

---

## 🎨 Customization Options

### Change Slider Speed
Edit any `custom-styles.css` file:
```css
@keyframes slideCategories {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}
/* Change 30s to your desired speed */
animation: slideCategories 30s linear infinite;
```

### Change Category Slider Colors
```css
.category-nav-item a {
  background: #fff; /* Background color */
  color: #333; /* Text color */
}

.category-nav-item a:hover {
  background: var(--color-primary, #10b981); /* Hover background */
  color: #fff; /* Hover text color */
}
```

### Change Max Width for Centered Layout
```css
@media (min-width: 1600px) {
  .container {
    max-width: 1600px; /* Change this value */
  }
}
```

---

## 🔍 Troubleshooting

### Category Slider Not Showing?
1. Check browser console for errors (F12)
2. Verify custom-styles.css is loaded (Network tab)
3. Check screen width is > 1199px
4. Clear browser cache (Ctrl+Shift+R)

### Currency Still Showing USD?
1. Verify SQL script ran successfully
2. Clear Laravel cache: `php artisan cache:clear`
3. Check database: `SELECT * FROM user_currencies WHERE is_default = 1;`

### Layout Not Centered on Large Screens?
1. Verify screen width is > 1600px
2. Check custom-styles.css is loaded
3. Inspect element to verify max-width is applied
4. Clear browser cache

### Slider Not Animating?
1. Check jQuery is loaded before custom script
2. Verify JavaScript has no errors (Console)
3. Check categories exist in database
4. Try disabling browser extensions

---

## 📱 Responsive Breakpoints

- **Mobile**: < 768px - Slider hidden
- **Tablet**: 768px - 1199px - Slider hidden
- **Desktop**: 1200px - 1599px - Slider visible, full width
- **Large Desktop**: ≥ 1600px - Slider visible, centered with max-width

---

## 🌐 Browser Testing Checklist

- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)
- [ ] Mobile Safari (iOS)
- [ ] Chrome Mobile (Android)

---

## 📞 Need Help?

Common issues and solutions:

**Q: Categories not sliding smoothly?**
A: Reduce animation duration from 30s to 20s for faster movement.

**Q: Want to add category images to slider?**
A: Modify the header.blade.php to include `<img>` tags inside category links.

**Q: How to change currency back to USD?**
A: Run: `UPDATE user_currencies SET text = 'USD', symbol = '$' WHERE is_default = 1;`

**Q: Slider showing duplicate categories?**
A: This is intentional for seamless infinite loop - leave as is.

---

## ✅ Deployment Checklist

Before pushing to production:

- [ ] Run SQL script to update currency
- [ ] Test on all template types
- [ ] Clear all caches
- [ ] Test on multiple browsers
- [ ] Test on mobile devices
- [ ] Verify large screen layout (>1600px)
- [ ] Check console for JavaScript errors
- [ ] Verify CSS loads correctly
- [ ] Test category slider on all templates
- [ ] Backup database before changes

---

**Last Updated**: July 3, 2026
**Status**: ✅ Ready for Production
