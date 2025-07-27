export interface Reservation {
  id: number;
  table_id: number;
  user_id?: number;
  phone?: string;
  first_name?: string;
  last_name?: string;
  starts_at: string;
  ends_at: string;
  created_at: string;
  updated_at: string;
  guests_count: number;
  note?: string;
}