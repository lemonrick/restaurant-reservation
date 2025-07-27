<script setup lang="ts">
import { ref } from 'vue'
import dayjs from 'dayjs'
import 'dayjs/locale/sk'
dayjs.locale('sk')

import BaseBreadcrumb from '@/components/shared/BaseBreadcrumb.vue'
import DashboardUsersTable from '@/components/DashboardUsersTable.vue';
import DashboardExpand from '@/components/DashboardExpand.vue';
import DashboardUserAdd from '@/components/DashboardUserAdd.vue';

const page = ref({ title: 'Users' })

const tableRef = ref()
function handleUserAdded() {
  tableRef.value?.loadUsers()
}
</script>

<template>
  <BaseBreadcrumb :title="page.title" />
  <v-row>
    <v-col cols="12">
      <DashboardExpand title="Add new user" >
        <DashboardUserAdd @user-added="handleUserAdded" />
      </DashboardExpand>
      <DashboardUsersTable ref="tableRef" />
    </v-col>
  </v-row>
</template>