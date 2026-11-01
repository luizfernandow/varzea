export default defineNuxtRouteMiddleware(async () => {
  const auth = useAuth()
  if (!auth.checked.value) await auth.refresh()
  if (!auth.loggedIn.value) return navigateTo('/login')
})
