SELECT COUNT(*) AS products_to_backfill
FROM `user_items` AS product
WHERE TRIM(COALESCE(product.`thumbnail`, '')) <> ''
  AND LOWER(SUBSTRING_INDEX(TRIM(product.`thumbnail`), '/', -1)) <> 'noimage.jpg'
  AND NOT EXISTS (
      SELECT 1
      FROM `user_item_images` AS slider
      WHERE slider.`item_id` = product.`id`
        AND TRIM(COALESCE(slider.`image`, '')) <> ''
        AND LOWER(SUBSTRING_INDEX(TRIM(slider.`image`), '/', -1)) <> 'noimage.jpg'
  );

START TRANSACTION;

INSERT INTO `user_item_images` (`item_id`, `image`, `created_at`, `updated_at`)
SELECT product.`id`, product.`thumbnail`, NOW(), NOW()
FROM `user_items` AS product
WHERE TRIM(COALESCE(product.`thumbnail`, '')) <> ''
  AND LOWER(SUBSTRING_INDEX(TRIM(product.`thumbnail`), '/', -1)) <> 'noimage.jpg'
  AND NOT EXISTS (
      SELECT 1
      FROM `user_item_images` AS slider
      WHERE slider.`item_id` = product.`id`
        AND TRIM(COALESCE(slider.`image`, '')) <> ''
        AND LOWER(SUBSTRING_INDEX(TRIM(slider.`image`), '/', -1)) <> 'noimage.jpg'
  );

SELECT ROW_COUNT() AS slider_rows_added;

SELECT COUNT(*) AS products_still_without_slider
FROM `user_items` AS product
WHERE TRIM(COALESCE(product.`thumbnail`, '')) <> ''
  AND LOWER(SUBSTRING_INDEX(TRIM(product.`thumbnail`), '/', -1)) <> 'noimage.jpg'
  AND NOT EXISTS (
      SELECT 1
      FROM `user_item_images` AS slider
      WHERE slider.`item_id` = product.`id`
        AND TRIM(COALESCE(slider.`image`, '')) <> ''
        AND LOWER(SUBSTRING_INDEX(TRIM(slider.`image`), '/', -1)) <> 'noimage.jpg'
  );

COMMIT;
