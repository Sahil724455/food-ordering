import React, {useEffect, useState} from 'react';
import {API} from '../api';

export default function MenuList({restaurantSlug, onAdd}){
  const [menu,setMenu]=useState([]);
  useEffect(()=> {
    fetch(`${API}/restaurants/${restaurantSlug}/menu`)
      .then(r=>r.json())
      .then(d=>setMenu(d));
  },[restaurantSlug]);

  return (
    <div>
      <h2>Menu</h2>
      {menu.map(item=>(
        <div key={item.id} style={{border:'1px solid #ddd',padding:8,margin:6}}>
          <h4>{item.name} — Rs {item.price}</h4>
          <p>{item.description}</p>
          <button onClick={()=> onAdd(item.id)}>Add to cart</button>
        </div>
      ))}
    </div>
  );
}
