import{m as p,u as S,b as u}from"./VOverlay-6080deb7.js";import{f as h}from"./forwardRefs-6ea3df5c.js";import{ac as O,aj as T,af as x,ak as k,al as w,a1 as e,r as A,z as d,ag as C,b as I}from"./main-35f434e5.js";const R=O({id:String,text:String,...T(p({closeOnBack:!1,location:"end",locationStrategy:"connected",eager:!0,minWidth:0,offset:10,openOnClick:!1,openOnHover:!0,origin:"auto",scrim:!1,scrollStrategy:"reposition",transition:!1}),["absolute","persistent"])},"VTooltip"),z=x()({name:"VTooltip",props:R(),emits:{"update:modelValue":t=>!0},setup(t,v){let{slots:a}=v;const n=k(t,"modelValue"),{scopeId:m}=S(),f=w(),r=e(()=>t.id||`v-tooltip-${f}`),l=A(),g=e(()=>t.location.split(" ").length>1?t.location:t.location+" center"),V=e(()=>t.origin==="auto"||t.origin==="overlap"||t.origin.split(" ").length>1||t.location.split(" ").length>1?t.origin:t.origin+" center"),P=e(()=>t.transition?t.transition:n.value?"scale-transition":"fade-transition"),b=e(()=>d({"aria-describedby":r.value},t.activatorProps));return C(()=>{const[y]=u.filterProps(t);return I(u,d({ref:l,class:["v-tooltip",t.class],style:t.style,id:r.value},y,{modelValue:n.value,"onUpdate:modelValue":o=>n.value=o,transition:P.value,absolute:!0,location:g.value,origin:V.value,persistent:!0,role:"tooltip",activatorProps:b.value,_disableGlobalStack:!0},m),{activator:a.activator,default:function(){var c;for(var o=arguments.length,s=new Array(o),i=0;i<o;i++)s[i]=arguments[i];return((c=a.default)==null?void 0:c.call(a,...s))??t.text}})}),h({},l)}});export{z as V};
