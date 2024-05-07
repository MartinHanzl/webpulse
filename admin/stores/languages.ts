import {defineStore} from 'pinia';

export const useLanguagesStore = defineStore('languages', {
    id: 'languageStore',
    actions: () => ({
        async fetchLanguages() {
            try {
                return await $fetch('/api/admin/language', {
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
