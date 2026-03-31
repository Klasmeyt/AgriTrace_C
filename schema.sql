-- Users table
CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  first_name VARCHAR(100),
  last_name VARCHAR(100),
  email VARCHAR(255) UNIQUE NOT NULL,
  role VARCHAR(50) DEFAULT 'Farmer',
  status VARCHAR(20) DEFAULT 'Active',
  mobile VARCHAR(20),
  farmer_id VARCHAR(50),
  barangay VARCHAR(100),
  municipality VARCHAR(100),
  province VARCHAR(100),
  farm_name VARCHAR(100),
  farm_size DECIMAL(8,2),
  ownership VARCHAR(50),
  gov_id VARCHAR(50),
  department VARCHAR(100),
  position VARCHAR(100),
  office VARCHAR(100),
  region VARCHAR(50),
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW()
);

-- Farms table
CREATE TABLE farms (
  id SERIAL PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  owner VARCHAR(100),
  owner_id INTEGER REFERENCES users(id),
  type VARCHAR(50),
  size DECIMAL(8,2),
  address TEXT,
  municipality VARCHAR(100),
  region VARCHAR(50),
  lat DECIMAL(10,8),
  lng DECIMAL(11,8),
  status VARCHAR(20) DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW()
);

-- Livestock table
CREATE TABLE livestock (
  id SERIAL PRIMARY KEY,
  farm_id INTEGER REFERENCES farms(id),
  tag_id VARCHAR(50) UNIQUE,
  type VARCHAR(50),
  breed VARCHAR(100),
  age VARCHAR(20),
  qty INTEGER DEFAULT 1,
  health VARCHAR(50) DEFAULT 'Healthy',
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW()
);

-- Incidents table
CREATE TABLE incidents (
  id SERIAL PRIMARY KEY,
  ref_id VARCHAR(20) UNIQUE,
  farm_id INTEGER REFERENCES farms(id),
  type VARCHAR(100),
  description TEXT,
  status VARCHAR(50) DEFAULT 'Pending',
  priority VARCHAR(20) DEFAULT 'Medium',
  location TEXT,
  incident_date TIMESTAMP,
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW()
);

-- Public reports table
CREATE TABLE public_reports (
  id SERIAL PRIMARY KEY,
  ref_id VARCHAR(20) UNIQUE,
  type VARCHAR(100),
  description TEXT,
  contact_phone VARCHAR(20),
  contact_email VARCHAR(255),
  status VARCHAR(50) DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW()
);

-- Audit log table
CREATE TABLE audit_log (
  id SERIAL PRIMARY KEY,
  user_email VARCHAR(255),
  action VARCHAR(50),
  record_type VARCHAR(50),
  description TEXT,
  ip_address INET,
  status VARCHAR(20),
  created_at TIMESTAMP DEFAULT NOW()
);

-- Enable RLS (Row Level Security)
ALTER TABLE users ENABLE ROW LEVEL SECURITY;
ALTER TABLE farms ENABLE ROW LEVEL SECURITY;
ALTER TABLE livestock ENABLE ROW LEVEL SECURITY;
ALTER TABLE incidents ENABLE ROW LEVEL SECURITY;
ALTER TABLE public_reports ENABLE ROW LEVEL SECURITY;
ALTER TABLE audit_log ENABLE ROW LEVEL SECURITY;

-- Create indexes
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_farms_owner_id ON farms(owner_id);
CREATE INDEX idx_farms_status ON farms(status);
CREATE INDEX idx_livestock_farm_id ON livestock(farm_id);
CREATE INDEX idx_incidents_farm_id ON incidents(farm_id);
CREATE INDEX idx_incidents_status ON incidents(status); 