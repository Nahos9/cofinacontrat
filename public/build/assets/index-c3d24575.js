import{r as w}from"./validators-be8c4c00.js";import{aa as ee,r as u,c9 as ae,ah as te,a1 as A,c as le,b as e,e as a,F as re,n as oe,a as se,o as k,d as m,f as E,M as s,a3 as d,N as P,s as f,Z as v,x as ie,z as N,a2 as ue}from"./main-6a2f1544.js";import{_ as ne}from"./AppSelect-1c872e0a.js";import{_ as de}from"./AppTextField-64ff589b.js";import{_ as me}from"./DialogCloseBtn-0ca9fab8.js";import{V as pe,p as fe,a as ve}from"./VPagination-0092c9d5.js";import{a as R}from"./theme-sugar-e8a0311e.js";import{u as ce}from"./useApi-4c4343b8.js";import{c as ge}from"./createUrl-4449bd6b.js";import{$ as Ve}from"./api-dd65edb4.js";import{V as g}from"./VCard-1470a4aa.js";import{V as z}from"./VDialog-f45cfaf5.js";import{V}from"./VCardText-23bae783.js";import{V as h}from"./VRow-af72bb7a.js";import{V as L}from"./VDivider-370c9e7d.js";import{V as O}from"./VTooltip-3d210fe7.js";import{V as _e}from"./VForm-04edc871.js";import{V as c}from"./VCol-01037d3b.js";import"./VTextField-2bcae35b.js";import"./VImg-6c5408fa.js";import"./forwardRefs-6ea3df5c.js";import"./VSelect-97592589.js";import"./VList-937448c2.js";import"./VAvatar-2395aa1b.js";import"./VOverlay-a9808ad2.js";import"./VMenu-e43d3d9d.js";import"./VTable-d0bad554.js";import"./filter-a4d0dd7f.js";import"./index-9c720871.js";import"./useAbility-dc299178.js";const ye=m("h2",null,"Liste des utilisateurs",-1),be={class:"ms-2 d-flex gap-4 flex-wrap align-center items-end"},we={class:"d-flex align-center justify-space-between flex-wrap gap-3 pa-5 pt-3"},ke={class:"text-sm text-medium-emphasis mb-0"},xe={class:"d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6"},$e=m("div",{class:"d-flex flex-column justify-center"},null,-1),Ce={class:"d-flex gap-4 align-center flex-wrap"},ra={__name:"index",async setup(Ue){let _,D;const q=ee("userToken").value,n=u(1),x=u(8),$=u(0),y=u(0),i=u(!1),p=u(!1);oe();const B=ae.useToast(),o=u({name:"",email:"",password:"",role:""}),H=[{value:"admin",title:"Administrateur"},{value:"credit_analyst",title:"Analyst crédit"},{value:"head_credit",title:"Head credit"},{value:"credit_admin",title:"Admin crédit"},{value:"operation",title:"Opérations"},{value:"md",title:"MD"},{value:"caf",title:"CAF"},{value:"ca",title:"CA"}],b=u((()=>({email:"",password:"",role:"",name:""}))()),Z=r=>{n.value=r.page},{data:C,execute:T}=([_,D]=te(()=>ce(ge("/user",{query:{page:n}}))),_=await _,D(),_),G=async r=>{await Ve(`user/${r}`,{method:"DELETE"}),B.success("Utilisateur supprimé!!",{position:"top-right"}),T()},J=async()=>{y.value&&R.get(`/api/user/${y.value}`,{headers:{Authorization:`Bearer ${q}`}}).then(({data:r})=>{o.value.name=r.data.user.name,o.value.email=r.data.user.email,o.value.role=r.data.user.profile,o.value.password=r.data.user.password})},K=async()=>{R.put(`/api/user/${y.value}`,{name:o.value.name,full_name:o.value.name,email:o.value.email,profile:o.value.role,password:o.value.password,activated:1,password_change_required:0},{headers:{Authorization:`Bearer ${q}`}}).then(r=>{r.status==200&&(B.success("Utilisateur modifié!!",{position:"top-right"}),p.value=!1,T())})},Q=r=>{y.value=r,J(),p.value=!0},W=A(()=>C.value.data),M=A(()=>C.value.total),S=A(()=>C.value.last_page),X=[{title:"Nom complet",key:"full_name"},{title:"Email",key:"email"},{title:"Profil",key:"profile_fr"},{title:"Actions",key:"actions",sortable:!1}];return(r,l)=>{const j=se("IconBtn"),F=me,U=de,Y=ne;return k(),le(re,null,[e(g,null,{default:a(()=>[e(V,null,{default:a(()=>[e(h,null,{default:a(()=>[e(V,null,{default:a(()=>[ye]),_:1})]),_:1})]),_:1})]),_:1}),e(g,{title:"Utilisateurs",class:"mb-6 mt-2"},{default:a(()=>[m("div",be,[r.$can("create","guarantor")?(k(),E(d,{key:0,color:"primary","prepend-icon":"tabler-plus",to:{name:"user-add"}},{default:a(()=>[s(" Ajouter ")]),_:1})):P("",!0)]),e(L,{class:"mt-4"}),e(f(pe),{"items-per-page":x.value,"onUpdate:itemsPerPage":l[1]||(l[1]=t=>x.value=t),page:n.value,"onUpdate:page":l[2]||(l[2]=t=>n.value=t),headers:X,items:W.value,"items-length":M.value,class:"text-no-wrap","onUpdate:options":Z},{"item.actions":a(({item:t})=>[e(j,{onClick:I=>Q(t.id)},{default:a(()=>[r.$can("update","user")?(k(),E(O,{key:0,activator:"parent",transition:"scroll-x-transition",location:"top"},{default:a(()=>[s("Modifier")]),_:1})):P("",!0),e(v,{icon:"tabler-edit"})]),_:2},1032,["onClick"]),e(j,{onClick:I=>{$.value=t.id,i.value=!0}},{default:a(()=>[r.$can("delete","guarantor")?(k(),E(O,{key:0,activator:"parent",transition:"scroll-x-transition",location:"top",onClick:I=>{$.value=t.id,i.value=!0}},{default:a(()=>[s("Supprimer")]),_:2},1032,["onClick"])):P("",!0),e(v,{icon:"tabler-trash",color:"error"})]),_:2},1032,["onClick"])]),bottom:a(()=>[e(L),m("div",we,[m("p",ke,ie(f(fe)({page:n.value,itemsPerPage:x.value},M.value)),1),e(ve,{modelValue:n.value,"onUpdate:modelValue":l[0]||(l[0]=t=>n.value=t),length:S.value,"total-visible":r.$vuetify.display.xs?1:Math.min(S.value,5)},{prev:a(t=>[e(d,N({variant:"tonal",color:"default"},t,{icon:!1}),{default:a(()=>[e(v,{start:"",icon:"tabler-arrow-left"}),s(" Précedent ")]),_:2},1040)]),next:a(t=>[e(d,N({variant:"tonal",color:"default"},t,{icon:!1}),{default:a(()=>[s(" Suivant "),e(v,{end:"",icon:"tabler-arrow-right"})]),_:2},1040)]),_:1},8,["modelValue","length","total-visible"])])]),_:1},8,["items-per-page","page","items","items-length"])]),_:1}),e(z,{modelValue:i.value,"onUpdate:modelValue":l[6]||(l[6]=t=>i.value=t),class:"v-dialog-sm"},{default:a(()=>[e(F,{onClick:l[3]||(l[3]=t=>i.value=!i.value)}),e(g,{title:"Suppression"},{default:a(()=>[e(V,null,{default:a(()=>[s(" Etes vous sûr de vouloir supprimer cet utilisateur? ")]),_:1}),e(V,{class:"d-flex justify-end gap-3 flex-wrap"},{default:a(()=>[e(d,{color:"secondary",variant:"tonal",onClick:l[4]||(l[4]=t=>i.value=!1)},{default:a(()=>[s(" Annuler ")]),_:1}),e(d,{onClick:l[5]||(l[5]=t=>{G($.value),i.value=!1})},{default:a(()=>[s(" Supprimer ")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"]),e(z,{modelValue:p.value,"onUpdate:modelValue":l[12]||(l[12]=t=>p.value=t),class:"v-dialog-sm"},{default:a(()=>[e(F,{onClick:l[7]||(l[7]=t=>p.value=!p.value)}),e(g,{title:"Modification"},{default:a(()=>[e(_e,{ref:"refForm",onSubmit:ue(K,["prevent"])},{default:a(()=>[e(h,null,{default:a(()=>[e(c,{md:"12"},{default:a(()=>[e(g,{class:"mb-6",title:"Information sur l'utilisateur"},{default:a(()=>[e(V,null,{default:a(()=>[e(h,null,{default:a(()=>[e(c,{cols:"12",md:"6",lg:"4"},{default:a(()=>[e(U,{modelValue:o.value.name,"onUpdate:modelValue":l[8]||(l[8]=t=>o.value.name=t),"error-messages":b.value.name,label:"Votre nom",rules:["requiredValidator"in r?r.requiredValidator:f(w)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(c,{cols:"12",md:"6",lg:"4"},{default:a(()=>[e(U,{modelValue:o.value.email,"onUpdate:modelValue":l[9]||(l[9]=t=>o.value.email=t),"error-messages":b.value.email,label:"Votre e-mail",rules:["requiredValidator"in r?r.requiredValidator:f(w)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(c,{cols:"12",md:"6",lg:"4"},{default:a(()=>[e(Y,{modelValue:o.value.role,"onUpdate:modelValue":l[10]||(l[10]=t=>o.value.role=t),items:H,"error-messages":b.value.role,label:"Rôle",placeholder:"Ex: Administrateur",rules:["requiredValidator"in r?r.requiredValidator:f(w)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(c,{cols:"12",md:"6",lg:"4"},{default:a(()=>[e(U,{modelValue:o.value.password,"onUpdate:modelValue":l[11]||(l[11]=t=>o.value.password=t),"error-messages":b.value.password,label:"Mot de passe",placeholder:"Ex: Passer@1234",rules:["requiredValidator"in r?r.requiredValidator:f(w)]},null,8,["modelValue","error-messages","rules"])]),_:1})]),_:1})]),_:1})]),_:1})]),_:1}),e(c,{cols:"12"},{default:a(()=>[m("div",xe,[$e,m("div",Ce,[e(d,{type:"reset",variant:"tonal",color:"primary"},{default:a(()=>[e(v,{start:"",icon:"tabler-circle-minus"}),s(" Effacer ")]),_:1}),e(d,{type:"submit",class:"me-3"},{default:a(()=>[s(" Enregistrer "),e(v,{end:"",icon:"tabler-checkbox"})]),_:1})])])]),_:1})]),_:1})]),_:1},512)]),_:1})]),_:1},8,["modelValue"])],64)}}};export{ra as default};