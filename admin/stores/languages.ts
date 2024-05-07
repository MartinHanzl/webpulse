import {defineStore} from 'pinia';
import {$fetch} from "ofetch";

export const useLanguageStore = defineStore('language', {
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
