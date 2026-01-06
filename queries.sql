-- Запрос на добавление существующих списков категорий
INSERT INTO categories (name, modifier)
VALUES ('Доски и лыжи', 'boards'),
       ('Крепления', 'attachment'),
       ('Ботинки', 'boots'),
       ('Одежда', 'clothing'),
       ('Инструменты', 'tools'),
       ('Разное', 'other');

-- Запрос на добавление нескольких пользователей
INSERT INTO users (name, email, password_hash, contact_info)
VALUES ('Иван', 'ivan1@test.com', 'testpass01', 'Телефон: +7 900 000-00-01'),
       ('Пётр', 'petr2@test.com', 'testpass02', 'Телефон: +7 900 000-00-02'),
       ('Сергей', 'sergey3@test.com', 'testpass03', 'Телефон: +7 900 000-00-03'),
       ('Анна', 'anna4@test.com', 'testpass04', 'Телефон: +7 900 000-00-04'),
       ('Ольга', 'olga5@test.com', 'testpass05', 'Телефон: +7 900 000-00-05'),
       ('Дмитрий', 'dmitry6@test.com', 'testpass06', 'Телефон: +7 900 000-00-06'),
       ('Екатерина', 'ekaterina7@test.com', 'testpass07', 'Телефон: +7 900 000-00-07'),
       ('Алексей', 'alexey8@test.com', 'testpass08', 'Телефон: +7 900 000-00-08'),
       ('Мария', 'maria9@test.com', 'testpass09', 'Телефон: +7 900 000-00-09'),
       ('Николай', 'nikolay10@test.com', 'testpass10', 'Телефон: +7 900 000-00-10');

-- Запрос на добавление существующих объявлений
INSERT INTO lots
(title, description, image_url, end_time, starting_price, bid_step, category_id, creator_id)
VALUES ('2014 Rossignol District Snowboard', 'Описание товара1', '/img/lot-1.jpg', '2025-12-02 00:00:00', 10999, 100, 1,
        1),
       ('DC Ply Mens 2016/2017 Snowboard', 'Описание товара2', '/img/lot-2.jpg', '2025-12-03 00:00:00', 159999, 100, 1,
        1),
       ('Крепления Union Contact Pro 2015 года', 'Описание товара3', '/img/lot-3.jpg',
        '2025-12-04 00:00:00',
        8000, 100, 2, 1),
       ('Ботинки для сноуборда DC Mutiny Charcoal', 'Описание товара4', '/img/lot-4.jpg', '2025-11-25 00:00:00', 10999,
        100, 3, 1),
       ('Куртка для сноуборда DC Mutiny Charcoal', 'Описание товара5', '/img/lot-5.jpg', '2025-12-01 00:19:00', 7500,
        100, 4, 1),
       ('Маска Oakley Canopy', 'Описание товара6', '/img/lot-6.jpg', '2025-12-15 00:00:00', 5400, 100, 6, 1);

-- Запрос на добавление пары ставок
INSERT INTO bids (user_id, lot_id, amount)
VALUES (1, 3, 11200),
       (2, 3, 12300),
       (1, 3, 13500),
       (3, 1, 16500),
       (2, 1, 17000);

-- Запрос на получение всех категорий
SELECT *
FROM categories;

-- Запрос на получение самых новых, открытых лотов. Максимум - 10 штук
SELECT
  l.title,
  l.starting_price,
  l.image_url,
  c.name AS category_name,
  COALESCE(MAX(b.amount), l.starting_price) AS price
FROM lots l
       JOIN categories c ON c.id = l.category_id
       LEFT JOIN bids b ON b.lot_id = l.id
WHERE l.end_time > NOW()
GROUP BY l.id
ORDER BY l.start_time DESC
  LIMIT 10;

-- Запрос для получения лота по его ID + название категории. Пример указан для ID = 1
SELECT
  l.*,
  c.name AS category_name
FROM lots l
       JOIN categories c ON c.id = l.category_id
WHERE l.id = 1;

-- Запрос для обновление названия лота по его ID. Пример указан для ID = 3 (добавили размер L/XL)
UPDATE lots
SET title = 'Крепления Union Contact Pro 2015 года размер L/XL'
WHERE id = 3;

-- Запрос для получения списка ставок для лота по его ID. Пример указан для ID = 3
SELECT
  b.id,
  b.amount,
  b.created_at,
  u.name AS user_name
FROM bids b
       JOIN users u ON u.id = b.user_id
WHERE b.lot_id = 3
ORDER BY b.created_at DESC;
