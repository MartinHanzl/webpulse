import {defineStore} from 'pinia';

export const useLanguagesStore = defineStore('languages', {
    actions: () => ({
        async fetchLanguages() {
            try {
                return await useFetch('/api/admin/language', {
                    method: 'GET',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                    }
                });
            } catch (error) {
                console.error('Error:', error);
            }
        }
    }),
});
