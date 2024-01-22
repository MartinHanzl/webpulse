const NextAuth = require('next-auth')
const { authConfig } = require('./auth.config');
module.exports = NextAuth(authConfig).auth;
exports.config = {
    matcher: ['/((?!api|_next/static|_next/image|.*\\.png$).*)'],
};