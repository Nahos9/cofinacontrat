import{a7 as f,r as v,w as V,a as g,o as c,f as i,e as o,b as s,Z as x,s as n,d as y,x as h,$ as k,c as b,F as w,A as B,M as S}from"./main-35f434e5.js";import{V as C}from"./VTooltip-14162ccf.js";import{V as I}from"./VMenu-ad7875d9.js";import{V as T,a as N}from"./VList-a84ed864.js";import"./VOverlay-6080deb7.js";import"./forwardRefs-6ea3df5c.js";import"./VImg-fb08db81.js";import"./VAvatar-5cd70722.js";import"./VDivider-2199d20e.js";const z={class:"text-capitalize"},L={__name:"ThemeSwitcher",props:{themes:{type:Array,required:!0}},setup(l){const r=l,t=f(),a=v([t.theme]);return V(()=>t.theme,()=>{a.value=[t.theme]},{deep:!0}),(m,p)=>{const d=g("IconBtn");return c(),i(d,{color:"rgba(var(--v-theme-on-surface), var(--v-high-emphasis-opacity))"},{default:o(()=>{var u;return[s(x,{icon:(u=r.themes.find(e=>e.name===n(t).theme))==null?void 0:u.icon,size:"26"},null,8,["icon"]),s(C,{activator:"parent","open-delay":"1000","scroll-strategy":"close"},{default:o(()=>[y("span",z,h(n(t).theme),1)]),_:1}),s(I,{activator:"parent",offset:"14px"},{default:o(()=>[s(T,{selected:n(a),"onUpdate:selected":p[0]||(p[0]=e=>k(a)?a.value=e:null)},{default:o(()=>[(c(!0),b(w,null,B(r.themes,({name:e,icon:_})=>(c(),i(N,{key:e,value:e,"prepend-icon":_,color:"primary",class:"text-capitalize",onClick:()=>{n(t).theme=e}},{default:o(()=>[S(h(e),1)]),_:2},1032,["value","prepend-icon","onClick"]))),128))]),_:1},8,["selected"])]),_:1})]}),_:1})}}},Z={__name:"NavbarThemeSwitcher",setup(l){const r=[{name:"system",icon:"tabler-device-laptop"},{name:"light",icon:"tabler-sun-high"},{name:"dark",icon:"tabler-moon"}];return(t,a)=>{const m=L;return c(),i(m,{themes:r})}}};export{Z as default};
