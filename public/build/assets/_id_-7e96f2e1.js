import{r as u}from"./validators-be8c4c00.js";import{ah as T,a1 as k,r as E,c as N,b as e,e as o,a2 as P,E as $,n as j,o as F,a3 as y,s as a,M as v,d as p,Z as w,C as L,D as S,ai as D}from"./main-35f434e5.js";import{_ as B}from"./AppDateTimePicker-cb72b21d.js";import{_ as R}from"./AppTextField-4144e217.js";import{_ as G}from"./AppSelect-f486f450.js";import{c as O}from"./createUrl-376a4625.js";import{u as Z}from"./useApi-f6bf8dd7.js";import{$ as z}from"./api-bc7c6e86.js";import{_ as H}from"./_plugin-vue_export-helper-c27b6911.js";import{V as J}from"./VForm-b1b04516.js";import{V as b}from"./VRow-5bf9819c.js";import{V as d}from"./VCol-bbc947c1.js";import{V as K}from"./VCard-de226d5c.js";import{V as Q}from"./VCardText-2ef94eba.js";import"./VTextField-31ac9b36.js";import"./VImg-fb08db81.js";import"./forwardRefs-6ea3df5c.js";import"./VSelect-8eaa7a4a.js";import"./VList-a84ed864.js";import"./VAvatar-5cd70722.js";import"./VDivider-2199d20e.js";import"./VOverlay-6080deb7.js";import"./VMenu-ad7875d9.js";import"./index-9c720871.js";import"./useAbility-597cc743.js";const U=f=>(L("data-v-2d2ac807"),f=f(),S(),f),W=U(()=>p("div",{class:"d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6"},[p("div",{class:"d-flex flex-column justify-center"},[p("h4",{class:"text-h4 font-weight-medium"}," Modifer une caution ")])],-1)),X={class:"d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6"},Y=U(()=>p("div",{class:"d-flex flex-column justify-center"},null,-1)),ee={class:"d-flex gap-4 align-center flex-wrap"},ae={__name:"[id]",async setup(f){let c,h;const x=$(),m=j(),{data:C}=([c,h]=T(()=>Z(O(`/guarantor/${m.params.id}`,{query:{with_notification:1}}))),c=await c,h(),c),t=k(()=>C.value.data.guarantor),q=()=>({civility:"",first_name:"",last_name:"",birth_date:"",birth_place:"",nationality:"",home_address:"",type_of_identity_document:"",number_of_identity_document:"",date_of_issue_of_identity_document:"",function:"",phone_number:""}),s=E(q()),A=[{value:"Mr",title:"Mr"},{value:"Mme",title:"Mme"},{value:"Mlle",title:"Mlle"}],M=[{value:"cni",title:"Carte d'identité nationale"},{value:"passport",title:"Passeport"},{value:"residence_certificate",title:"Certificat de résidence"},{value:"driving_licence",title:"Permis de conduire"}],g=E(),I=()=>{var r;(r=g.value)==null||r.validate().then(async({valid:l})=>{if(l){const _=await z(`/guarantor/${m.params.id}`,{method:"PUT",body:{notification_id:m.params.notification_id,civility:t.value.civility,first_name:t.value.first_name,last_name:t.value.last_name,birth_date:t.value.birth_date,birth_place:t.value.birth_place,nationality:t.value.nationality,home_address:t.value.home_address,type_of_identity_document:t.value.type_of_identity_document,number_of_identity_document:t.value.number_of_identity_document,date_of_issue_of_identity_document:t.value.date_of_issue_of_identity_document,function:t.value.function,phone_number:t.value.phone_number}});if(s.value=q(),_.status==200)x.push({name:"notification-notification_id-guarantor",params:{notification_id:m.params.notification_id}});else for(const n in _.errors)_.errors[n].forEach(V=>{s.value[n]+=V+`
`});D(()=>{var n;(n=g.value)==null||n.resetValidation()})}})};return(r,l)=>{const _=G,n=R,V=B;return F(),N("div",null,[W,e(J,{ref_key:"refForm",ref:g,onSubmit:P(I,["prevent"])},{default:o(()=>[e(b,null,{default:o(()=>[e(d,{cols:"11"},{default:o(()=>[e(y,{"prepend-icon":"tabler-arrow-left",to:{name:"notification-notification_id-guarantor",params:{notification_id:a(m).params.notification_id}}},{default:o(()=>[v(" Cautions ")]),_:1},8,["to"])]),_:1}),e(d,{cols:"1"},{default:o(()=>[e(y,{"prepend-icon":"tabler-eye",to:{name:"notification-notification_id-guarantor-id",params:{notification_id:a(m).params.notification_id,id:a(m).params.id}}},{default:o(()=>[v(" Voir ")]),_:1},8,["to"])]),_:1})]),_:1}),e(b,null,{default:o(()=>[e(d,{md:"12"},{default:o(()=>[e(K,{class:"mb-6",title:"Information de la caution"},{default:o(()=>[e(Q,null,{default:o(()=>[e(b,null,{default:o(()=>[e(d,{cols:"12",md:"6",lg:"4"},{default:o(()=>[e(_,{modelValue:a(t).civility,"onUpdate:modelValue":l[0]||(l[0]=i=>a(t).civility=i),items:A,"error-messages":s.value.civility,label:"Civilité",placeholder:"Ex: Mr",rules:["requiredValidator"in r?r.requiredValidator:a(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(d,{cols:"12",md:"6",lg:"4"},{default:o(()=>[e(n,{modelValue:a(t).first_name,"onUpdate:modelValue":l[1]||(l[1]=i=>a(t).first_name=i),"error-messages":s.value.first_name,label:"Prénom",placeholder:"Ex: Cesar",rules:["requiredValidator"in r?r.requiredValidator:a(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(d,{cols:"12",md:"6",lg:"4"},{default:o(()=>[e(n,{modelValue:a(t).last_name,"onUpdate:modelValue":l[2]||(l[2]=i=>a(t).last_name=i),"error-messages":s.value.last_name,label:"Nom",placeholder:"Ex: DEFALGO",rules:["requiredValidator"in r?r.requiredValidator:a(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(d,{cols:"12",md:"6",lg:"4"},{default:o(()=>[e(V,{modelValue:a(t).birth_date,"onUpdate:modelValue":l[3]||(l[3]=i=>a(t).birth_date=i),"error-messages":s.value.birth_date,label:"Date de naissance",placeholder:"Ex: 2000-12-12",rules:["requiredValidator"in r?r.requiredValidator:a(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(d,{cols:"12",md:"6",lg:"4"},{default:o(()=>[e(n,{modelValue:a(t).birth_place,"onUpdate:modelValue":l[4]||(l[4]=i=>a(t).birth_place=i),"error-messages":s.value.birth_place,label:"Lieu de naissance",placeholder:"Ex: Lomé",rules:["requiredValidator"in r?r.requiredValidator:a(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(d,{cols:"12",md:"6",lg:"4"},{default:o(()=>[e(n,{modelValue:a(t).nationality,"onUpdate:modelValue":l[5]||(l[5]=i=>a(t).nationality=i),"error-messages":s.value.nationality,label:"Nationalité",placeholder:"Ex: Togolaise",rules:["requiredValidator"in r?r.requiredValidator:a(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(d,{cols:"12",md:"6",lg:"4"},{default:o(()=>[e(n,{modelValue:a(t).home_address,"onUpdate:modelValue":l[6]||(l[6]=i=>a(t).home_address=i),"error-messages":s.value.home_address,label:"Addresse du domicile",placeholder:"Ex: Adewi, Lomé",rules:["requiredValidator"in r?r.requiredValidator:a(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(d,{cols:"12",md:"6",lg:"4"},{default:o(()=>[e(_,{modelValue:a(t).type_of_identity_document,"onUpdate:modelValue":l[7]||(l[7]=i=>a(t).type_of_identity_document=i),items:M,"error-messages":s.value.type_of_identity_document,label:"Type de la pièce d'identité",placeholder:"Ex: Passeport",rules:["requiredValidator"in r?r.requiredValidator:a(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(d,{cols:"12",md:"6",lg:"4"},{default:o(()=>[e(n,{modelValue:a(t).number_of_identity_document,"onUpdate:modelValue":l[8]||(l[8]=i=>a(t).number_of_identity_document=i),"error-messages":s.value.number_of_identity_document,label:"Numéro de la pièce d'identité",placeholder:"Ex: 251012345678",rules:["requiredValidator"in r?r.requiredValidator:a(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(d,{cols:"12",md:"6",lg:"4"},{default:o(()=>[e(V,{modelValue:a(t).date_of_issue_of_identity_document,"onUpdate:modelValue":l[9]||(l[9]=i=>a(t).date_of_issue_of_identity_document=i),"error-messages":s.value.date_of_issue_of_identity_document,label:"Date de délivrance de la pièce d'identité",placeholder:"Ex: 2022-01-01",rules:["requiredValidator"in r?r.requiredValidator:a(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(d,{cols:"12",md:"6",lg:"4"},{default:o(()=>[e(n,{modelValue:a(t).function,"onUpdate:modelValue":l[10]||(l[10]=i=>a(t).function=i),"error-messages":s.value.function,label:"Fonction",placeholder:"Ex: Agent de change",rules:["requiredValidator"in r?r.requiredValidator:a(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(d,{cols:"12",md:"6",lg:"4"},{default:o(()=>[e(n,{modelValue:a(t).phone_number,"onUpdate:modelValue":l[11]||(l[11]=i=>a(t).phone_number=i),"error-messages":s.value.phone_number,label:"Numéro de téléphone",placeholder:"Ex: +228 96 96 96 96",rules:["requiredValidator"in r?r.requiredValidator:a(u)]},null,8,["modelValue","error-messages","rules"])]),_:1})]),_:1})]),_:1})]),_:1})]),_:1}),e(d,{cols:"12"},{default:o(()=>[p("div",X,[Y,p("div",ee,[e(y,{type:"reset",variant:"tonal",color:"primary"},{default:o(()=>[e(w,{start:"",icon:"tabler-circle-minus"}),v(" Effacer ")]),_:1}),e(y,{type:"submit",class:"me-3"},{default:o(()=>[v(" Enregistrer "),e(w,{end:"",icon:"tabler-checkbox"})]),_:1})])])]),_:1})]),_:1})]),_:1},512)])}}},Ce=H(ae,[["__scopeId","data-v-2d2ac807"]]);export{Ce as default};
