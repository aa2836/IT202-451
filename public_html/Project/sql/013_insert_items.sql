INSERT INTO Products (id, name, description, category, stock, cost, visibility, image) VALUES
(1, "Inter Milan Jersey", "Official Inter Milan soccer jersey", "Jerseys", 20, 52, "True", "https://example.com/inter_milan_jersey.png"),
(2, "Atletico Madrid Soccer Ball", "Official Atletico Madrid soccer ball", "Balls", 35, 28, "True", "https://example.com/atletico_soccer_ball.png"),
(3, "Tottenham Hotspur Scarf", "Scarfs for Tottenham fans", "Scarfs", 100, 22, "True", "https://example.com/tottenham_scarf.png"),
(4, "Borussia Dortmund Mug", "Borussia Dortmund coffee mug", "Mugs", 200, 18, "True", "https://example.com/dortmund_mug.png"),
(5, "AS Roma Cap", "AS Roma baseball cap", "Caps", 500, 28, "True", "https://example.com/as_roma_cap.png"),
(6, "Sevilla FC Water Bottle", "Sevilla FC water bottle", "Bottles", 75, 15, "True", "https://example.com/sevilla_water_bottle.png"),
(7, "Benfica Soccer Shorts", "SL Benfica soccer shorts", "Shorts", 25, 42, "True", "https://example.com/benfica_soccer_shorts.png"),
(8, "Porto FC Socks", "Official Porto FC soccer socks", "Socks", 80, 10, "True", "https://example.com/porto_socks.png"),
(9, "Ajax FC Jacket", "Ajax FC windbreaker jacket", "Jackets", 30, 65, "True", "https://example.com/ajax_jacket.png"),
(10, "Lyon Soccer Cleats", "Official Olympique Lyonnais soccer cleats", "Cleats", 60, 85, "True", "https://example.com/lyon_soccer_cleats.png")
ON DUPLICATE KEY UPDATE modified = CURRENT_TIMESTAMP();
