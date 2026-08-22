const bcrypt = require('bcryptjs');
const db = require('./db');

async function seedAdminUser() {
  console.log('[Seed] Checking default admin user credentials...');

  const defaultEmail = 'admin@princeartpackages.com';
  const defaultPassword = 'AdminPassword2026!';
  const defaultName = 'System Administrator';

  try {
    const existing = await db.query('SELECT id, email, role FROM users WHERE email = ?', [defaultEmail]);

    if (existing.length === 0) {
      const salt = await bcrypt.genSalt(10);
      const passwordHash = await bcrypt.hash(defaultPassword, salt);

      await db.query(
        'INSERT INTO users (name, email, password_hash, role, status) VALUES (?, ?, ?, ?, ?)',
        [defaultName, defaultEmail, passwordHash, 'admin', 'active']
      );
      console.log(`   ✓ Created default admin user: ${defaultEmail}`);
    } else {
      // Ensure password hash is updated to defaultPassword for easy login
      const salt = await bcrypt.genSalt(10);
      const passwordHash = await bcrypt.hash(defaultPassword, salt);
      await db.query('UPDATE users SET password_hash = ?, role = ? WHERE email = ?', [passwordHash, 'admin', defaultEmail]);
      console.log(`   ✓ Reset password for admin user: ${defaultEmail}`);
    }

    console.log('\n====================================================');
    console.log('         DEFAULT ADMIN LOGIN CREDENTIALS            ');
    console.log('====================================================');
    console.log(`Email:    ${defaultEmail}`);
    console.log(`Password: ${defaultPassword}`);
    console.log('====================================================\n');
  } catch (err) {
    console.error('[Seed Error] Failed to seed admin user:', err.message);
  }
}

if (require.main === module) {
  seedAdminUser().then(() => process.exit(0)).catch(() => process.exit(1));
}

module.exports = seedAdminUser;
