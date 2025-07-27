<script setup lang="ts">
import { ref } from 'vue';

import BaseBreadcrumb from '@/components/shared/BaseBreadcrumb.vue';
import ReservationsAdminTable from '@/components/ReservationsAdminTable.vue';
import DashboardExpand from '@/components/DashboardExpand.vue';
import DashboardReservationCreateWithPhone from '@/components/DashboardReservationCreateWithPhone.vue';
import DashboardReservationCreateForGuest from '@/components/DashboardReservationCreateForGuest.vue';

const page = ref({ title: 'Reservations' });

const tableRef = ref()
function handleReservationAdded() {
  tableRef.value?.loadReservations()
}

const selected = ref('phone')
</script>

<template>
  <BaseBreadcrumb :title="page.title"></BaseBreadcrumb>
  <v-row>
    <v-col cols="12" md="12">
      <DashboardExpand title="Add new reservation"> <!-- :default-expanded="true" -->
        <div class="d-flex justify-center my-4">
          <v-btn-toggle
            v-model="selected"
            mandatory
            divided
          >
            <v-btn value="phone">Phone Reservation</v-btn>
            <v-btn value="classic">Classic Reservation</v-btn>
          </v-btn-toggle>
        </div>
        <DashboardReservationCreateWithPhone
          v-if="selected === 'phone'"
          @reservation-added="handleReservationAdded"
        />
        <DashboardReservationCreateForGuest
          v-else
          @reservation-added="handleReservationAdded"
        />
      </DashboardExpand>
      <ReservationsAdminTable ref="tableRef" />
    </v-col>
  </v-row>
</template>