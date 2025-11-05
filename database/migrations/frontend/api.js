export const API = "http://localhost:8000/api";
export function authHeaders(){ 
  const token = localStorage.getItem('token');
  return token ? { Authorization: `Bearer ${token}` } : {};
}
