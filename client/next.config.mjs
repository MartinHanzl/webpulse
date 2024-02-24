/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  i18n: {
    locales: ['en', 'cs', 'de'],
    defaultLocale: 'en',
    localeDetection: false,
    domains: [
      {
        domain: 'example.com',
        defaultLocale: 'en',
        http: true,
      },
      {
        domain: 'example.cz',
        defaultLocale: 'cs',
        http: true,
      },
      {
        domain: 'example.de',
        defaultLocale: 'de',
        http: true,
      },
    ],
  },
  trailingSlash: true,
};


export default nextConfig;
