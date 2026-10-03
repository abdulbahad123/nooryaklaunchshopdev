USE `nooryak_ps_youversein_launchshop`;

SELECT `id`, `sku`, `thumbnail`
FROM `user_items`
WHERE `id` = 16
  AND `sku` = '7707135';

START TRANSACTION;

INSERT INTO `user_item_images` (`item_id`, `image`, `created_at`, `updated_at`)
SELECT product.`id`, image.`image`, NOW(), NOW()
FROM `user_items` AS product
CROSS JOIN (
    SELECT '80691e9c847c14415e7d48960a87858cc412c78c.png' AS `image`
    UNION ALL SELECT 'd390534a404426d8b5757c0d3aea8eaf9c0080e1.png'
    UNION ALL SELECT '9a5e582d6956bf6dd4d7bafa1966ecacd7d57702.png'
    UNION ALL SELECT 'cb126b70706122025ac713c251d461abdaa1d836.png'
    UNION ALL SELECT 'c5b52d001adabfa2f39da1d751bbe3c4ef41cf91.png'
) AS image
WHERE product.`id` = 16
  AND product.`sku` = '7707135'
  AND NOT EXISTS (
      SELECT 1
      FROM `user_item_images` AS existing
      WHERE existing.`item_id` = product.`id`
        AND existing.`image` = image.`image`
  );

SELECT `item_id`, `image`
FROM `user_item_images`
WHERE `item_id` = 16
ORDER BY `id`;

COMMIT;
