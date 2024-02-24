/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  i18n: {
    locales: ['en', 'cs', 'de'],
    defaultLocale: 'en',
    localeDetection: true
  },
  trailingSlash: true,
};


export default nextConfig;
