import { describe, it, beforeEach, expect, vi } from 'vitest';
import api from '@/plugins/axios';

vi.mock('@/stores/auth', () => ({
  useAuthStore: () => ({
    user: { name: 'Test', token: 'mocked-token' }
  }),
}));

describe('axios plugin', () => {
  beforeEach(() => {
    localStorage.clear();
  });

  it('adds Authorization header if token exists', async () => {
    localStorage.setItem(
      'restaurant_user',
      JSON.stringify({ token: 'TEST_TOKEN' })
    );
    const config = await api.interceptors.request.handlers[0].fulfilled({ headers: {} });
    expect(config.headers.Authorization).toBe('Bearer TEST_TOKEN');
  });

  it('does not set Authorization header if no user', async () => {
    const config = await api.interceptors.request.handlers[0].fulfilled({ headers: {} });
    expect(config.headers.Authorization).toBeUndefined();
  });

  it('handles 401 response by clearing user and redirecting', async () => {
    // Set up localStorage and spy
    localStorage.setItem(
      'restaurant_user',
      JSON.stringify({ token: 'XYZ', name: 'any' })
    );

    const setHref = vi.fn();
    Object.defineProperty(window, 'location', {
      value: { set href(v) { setHref(v); }, get href() { return '' } },
      configurable: true,
    });

    // Mock error object returned from axios
    const error = {
      response: { status: 401 }
    };

    // Make response interceptor reject
    await expect(
      api.interceptors.response.handlers[0].rejected(error)
    ).rejects.toBe(error);

    expect(localStorage.getItem('restaurant_user')).toBeNull();
    expect(setHref).toHaveBeenCalledWith('/login');
  });
});
