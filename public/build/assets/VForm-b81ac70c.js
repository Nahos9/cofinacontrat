import{ac as p,ad as b,af as v,r as F,ag as V,b as y}from"./main-e84ce0a6.js";import{i as h,j as R}from"./VTextField-bed2e038.js";import{f as P}from"./forwardRefs-6ea3df5c.js";const k=p({...b(),...h()},"VForm"),S=v()({name:"VForm",props:k(),emits:{"update:modelValue":o=>!0,submit:o=>!0},setup(o,f){let{slots:n,emit:i}=f;const r=R(o),s=F();function l(t){t.preventDefault(),r.reset()}function u(t){const a=t,e=r.validate();a.then=e.then.bind(e),a.catch=e.catch.bind(e),a.finally=e.finally.bind(e),i("submit",a),a.defaultPrevented||e.then(c=>{var m;let{valid:d}=c;d&&((m=s.value)==null||m.submit())}),a.preventDefault()}return V(()=>{var t;return y("form",{ref:s,class:["v-form",o.class],style:o.style,novalidate:!0,onReset:l,onSubmit:u},[(t=n.default)==null?void 0:t.call(n,r)])}),P(r,s)}});export{S as V};