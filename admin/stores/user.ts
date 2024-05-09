// store/languages.ts
import {defineStore} from 'pinia';
import {useAuthStore} from "~/stores/auth";

export const useUserStore = defineStore('user', {
    state: () => ({
        user: [],
    }),
    actions: {
        async storeUser() {
            const {data}: any = await useFetch('/api/admin/auth/me', {
                method: 'GET',
                authorization: 'Bearer ' + useAuthStore().token,
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                }
            });
            if (data.value) {
                this.user = data?.value?.data;
            }
        },
    },
});
