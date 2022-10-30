export default async function ({ store }) {
    const workbox = await window.$workbox
    if (workbox) {
        workbox.addEventListener('installed', (event) => {
            // If we don't do this we'll be displaying the notification after the initial installation, which isn't perferred.
            if (event.isUpdate) {
                store.commit('serviceWorker/setUpdate', true)
            }
        })
    }
}
