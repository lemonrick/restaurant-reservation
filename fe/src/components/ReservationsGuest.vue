<script setup lang="ts">
import { ref } from 'vue';

import BaseBreadcrumb from '@/components/shared/BaseBreadcrumb.vue';
import ReservationsGuestTable from '@/components/ReservationsGuestTable.vue';
import DashboardExpand from '@/components/DashboardExpand.vue';
import DashboardReservationCreateSelf from '@/components/DashboardReservationCreateSelf.vue';

const page = ref({ title: 'My reservations' });

const tableRef = ref()

function handleReservationAdded() {
  tableRef.value?.loadReservations()
}
</script>

<template>
  <BaseBreadcrumb :title="page.title"></BaseBreadcrumb>
  <v-row>
    <v-col cols="12" md="12">
      <DashboardExpand title="Add new reservation" :default-expanded="true">
        <DashboardReservationCreateSelf @reservation-added="handleReservationAdded" />
      </DashboardExpand>
      <ReservationsGuestTable ref="tableRef" />
    </v-col>
  </v-row>
</template>