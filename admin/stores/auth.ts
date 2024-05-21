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
                const token = useCookie('token');
                token.value = data?.value?.access_token;
                this.authenticated = true;
            }
        },
        logUserOut() {
            const token = useCookie('token');
            this.authenticated = false;
            token.value = null;
        },
    },
});
