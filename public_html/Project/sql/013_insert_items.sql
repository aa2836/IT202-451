INSERT INTO Products (id, name, description, category, stock, unit_price) VALUES
(1, "Inter Milan Jersey", "Official Inter Milan soccer jersey", "Jerseys", 20, 79.99),
(2, "Atletico Madrid Soccer Ball", "Official Atletico Madrid soccer ball", "Balls", 35, 49.99),
(3, "Tottenham Hotspur Scarf", "Scarfs for Tottenham fans", "Scarfs", 100, 24.99),
(4, "Borussia Dortmund Mug", "Borussia Dortmund coffee mug", "Mugs", 200, 14.99),
(5, "AS Roma Cap", "AS Roma baseball cap", "Caps", 500, 19.99),
(6, "Sevilla FC Water Bottle", "Sevilla FC water bottle", "Bottles", 75, 9.99),
(7, "Benfica Soccer Shorts", "SL Benfica soccer shorts", "Shorts", 25, 39.99),
(8, "Porto FC Socks", "Official Porto FC soccer socks", "Socks", 80, 12.99),
(9, "Ajax FC Jacket", "Ajax FC windbreaker jacket", "Jackets", 30, 99.99),
(10, "Lyon Soccer Cleats", "Official Olympique Lyonnais soccer cleats", "Cleats", 60, 159.99)
ON DUPLICATE KEY UPDATE modified = CURRENT_TIMESTAMP, name=VALUES(name), description=VALUES(description), category=VALUES(category), stock=VALUES(stock), unit_price=VALUES(unit_price);
