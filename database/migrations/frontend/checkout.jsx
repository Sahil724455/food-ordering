import React from 'react';
import {API, authHeaders} from '../api';

export default function Checkout({items, restaurantId}){
  async function placeOrder(){
    const payload = {
      restaurant_id: restaurantId,
      items: items.map(i=>({menu_item_id:i.id, quantity:i.qty})),
      delivery_address: {line1:'Test Address', city:'Kathmandu'},
      payment_method: 'dummy',
      card_number: '4242424242424242'
    };
    const res = await fetch(`${API}/orders`, {
      method:'POST',
      headers:{ 'Content-Type':'application/json', ...authHeaders() },
      body: JSON.stringify(payload)
    });
    const data = await res.json();
    if(res.ok){ alert('Order placed: '+data.order.id); } else { alert('Error: '+(data.message||JSON.stringify(data))); }
  }

  return <button onClick={placeOrder}>Place Order (dummy)</button>;
}
