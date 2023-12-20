import { createProxyMiddleware } from 'http-proxy-middleware';

export default createProxyMiddleware({
    target: 'http://localhost:8000', // target host
    changeOrigin: true, // needed for virtual hosted sites
    ws: true, // proxy websockets
    pathRewrite: {
        '^/api': '', // rewrite path
    },
});

export const config = {
    api: {
        bodyParser: false,
        externalResolver: true,
    },
};
