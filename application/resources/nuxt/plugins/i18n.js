/**
 * Vue i18n
 *
 * @library
 *
 * http://kazupon.github.io/vue-i18n/en/
 */

// Lib imports
import Vue from 'vue'
import VueI18n from 'vue-i18n'
import messages from '~/locales'

Vue.use(VueI18n)

export default ({ app }) => {
    // Set i18n instance on app
    // This way we can use it in middleware and pages asyncData/fetch

    const locale = navigator.language

    app.i18n = new VueI18n({
        locale,
        fallbackLocale: 'en-US',
        messages,
    })
}
