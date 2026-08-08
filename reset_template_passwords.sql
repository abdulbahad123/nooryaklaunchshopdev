-- ============================================
-- Reset Template Passwords Script
-- ============================================
-- This will:
-- 1. Set password to: password123
-- 2. Verify email (email_verified = 1)
-- 3. Activate account (status = 1)
-- 4. Make online (online_status = 1)
-- ============================================

-- Password hash for 'password123': $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi

UPDATE `users` 
SET 
    `password` = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    `email_verified` = 1,
    `status` = 1,
    `online_status` = 1
WHERE `preview_template` = 1;

-- Now you can login to any template with:
-- Password: password123

-- Individual template logins:
-- 1. Email: manti@example.com | Password: password123
-- 2. Email: grocery@example.com | Password: password123
-- 3. Email: electi@example.com | Password: password123
-- 4. Email: kidsfa@example.com | Password: password123
-- 5. Email: furial@example.com | Password: password123
-- 6. Email: fashclo@example.com | Password: password123
-- 7. Email: parashop@gmail.com | Password: password123
-- 8. Email: skinflow@gmail.com | Password: password123
-- 9. Email: jewelleryshop@gmail.com | Password: password123
-- 10. Email: demo.clothing@launchshop.test | Password: password123
