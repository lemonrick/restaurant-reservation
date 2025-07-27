export interface RegisterPayload {
  first_name?: string;
  last_name: string;
  email?: string;
  phone: string;
  password?: string;
  role: 'guest' | 'admin';
}