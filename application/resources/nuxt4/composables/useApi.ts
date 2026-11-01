export function useApi<T>(path: string, options: Record<string, unknown> = {}) {
  const config = useRuntimeConfig()
  const headers: Record<string, string> = {
    Accept: 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  }

  if (import.meta.client) {
    const xsrfCookie = document.cookie
      .split('; ')
      .find((cookie) => cookie.startsWith('XSRF-TOKEN='))
    if (xsrfCookie) {
      headers['X-XSRF-TOKEN'] = decodeURIComponent(xsrfCookie.slice('XSRF-TOKEN='.length))
    }
  }

  return $fetch<T>(path, {
    baseURL: config.public.apiBase,
    credentials: 'include',
    headers,
    ...options
  })
}
