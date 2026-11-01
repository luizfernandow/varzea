import { cp, readdir, rm, stat } from 'node:fs/promises'
import { dirname, join, resolve } from 'node:path'
import { fileURLToPath } from 'node:url'

const appRoot = resolve(dirname(fileURLToPath(import.meta.url)), '..')
const generatedDir = join(appRoot, '.output', 'public')
const laravelPublicDir = join(appRoot, 'public')
const generatedIndex = join(generatedDir, 'index.html')

try {
  if (!(await stat(generatedIndex)).isFile()) {
    throw new Error('Nuxt did not generate .output/public/index.html.')
  }
} catch (error) {
  throw new Error(
    `Cannot publish the Nuxt SPA because ${generatedIndex} is missing. Run nuxt generate first.`,
    { cause: error }
  )
}

// Keep Laravel's entry point, rewrites, public storage symlink, and robots rules.
const preservedLaravelFiles = new Set(['index.php', 'robots.txt', '.htaccess', 'storage'])
for (const entry of await readdir(laravelPublicDir)) {
  if (preservedLaravelFiles.has(entry)) {
    continue
  }
  await rm(join(laravelPublicDir, entry), { recursive: true, force: true })
}

await cp(generatedDir, laravelPublicDir, { recursive: true, force: true })
console.log(`Published the Nuxt SPA to ${laravelPublicDir}`)
