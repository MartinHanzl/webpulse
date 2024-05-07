// store/languages.ts
import {defineStore} from 'pinia';

export const useLanguagesStore = defineStore('languages', {
    state: () => ({
        languages: [],
    }),
    actions: {
        async storeLanguages() {
            const { data }: any = await useFetch('/api/admin/language', {
                method: 'GET',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                }
            });
            if (data.value) {
                this.languages = data?.value?.data;
            }
        },
    },
});
