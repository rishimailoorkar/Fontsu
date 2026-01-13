<!DOCTYPE html>
<html>
<head>
    <title>Create Users Table - Supabase SQL</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 8px; max-width: 900px; margin: 0 auto; }
        h1 { color: #007F8C; }
        pre { background: #1e1e1e; color: #d4d4d4; padding: 20px; border-radius: 4px; overflow-x: auto; }
        .instructions { background: #e3f2fd; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        button { background: #007F8C; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; }
        button:hover { background: #006670; }
        .success { color: #28a745; margin-top: 10px; display: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create Users Table in Supabase</h1>
        
        <div class="instructions">
            <strong>Instructions:</strong>
            <ol>
                <li>Go to your Supabase Dashboard</li>
                <li>Navigate to <strong>SQL Editor</strong></li>
                <li>Click <strong>New Query</strong></li>
                <li>Copy the SQL below and paste it</li>
                <li>Click <strong>Run</strong></li>
            </ol>
        </div>

        <button onclick="copySQL()">📋 Copy SQL to Clipboard</button>
        <div class="success" id="success">✓ Copied to clipboard!</div>

        <pre id="sqlCode">-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'buyer',
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Create index on email for faster lookups
CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);
CREATE INDEX IF NOT EXISTS idx_users_phone ON users(phone);

-- Enable Row Level Security
ALTER TABLE users ENABLE ROW LEVEL SECURITY;

-- Drop existing policies if they exist
DROP POLICY IF EXISTS "Allow public signup" ON users;
DROP POLICY IF EXISTS "Users can read own data" ON users;
DROP POLICY IF EXISTS "Users can update own data" ON users;
DROP POLICY IF EXISTS "Service role bypass" ON users;

-- Create policy to allow service role full access (for PHP backend)
CREATE POLICY "Service role bypass" ON users
    AS PERMISSIVE
    FOR ALL
    TO service_role
    USING (true)
    WITH CHECK (true);

-- Create policy to allow anyone to insert (signup)
CREATE POLICY "Allow public signup" ON users
    AS PERMISSIVE
    FOR INSERT
    TO anon, authenticated
    WITH CHECK (true);

-- Create policy to allow users to read all data
CREATE POLICY "Users can read data" ON users
    AS PERMISSIVE
    FOR SELECT
    TO anon, authenticated
    USING (true);</pre>
    </div>

    <script>
        function copySQL() {
            const sqlCode = document.getElementById('sqlCode').textContent;
            navigator.clipboard.writeText(sqlCode).then(() => {
                const success = document.getElementById('success');
                success.style.display = 'block';
                setTimeout(() => {
                    success.style.display = 'none';
                }, 3000);
            });
        }
    </script>
</body>
</html>
