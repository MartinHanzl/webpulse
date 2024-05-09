// store/auth.ts

import { defineStore } from 'pinia';

interface UserPayloadInterface {
    email: string;
    password: string;
}

export const useAuthStore = defineStore('auth', {
    state: () => ({
        authenticated: false,
        loading: false,
    }),
    actions: {
        async authenticateUser({ email, password }: UserPayloadInterface) {
            // useFetch from nuxt 3
            const { data, pending }: any = await useFetch('/api/admin/auth/login', {
                method: 'post',
                headers: { 'Content-Type': 'application/json' },
                body: {
                    email,
                    password,
                },
            });
            this.loading = pending;

            if (data.value) {
                const token = useCookie('token'); // useCookie new hook in nuxt 3
                token.value = data?.value?.access_token; // set token to cookie
                this.authenticated = true; // set authenticated  state value to true
            }
        },
        logUserOut() {
            const token = useCookie('token'); // useCookie new hook in nuxt 3
            this.authenticated = false; // set authenticated  state value to false
            token.value = null; // clear the token cookie
        },
    },
});
