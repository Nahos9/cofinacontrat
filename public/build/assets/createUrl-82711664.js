import{s as f}from"./index-9c720871.js";import{a1 as b,bU as e}from"./main-a1bb8d1c.js";const s=(c,r)=>b(()=>{if(!(r!=null&&r.query))return e(c);const a=e(c),t=e(r==null?void 0:r.query),u=Object.fromEntries(Object.entries(t).map(([m,y])=>[m,e(y)]));return`${a}${u?`?${f(u)}`:""}`});export{s as c};