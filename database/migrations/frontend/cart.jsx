import React, {useState, useEffect} from 'react';
import {API, authHeaders} from '../api';

export default function Cart(){
  const [cart,setCart] = useState({items:[]});
  useEffect(()=> fetchCart(), []);

  function fetchCart(){
    fetch(`${API}/cart`, {headers: {...authHeaders()}})
      .then(r=>r.json()).then(d=>setCart(d.cart || {}));
  }

  function add(menuItemId){
    fetch(`${API}/cart/add`, {
      method:'POST',
      headers:{ 'Content-Type':'application/json', ...authHeaders() },
      body: JSON.stringify({menu_item_id: menuItemId, quantity:1})
    }).then(fetchCart);
  }

  return (
    <div>
      <h3>Your Cart</h3>
      {cart.items?.map(ci => (
        <div key={ci.id}>
          {ci.menu_item.name} x {ci.quantity}
        </div>
      ))}
    </div>
  );
}
