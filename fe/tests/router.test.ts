import { describe, it, expect, vi, beforeEach } from 'vitest'
import { createMemoryHistory, createRouter } from 'vue-router'
import { setActivePinia, createPinia } from 'pinia'
import { nextTick, defineComponent } from 'vue'
import { routes } from '@/router'
import { useAuthStore } from '@/stores/auth'

// Mock lazy-loaded components used in routes
const DummyComponent = defineComponent({ template: '<div>Dummy</div>' })
vi.mock('@/views/pages/HomePage.vue', () => ({ default: DummyComponent }))
vi.mock('@/views/authentication/LoginPage.vue', () => ({ default: DummyComponent }))
vi.mock('@/views/authentication/RegisterPage.vue', () => ({ default: DummyComponent }))
vi.mock('@/views/dashboards/Reservations.vue', () => ({ default: DummyComponent }))
vi.mock('@/views/dashboards/Users.vue', () => ({ default: DummyComponent }))
vi.mock('@/layouts/full/FullLayout.vue', () => ({ default: DummyComponent }))
vi.mock('@/layouts/blank/BlankLayout.vue', () => ({ default: DummyComponent }))
vi.mock('@/layouts/home/YummyLayout.vue', () => ({ default: DummyComponent }))
vi.mock('@/views/pages/maintenance/error/Error404Page.vue', () => ({ default: DummyComponent }))

describe('Router Guards', () => {
  let router: ReturnType<typeof createRouter>

  beforeEach(() => {
    setActivePinia(createPinia())
    router = createRouter({
      history: createMemoryHistory(),
      routes
    })
  })

  it('contains base paths', () => {
    const paths = router.getRoutes().map(r => r.path)
    expect(paths).toContain('/')
    expect(paths).toContain('/login')
    expect(paths).toContain('/register')
    expect(paths).toContain('/dashboard')
    expect(paths).toContain('/dashboard/users')
    expect(paths).toContain('/:pathMatch(.*)*')
  })

  it('requires admin access for /dashboard/users', () => {
    const adminRoute = router.getRoutes().find(r => r.path === '/dashboard/users')
    expect(adminRoute?.meta.requiresAdmin).toBe(true)
  })
  

  it('allows admin user to access /dashboard/users', async () => {
    const auth = useAuthStore()
    auth.user = { name: 'Admin', email: 'admin@email.com', role: 'admin' }

    await router.push('/dashboard/users')
    await router.isReady()
    await nextTick()
    expect(router.currentRoute.value.fullPath).toBe('/dashboard/users')
  })
  
  it('allows unauthenticated user to access /login', async () => {
    const auth = useAuthStore()
    auth.user = null

    await router.push('/login')
    await router.isReady()
    await nextTick()
    expect(router.currentRoute.value.fullPath).toBe('/login')
  })

  it('allows unauthenticated user to access /register', async () => {
    const auth = useAuthStore()
    auth.user = null

    await router.push('/register')
    await router.isReady()
    await nextTick()
    expect(router.currentRoute.value.fullPath).toBe('/register')
  })

})
