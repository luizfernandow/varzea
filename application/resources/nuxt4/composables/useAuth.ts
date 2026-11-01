type User = { id: number; name: string; email?: string }

export function useAuth() {
  const user = useState<User | null>('auth-user', () => null)
  const checked = useState('auth-checked', () => false)
  const loggedIn = computed(() => !!user.value)

  async function refresh() {
    try {
      user.value = await useApi<User>('/api/auth/user')
    } catch {
      user.value = null
    } finally {
      checked.value = true
    }
    return user.value
  }

  async function login(email: string, password: string) {
    const config = useRuntimeConfig()
    await $fetch('/sanctum/csrf-cookie', {
      baseURL: config.public.apiBase,
      credentials: 'include',
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    await useApi('/api/auth/login', { method: 'POST', body: { email, password } })
    await refresh()
  }

  async function logout() {
    await useApi('/api/auth/logout', { method: 'POST' })
    user.value = null
  }

  return { user, checked, loggedIn, refresh, login, logout }
}
