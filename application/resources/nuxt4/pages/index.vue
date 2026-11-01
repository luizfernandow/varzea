<script setup>
useHead({
  title: 'Home'
})
</script>

<template>
  <div>
    <UPageHero
      title="Várzealand"
      description="Competições de ciclismo, resultados e classificação."
    >
      <NuxtImg
        src="/mapa.jpeg"
        alt="Mapa do circuito Várzealand"
        class="rounded-lg shadow-2xl ring ring-default m-auto"
      />
    </UPageHero>
  </div>
</template>
