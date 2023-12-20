import { decodeToken } from '../../utils/decodeToken';
import axios from 'axios';

export default async function middleware(req, res, next) {
    const token = req.cookies.token;

    if (token) {
        const decoded = decodeToken(token);
        const currentTime = Date.now() / 1000; // Current time in seconds

        // Check if token is about to expire in the next 10 minutes
        if (decoded.exp < currentTime + 600) {
            try {
                // Assuming your API provides an endpoint to refresh the token
                const response = await axios.post('http://localhost:8000/api/auth/me', { token });
                const newToken = response.data.token;

                // Set the new token in the cookie
                res.cookie('token', newToken, { httpOnly: true, path: '/' });

                // Replace the token in the request for downstream use
                req.cookies.token = newToken;
            } catch (error) {
                console.error('Error refreshing token:', error);
                // Handle error, possibly by redirecting to login
                return res.redirect('/login');
            }
        }
    }

    next();
}
