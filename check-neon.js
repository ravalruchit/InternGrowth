const { Client } = require('pg');

const client = new Client({
    connectionString: "postgresql://neondb_owner:npg_hsV6Jqdc8HnR@ep-purple-meadow-adr5e2fa.c-2.us-east-1.aws.neon.tech/neondb?sslmode=require"
});

async function run() {
    try {
        await client.connect();
        const res = await client.query('SELECT COUNT(*) FROM users');
        console.log('Neon users count:', res.rows[0].count);
        
        const tables = [
            'users',
            'student_profiles',
            'startup_profiles',
            'tasks',
            'applications',
            'submissions'
        ];
        
        for (const table of tables) {
            const countRes = await client.query(`SELECT COUNT(*) FROM "${table}"`);
            console.log(`- ${table}: ${countRes.rows[0].count} rows`);
        }
    } catch (err) {
        console.error('Error connecting to Neon:', err.message);
    } finally {
        await client.end();
    }
}

run();
