import { defineStore } from 'pinia';
// import router from '@/router';
import type { User } from '@/models/User';
import type { RegisterPayload } from '@/models/RegisterPayload';
import api from '@/plugins/axios.ts';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('restaurant_user') || 'null') as User | null,
    isLoading: false
  }),
  getters: {
    isAdmin: (state) => state.user?.role === 'admin' || false,
  },
  actions: {
    login(email: string, password: string): Promise<AxiosResponse> {
      this.isLoading = true;

      return api.post('/login', { email, password })
        .then(response => {
          const user = response.data.user;

          this.user = user;
          localStorage.setItem('restaurant_user', JSON.stringify(user));

          return response;
        })
        .finally(() => {
          this.isLoading = false;
        });
    },

    register(payload: RegisterPayload): Promise<AxiosResponse> {
      this.isLoading = true;

      return api.post('/register', payload)
        .then(response => {
          return response;
        })
        .finally(() => {
          this.isLoading = false;
        });
    },

    logout(): Promise<void> {
      return api.post('/logout')
        .catch(error => {
          console.warn('Logout API call failed (probably expired token):', error);
        })
        .finally(() => {
          this.user = null;
          localStorage.removeItem('restaurant_user');
          // router.push('/')
          window.location.href = '/';
        });
    }
  }
});
