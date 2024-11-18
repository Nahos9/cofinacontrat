import{r as m}from"./validators-be8c4c00.js";import{r as p,aa as k,c9 as C,c as F,b as e,e as a,a2 as T,F as j,E as B,o as U,s as r,d as n,a3 as _,Z as v,M as g}from"./main-35f434e5.js";import{_ as M}from"./AppSelect-f486f450.js";import{_ as R}from"./AppTextField-4144e217.js";import"./api-bc7c6e86.js";import{a as $}from"./theme-sugar-e8a0311e.js";import{V as b}from"./VCard-de226d5c.js";import{V as N}from"./VForm-b1b04516.js";import{V as f}from"./VCardText-2ef94eba.js";import{V as c}from"./VRow-5bf9819c.js";import{V as i}from"./VCol-bbc947c1.js";import"./VTextField-31ac9b36.js";import"./VImg-fb08db81.js";import"./forwardRefs-6ea3df5c.js";import"./VSelect-8eaa7a4a.js";import"./VList-a84ed864.js";import"./VAvatar-5cd70722.js";import"./VDivider-2199d20e.js";import"./VOverlay-6080deb7.js";import"./VMenu-ad7875d9.js";import"./index-9c720871.js";import"./useAbility-597cc743.js";const S=n("h2",null,"Ajouter un utilisateur",-1),D={class:"d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6"},I=n("div",{class:"d-flex flex-column justify-center"},null,-1),P={class:"d-flex gap-4 align-center flex-wrap"},ne={__name:"add",setup(z){const l=p({email:"",password:"",role:"",name:""}),w=()=>({email:"",password:"",role:"",name:""}),y=[{value:"admin",title:"Administrateur"},{value:"credit_analyst",title:"Analyst crédit"},{value:"head_credit",title:"Head credit"},{value:"credit_admin",title:"Admin crédit"},{value:"operation",title:"Opérations"},{value:"md",title:"MD"},{value:"caf",title:"CAF"},{value:"ca",title:"CA"}],h=B(),q=k("userToken").value,x=C.useToast(),V=p(),A=()=>{var t;(t=V.value)==null||t.validate().then(async({valid:s})=>{if(s){const{data:u}=await $.post("/api/user",{email:l.value.email,name:l.value.name,password:l.value.password,full_name:l.value.name,profile:l.value.role,activated:1,password_change_required:0},{headers:{Authorization:`Bearer ${q}`}});u.status==201&&(x.success("Utilisateur ajouté!!",{position:"top-right"}),h.push("/user"))}})},d=p(w());return(t,s)=>{const u=R,E=M;return U(),F(j,null,[e(b,{class:"mb-6"},{default:a(()=>[e(f,null,{default:a(()=>[e(c,null,{default:a(()=>[e(f,null,{default:a(()=>[S]),_:1})]),_:1})]),_:1})]),_:1}),e(N,{ref_key:"refForm",ref:V,onSubmit:T(A,["prevent"])},{default:a(()=>[e(c,null,{default:a(()=>[e(i,{md:"12"},{default:a(()=>[e(b,{class:"mb-6",title:"Information sur l'utilisateur"},{default:a(()=>[e(f,null,{default:a(()=>[e(c,null,{default:a(()=>[e(i,{cols:"12",md:"6",lg:"4"},{default:a(()=>[e(u,{modelValue:r(l).name,"onUpdate:modelValue":s[0]||(s[0]=o=>r(l).name=o),"error-messages":r(d).name,label:"Votre nom",rules:["requiredValidator"in t?t.requiredValidator:r(m)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(i,{cols:"12",md:"6",lg:"4"},{default:a(()=>[e(u,{modelValue:r(l).email,"onUpdate:modelValue":s[1]||(s[1]=o=>r(l).email=o),"error-messages":r(d).email,label:"Votre e-mail",rules:["requiredValidator"in t?t.requiredValidator:r(m)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(i,{cols:"12",md:"6",lg:"4"},{default:a(()=>[e(E,{modelValue:r(l).role,"onUpdate:modelValue":s[2]||(s[2]=o=>r(l).role=o),items:y,"error-messages":r(d).role,label:"Rôle",placeholder:"Ex: Administrateur",rules:["requiredValidator"in t?t.requiredValidator:r(m)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(i,{cols:"12",md:"6",lg:"4"},{default:a(()=>[e(u,{modelValue:r(l).password,"onUpdate:modelValue":s[3]||(s[3]=o=>r(l).password=o),"error-messages":r(d).password,label:"Mot de passe",placeholder:"Ex: Passer@1234",rules:["requiredValidator"in t?t.requiredValidator:r(m)]},null,8,["modelValue","error-messages","rules"])]),_:1})]),_:1})]),_:1})]),_:1})]),_:1}),e(i,{cols:"12"},{default:a(()=>[n("div",D,[I,n("div",P,[e(_,{type:"reset",variant:"tonal",color:"primary"},{default:a(()=>[e(v,{start:"",icon:"tabler-circle-minus"}),g(" Effacer ")]),_:1}),e(_,{type:"submit",class:"me-3"},{default:a(()=>[g(" Enregistrer "),e(v,{end:"",icon:"tabler-checkbox"})]),_:1})])])]),_:1})]),_:1})]),_:1},512)],64)}}};export{ne as default};
