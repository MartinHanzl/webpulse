// store/languages.ts
import {defineStore} from 'pinia';
import {useAuthStore} from "~/stores/auth";

export const useUserStore = defineStore('user', {
    state: () => ({
        user: [],
    }),
    actions: {
        async storeUser() {
            const bearerToken = useCookie('token');
            const {data}: any = await useFetch('/api/admin/auth/me', {
                method: 'GET',
                // @ts-ignore
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    Authorization: `Bearer ${bearerToken.value}`,
                }
            });
            if (data.value) {
                this.user = data?.value?.data;
            }
        },
    },
});
