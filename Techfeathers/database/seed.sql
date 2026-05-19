-- seed.sql
-- Initial seed data for Poultry Management System

INSERT INTO users (email, password, name, role) VALUES ('admin@poultry.local', '<PASSWORD_HASH_PLACEHOLDER>', 'Admin User', 'admin');

INSERT INTO batches (batch_code, breed, started_at, quantity, status) VALUES
  ('B-201', 'Leghorn', date('now', '-7 days'), 120, 'active'),
  ('B-202', 'Rhode Island Red', date('now', '-10 days'), 90, 'active');

-- Seed egg production for the last 7 days (example)
-- Insert using application logic, or run a small script to populate realistic data.

INSERT INTO feed_inventory (name, quantity_kg, unit_cost, last_updated) VALUES
  ('Layer Mash', 250.0, 25.0, date('now')),
  ('Grower Feed', 150.0, 22.5, date('now'));

INSERT INTO finances (type, description, amount, incurred_at) VALUES
  ('sale', 'Egg sales', 420.00, date('now','-3 days')),
  ('expense', 'Feed purchase', 190.00, date('now','-2 days'));
