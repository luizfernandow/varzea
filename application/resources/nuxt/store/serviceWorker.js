export const state = () => ({
    updated: false,
})

export const mutations = {
    setUpdate(state, updated) {
        state.updated = updated
    },
}
