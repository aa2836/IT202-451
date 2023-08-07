INSERT INTO Products (name, description, category, stock, cost, image) VALUES
    ("Inter Milan Jersey", "Official Inter Milan soccer jersey", "Jerseys", 20, 79, ""),
    ("Atletico Madrid Soccer Ball", "Official Atletico Madrid soccer ball", "Balls", 35, 49, ""),
    ("Tottenham Hotspur Scarf", "Scarfs for Tottenham fans", "Scarfs", 100, 24, ""),
    ("Borussia Dortmund Mug", "Borussia Dortmund coffee mug", "Mugs", 200, 14, ""),
    ("AS Roma Cap", "AS Roma baseball cap", "Caps", 500, 19, ""),
    ("Sevilla FC Water Bottle", "Sevilla FC water bottle", "Bottles", 75, 9, ""),
    ("Benfica Soccer Shorts", "SL Benfica soccer shorts", "Shorts", 25, 39, ""),
    ("Porto FC Socks", "Official Porto FC soccer socks", "Socks", 80, 12, ""),
    ("Ajax FC Jacket", "Ajax FC windbreaker jacket", "Jackets", 30, 99, ""),
    ("Lyon Soccer Cleats", "Official Olympique Lyonnais soccer cleats", "Cleats", 60, 159, "")
ON DUPLICATE KEY UPDATE modified = CURRENT_TIMESTAMP, name=VALUES(name), description=VALUES(description), category=VALUES(category), stock=VALUES(stock), cost=VALUES(cost), image=VALUES(image);