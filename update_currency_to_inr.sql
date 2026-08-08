-- Update Currency to INR for All Template Users
-- This script updates the default currency for all existing template users to INR

-- Update user_currencies table to set all default currencies to INR
UPDATE user_currencies 
SET 
    text = 'INR',
    symbol = '₹',
    value = 1,
    symbol_position = 'left',
    text_position = 'left'
WHERE is_default = 1;

-- If there are no default currencies, insert INR as default for each user
INSERT INTO user_currencies (user_id, text, symbol, value, is_default, symbol_position, text_position, created_at, updated_at)
SELECT 
    u.id,
    'INR',
    '₹',
    1,
    1,
    'left',
    'left',
    NOW(),
    NOW()
FROM users u
WHERE NOT EXISTS (
    SELECT 1 
    FROM user_currencies uc 
    WHERE uc.user_id = u.id AND uc.is_default = 1
);

-- Update basic_settings table if it exists (admin default currency)
UPDATE basic_settings 
SET 
    base_currency_text = 'INR',
    base_currency_symbol = '₹'
WHERE id = 1;

-- Update basic_extendeds table if it exists
UPDATE basic_extendeds 
SET 
    base_currency_text = 'INR',
    base_currency_symbol = '₹';

-- Display confirmation
SELECT 'Currency update completed successfully!' as status;
SELECT COUNT(*) as total_users_with_inr 
FROM user_currencies 
WHERE text = 'INR' AND is_default = 1;
