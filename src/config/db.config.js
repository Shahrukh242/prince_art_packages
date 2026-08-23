// TEMPORARY fallback DB config for the free MySQL host.
// The app prefers process.env.* (set in Vercel dashboard / vercel.json), but
// when the platform fails to inject them, this guarantees a connection so the
// admin login works. Remove this file once the site moves to real hosting with
// proper environment variables.
module.exports = {
  DB_HOST: 'sql12.freesqldatabase.com',
  DB_PORT: 3306,
  DB_USER: 'sql12835813',
  DB_PASSWORD: 'cBYPlgVUqa',
  DB_NAME: 'sql12835813'
};
