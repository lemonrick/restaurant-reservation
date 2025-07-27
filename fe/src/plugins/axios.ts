import axios from 'axios'
import router from '@/router'
import { useAuthStore } from '@/stores/auth'

// Create axios instance
const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  timeout: 10000,
})

// Add Authorization header from localStorage if token exists
api.interceptors.request.use(
  (config) => {
    const user = localStorage.getItem('restaurant_user')
    if (user) {
      try {
        const parsed = JSON.parse(user)
        const token = parsed?.token
        if (token) {
          config.headers.Authorization = `Bearer ${token}`
        }
      } catch {
        console.warn('Invalid token in localStorage')
      }
    }
    return config
  },
  (error) => Promise.reject(error)
)

// Handle global 401 Unauthorized errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      try {
        const auth = useAuthStore()
        auth.user = null
      } catch {
        // useAuthStore may not be available depending on context
      }
      localStorage.removeItem('restaurant_user')
      // router.push('/login')
      window.location.href = '/login';
    }

    return Promise.reject(error)
  }
)

export default api