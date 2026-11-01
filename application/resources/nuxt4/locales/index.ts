const files = import.meta.glob('./**/*.json', { eager: true, import: 'default' }) as Record<
  string,
  Record<string, string>
>
const messages: Record<string, Record<string, Record<string, string>>> = {}

for (const [path, content] of Object.entries(files)) {
  const [, locale, namespace] = path.match(/^\.\/([^/]+)\/([^/]+)\.json$/) ?? []
  if (!locale || !namespace) {
    continue
  }
  messages[locale] ??= {}
  messages[locale][namespace] = content
}

export default messages
