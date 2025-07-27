import { describe, it, expect, beforeEach, vi, afterEach } from 'vitest';
import { setActivePinia, createPinia } from 'pinia';

vi.mock('@/plugins/axios', () => ({
  default: {
    post: vi.fn((url: string, payload: never) => {
      if (url === '/login') {
        return Promise.resolve({
          data: {
            user: { name: 'John', email: payload.email, role: 'admin' }
          }
        });
      }
      if (url === '/logout') {
        return Promise.resolve({});
      }
      if (url === '/register') {
        return Promise.resolve({
          data: { user: { ...payload, id: 1 } }
        });
      }
      return Promise.reject(new Error('Unknown endpoint'));
    })
  }
}));

const localStorageMock = (() => {
  let store: Record<string, string> = {};
  return {
    getItem: (key: string) => store[key] || null,
    setItem: (key: string, value: string) => { store[key] = value; },
    removeItem: (key: string) => { delete store[key]; },
    clear: () => { store = {}; }
  };
})();

Object.defineProperty(window, 'localStorage', { value: localStorageMock });

import { useAuthStore } from '@/stores/auth';

describe('auth store', () => {
  beforeEach(() => {
    setActivePinia(createPinia());
    localStorage.clear();
  });

  afterEach(() => {
    vi.clearAllMocks();
  });

  it('has null user and isLoading false by default', () => {
    const auth = useAuthStore();
    expect(auth.user).toBeNull();
    expect(auth.isLoading).toBe(false);
  });

  it('login sets user and saves user to localStorage', async () => {
    const auth = useAuthStore();
    await auth.login('test@example.com', 'testpassword');
    expect(auth.user).toBeTruthy();
    expect(auth.user?.email).toBe('test@example.com');
    expect(localStorage.getItem('restaurant_user')).toContain('test@example.com');
    expect(auth.isLoading).toBe(false);
  });

  it('isAdmin returns true for admin user', async () => {
    const auth = useAuthStore();
    await auth.login('admin@example.com', 'password');
    expect(auth.isAdmin).toBe(true);
  });

  it('register calls API and keeps user null', async () => {
    const auth = useAuthStore();
    await auth.register({ name: 'New', email: 'new@example.com', password: 'p', confirmPassword: 'p' });
    expect(auth.user).toBeNull();
    expect(auth.isLoading).toBe(false);
  });

  it('logout clears user and localStorage', async () => {
    const auth = useAuthStore();
    // Set fake user
    auth.user = { name: 'ToBeCleared', email: 'clear@example.com', role: 'user' };
    localStorage.setItem('restaurant_user', JSON.stringify(auth.user));
    // Stub window.location.href to not reload during test

    const setHref = vi.fn();
    Object.defineProperty(window, 'location', {
      value: { set href(v) { setHref(v); }, get href() { return '' } },
      configurable: true,
    });

    await auth.logout();
    expect(auth.user).toBeNull();
    expect(localStorage.getItem('restaurant_user')).toBeNull();
    expect(setHref).toHaveBeenCalledWith('/');
  });
});
