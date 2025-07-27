<script setup lang="ts">
import { computed, ref } from 'vue';
import { useCustomizerStore } from '@/stores/customizer.ts';
import { useAuthStore } from '@/stores/auth';
import { getSidebarItems } from './sidebarItem';

import NavGroup from './NavGroup/NavGroup.vue';
import NavItem from './NavItem/NavItem.vue';
import NavCollapse from './NavCollapse/NavCollapse.vue';
import Logo from '../logo/LogoMain.vue';

const customizer = useCustomizerStore();
const auth = useAuthStore();

const sidebarMenu = computed(() => getSidebarItems(auth.user));
const isRailHover = ref(false);

function onRailUpdate(val) {
  isRailHover.value = !val
}

const isLogoVisible = computed(() => {
  return !customizer.mini_sidebar || isRailHover.value
});


</script>

<template>
  <v-navigation-drawer
    left
    v-model="customizer.Sidebar_drawer"
    elevation="0"
    rail-width="75"
    mobile-breakpoint="lg"
    app
    class="leftSidebar"
    :rail="customizer.mini_sidebar"
    expand-on-hover
    @update:rail="onRailUpdate"
  >
    <!---Logo part -->

    <div
      v-if="isLogoVisible"
      class="pa-5 logo-edit">
      <Logo />
    </div>
    <!-- ---------------------------------------------- -->
    <!---Navigation -->
    <!-- ---------------------------------------------- -->
    <perfect-scrollbar class="scrollnavbar">
      <v-list class="pa-4">
        <!---Menu Loop -->
        <template v-for="(item, i) in sidebarMenu" :key="i">
          <!---Item Sub Header -->
          <NavGroup :item="item" v-if="item.header" :key="item.title" />
          <!---Item Divider -->
          <v-divider class="my-3" v-else-if="item.divider" />
          <!---If Has Child -->
          <NavCollapse class="leftPadding" :item="item" :level="0" v-else-if="item.children" />
          <!---Single Item-->
          <NavItem :item="item" v-else class="leftPadding" />
          <!---End Single Item-->
        </template>
      </v-list>
      <!--<div class="pa-4">
        <ExtraBox />
      </div>
      <div class="pa-4 text-center">
        <v-chip color="inputBorder" size="small"> v1.3.0 </v-chip>
      </div>-->
    </perfect-scrollbar>
  </v-navigation-drawer>
</template>

<style scoped>
  .logo-edit {
    padding-top: 10px !important;
  }
</style>