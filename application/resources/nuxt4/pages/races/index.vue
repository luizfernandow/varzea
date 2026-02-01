<script setup lang="ts">
import { getPaginationRowModel } from '@tanstack/vue-table'
import type { TableColumn } from '@nuxt/ui'

const table = useTemplateRef('table')

type Race = {
  id: number
  name: string
  date_start: Date
  time_start: string
}

type RaceData = {
  data: Race[]
}

const { data, status } = await useFetch<RaceData>('http://localhost/api/races', {
  key: 'table-races',
  lazy: true
})

const columns: TableColumn<Race>[] = [
  {
    accessorKey: 'name',
    header: 'Name'
  },
  {
    accessorKey: 'date_start',
    header: 'Date start'
  },
  {
    accessorKey: 'time_start',
    header: 'Time start'
  }
]

const globalFilter = ref()

const pagination = ref({
  pageIndex: 0,
  pageSize: 5
})
</script>

<template>
  <UContainer>
    <UPageHeader title="Races" />
    <UPageBody>
      <UInput v-model="globalFilter" class="max-w-sm" placeholder="Pesquisar..." />
      <UTable
        ref="table"
        v-model:global-filter="globalFilter"
        v-model:pagination="pagination"
        :pagination-options="{
          getPaginationRowModel: getPaginationRowModel()
        }"
        :data="data?.data"
        :columns="columns"
        :loading="status === 'pending'"
        class="flex-1 h-80"
      />
      <div class="flex justify-center border-t border-default pt-1">
        <UPagination
          :default-page="(table?.tableApi?.getState().pagination.pageIndex || 0) + 1"
          :items-per-page="table?.tableApi?.getState().pagination.pageSize"
          :total="table?.tableApi?.getFilteredRowModel().rows.length"
          @update:page="(p: number) => table?.tableApi?.setPageIndex(p - 1)"
        />
      </div>
    </UPageBody>
  </UContainer>
</template>
