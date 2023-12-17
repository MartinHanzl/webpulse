/** @type {import('next').NextConfig} */
const nextConfig = {
    experimental: {
        serverActions: true
    },
    proxy: {
        '/api': {
            target: 'http://localhost:8000',
            changeOrigin: true,
            pathRewrite: { '^/api': '' },
        },
    },
}

module.exports = nextConfig
