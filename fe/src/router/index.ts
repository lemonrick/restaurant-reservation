
import { createRouter, createWebHistory } from 'vue-router/auto'
import { useAuthStore } from '@/stores/auth'

const adminRoutes = [
  {
    path: '/dashboard/users',
    name: 'UserList',
    component: () => import('@/views/dashboards/Users.vue'),
    meta: { requiresAdmin: true },
  },
];

const routes = [
  {
    path: '/',
    component: () => import('@/layouts/home/YummyLayout.vue'),
    children: [
      {
        path: '',
        name: 'Home',
        component: () => import('@/views/pages/HomePage.vue'),
        meta: { requiresAuth: false },
      }
    ]
  },
  {
    path: '/',
    component: () => import('@/layouts/blank/BlankLayout.vue'),
    children: [
      {
        name: 'Login',
        path: '/login',
        component: () => import('@/views/authentication/LoginPage.vue')
      },
      {
        name: 'Register',
        path: '/register',
        component: () => import('@/views/authentication/RegisterPage.vue')
      },
      {
        name: 'Error 404',
        path: '/error',
        component: () => import('@/views/pages/maintenance/error/Error404Page.vue')
      }
    ]
  },
  {
    path: '/dashboard',
    meta: { requiresAuth: true },
    component: () => import('@/layouts/full/FullLayout.vue'),
    children: [
      {
        path: '',
        redirect: '/dashboard/reservations'
      },
      {
        name: 'Reservations',
        path: '/dashboard/reservations',
        component: () => import('@/views/dashboards/Reservations.vue')
      },
      ...adminRoutes,
    ]
  },
  {
    path: '/:pathMatch(.*)*',
    component: () => import('@/views/pages/maintenance/error/Error404Page.vue'),
    meta: { requiresAuth: false }
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

router.beforeEach((to, from, next) => {
  const publicPages = ['/', '/login', '/register', '/error']
  const auth = useAuthStore()

  const isPublicPage = publicPages.includes(to.path)
  const authRequired = !isPublicPage && to.matched.some((record) => record.meta.requiresAuth)
  const adminRequired = to.matched.some((record) => record.meta.requiresAdmin)

  // 🔒 1. Redirect if trying to access protected route without being authenticated
  if (authRequired && !auth.user) {
    next('/login')
  }
  // 🔒 2. Admin-only access
  else if (adminRequired && (!auth.user || auth.user.role !== 'admin')) {
    next('/dashboard/reservations')
  }
  // 🎯 3. Special access rules for /register
  else if (to.path === '/register') {
    if (!auth.user) {
      next() // ✅ allow only if not logged in
    } else {
      next('/dashboard/reservations') // ❌ redirect if logged in
    }
  }
  // 🚫 4. Prevent authenticated users from accessing /login
  else if (auth.user && to.path === '/login') {
    next('/dashboard/reservations')
  }
  // ✅ 5. Default allow
  else {
    next()
  }
})

// Workaround for https://github.com/vitejs/vite/issues/11804
router.onError((err, to) => {
  if (err?.message?.includes?.('Failed to fetch dynamically imported module')) {
    if (localStorage.getItem('vuetify:dynamic-reload')) {
      console.error('Dynamic import error, reloading page did not fix it', err)
    } else {
      // console.log('Reloading page to fix dynamic import error')
      localStorage.setItem('vuetify:dynamic-reload', 'true')
      location.assign(to.fullPath)
    }
  } else {
    console.error(err)
  }
})

router.isReady().then(() => {
  localStorage.removeItem('vuetify:dynamic-reload')
})

export { routes }
export default router