// @ts-check
import withNuxt from './.nuxt/eslint.config.mjs';

export default withNuxt(
    {
        extends: [
            '@nuxtjs/eslint-config-typescript',
            '@nuxtjs/eslint-config-prettier',
            'plugin:vue/vue3-recommended',
            'plugin:nuxt/recommended',
        ],
        rules: {
            'vue/multi-word-component-names': 0,
            'vue/no-v-html': 0,
            'vue/no-unused-vars': 0,
            'vue/valid-v-slot': 0,
            'vue/require-default-prop': 0,
            'vue/no-deprecated-slot-attribute': 0,
        },
    }
);
