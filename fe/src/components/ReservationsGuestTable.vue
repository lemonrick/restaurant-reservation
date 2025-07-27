<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import api from '@/plugins/axios.ts';
import type { Reservation } from '@/models/Reservation.ts';
import dayjs from 'dayjs'
import 'dayjs/locale/en'
import UiParentCard from '@/components/shared/UiParentCard.vue';
dayjs.locale('en')

const loading = ref(false)
const reservations = ref<Reservation[]>([]);
const onlyToday = ref(false);

const headers = [
  { title: 'Booked On', value: 'created_at', sortable: true },
  { title: 'Reservation Time', value: 'starts_at', sortable: true },
  { title: 'Guests', value: 'guests_count', sortable: true },
  { title: 'Note', value: 'note', sortable: true },
];

const filteredReservations = computed(() => {
  const today = new Date().toISOString().split('T')[0];

  return reservations.value
    .filter(r => {
      if (!onlyToday.value) return true;
      return r.starts_at?.split(' ')[0] === today;
    })
});

const sortBy = ref([{ key: 'starts_at', order: 'asc' }])

function formatDate(dateStr?: string): string {
  return dateStr ? dayjs(dateStr).format('DD. M. YYYY') : ''
}

function formatDateTime(datetime: string): string {
  return dayjs(datetime).format('DD. M. YYYY – HH:mm')
}

async function loadReservations() {
  loading.value = true;
  try {
    const response = await api.get<Reservation[]>('/reservations');
    reservations.value = response.data.map(r => ({
      ...r,
      full_name: `${r.first_name ?? ''} ${r.last_name ?? ''}`.trim(),
    }));
  } catch (error) {
    console.error('Error loading data:', error);
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
  loadReservations()
})

defineExpose({
  loadReservations,
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

    <template v-if="!loading && reservations.length > 0">
      <v-switch
        v-model="onlyToday"
        label="Show only today’s reservations"
        color="success"
        class="mx-4 mb-2"></v-switch>
      <v-data-table
        :headers="headers"
        :items="filteredReservations"
        class="elevation-0 borderless-table"
        v-model:sort-by="sortBy"
        :items-per-page="-1"
        hide-default-footer
      >
        <template #item.created_at="{ item }">
          {{ formatDate(item.created_at) }}
        </template>
        <template #item.starts_at="{ item }">
          {{ formatDateTime(item.starts_at) }}
        </template>
        <template #item.note="{ item }">
          <v-tooltip location="top">
            <template #activator="{ props }">
      <span v-bind="props" style="cursor: pointer;">
        {{ item.note ? 'View note 👀' : '' }}
      </span>
            </template>
            <span>{{ item.note }}</span>
          </v-tooltip>
        </template>
      </v-data-table>
    </template>

    <template v-else-if="!loading">
      <div class="text-center pa-6 text-medium-emphasis">
        No reservations found.
      </div>
    </template>
  </UiParentCard>
</template>

<style scoped>
</style>