/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  i18n: {
    locales: ['en', 'cs'],
    defaultLocale: 'cs',
    localeDetection: true
  },
  trailingSlash: true,
};


export default nextConfig;
