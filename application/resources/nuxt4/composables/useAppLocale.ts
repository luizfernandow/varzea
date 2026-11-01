import messages from '../locales'

export function useAppLocale() {
  const locale = useState<'pt-BR' | 'en-US'>('locale', () =>
    import.meta.client && navigator.language.toLowerCase().startsWith('pt') ? 'pt-BR' : 'en-US'
  )
  const fallback = messages['pt-BR'] ?? messages['en-US'] ?? {}
  const current = computed(() => messages[locale.value] ?? fallback)
  const menu = computed(() => current.value.menu)

  function t(namespace: string, key: string, values: Record<string, string | number> = {}) {
    let value: unknown = current.value[namespace]
    for (const part of key.split('.')) {
      value =
        typeof value === 'object' && value !== null
          ? (value as Record<string, unknown>)[part]
          : undefined
    }
    let result = typeof value === 'string' ? value : key
    for (const [name, value] of Object.entries(values)) {
      result = result.replaceAll(`{${name}}`, String(value))
    }
    return result
  }

  function toggle() {
    locale.value = locale.value === 'pt-BR' ? 'en-US' : 'pt-BR'
  }

  return { locale, menu, t, toggle }
}
