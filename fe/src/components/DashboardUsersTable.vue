<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '@/plugins/axios'
import type { User } from '@/models/User'
import dayjs from 'dayjs'
import 'dayjs/locale/sk'
import UiParentCard from '@/components/shared/UiParentCard.vue';
dayjs.locale('sk')

const loading = ref(false)
const users = ref<User[]>([])
const search = ref('')

const headers = [
  { title: 'ID', value: 'id', sortable: true },
  { title: 'First Name', value: 'first_name', sortable: true },
  { title: 'Last Name', value: 'last_name', sortable: true },
  { title: 'Email', value: 'email', sortable: true },
  { title: 'Phone', value: 'phone', sortable: true },
  { title: 'Role', value: 'role', sortable: true },
  { title: 'Created At', value: 'created_at', sortable: true },
]

const sortBy = ref([{ key: 'id', order: 'desc' }])

function formatDate(dateStr?: string): string {
  return dateStr ? dayjs(dateStr).format('D. M. YYYY') : ''
}

async function loadUsers() {
  loading.value = true
  try {
    const response = await api.get<User[]>('/users')
    users.value = response.data
  } catch (error) {
    console.error('Error loading data:', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadUsers()
})

defineExpose({
  loadUsers,
})
</script>

<template>
  <UiParentCard>
    <v-progress-linear
      v-if="loading"
      indeterminate
      color="primary"
      class="mb-4"
    />

    <template v-if="!loading && users.length > 0">
      <v-text-field
        v-model="search"
        label="Search"
        class="mb-4"
        prepend-inner-icon="$magnify"
        variant="outlined"
        clearable
      />
      <v-data-table
        :headers="headers"
        :items="users"
        class="elevation-0 borderless-table"
        :search="search"
        v-model:sort-by="sortBy"
      >
        <template #item.created_at="{ item }">
          {{ formatDate(item.created_at) }}
        </template>
      </v-data-table>
    </template>

    <template v-else-if="!loading">
      <div class="text-center pa-6 text-medium-emphasis">
        No users found.
      </div>
    </template>
  </UiParentCard>
</template>