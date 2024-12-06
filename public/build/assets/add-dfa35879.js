import{r as n}from"./validators-be8c4c00.js";import{r as V,c as C,b as a,e as o,a2 as M,E as T,n as A,o as I,s as u,d as _,a3 as y,M as g,Z as h,C as j,D as k,ai as N}from"./main-a1bb8d1c.js";import{_ as S}from"./AppDateTimePicker-127836d3.js";import{_ as F}from"./AppTextField-6136aa1e.js";import{_ as L}from"./AppSelect-ec953df4.js";import{$ as P}from"./api-0fd30a7b.js";import{_ as D}from"./_plugin-vue_export-helper-c27b6911.js";import{V as $}from"./VForm-0ba8db0b.js";import{V as q}from"./VRow-4fd62cc5.js";import{V as s}from"./VCol-c9fbba03.js";import{V as B}from"./VCard-0a0db0fa.js";import{V as R}from"./VCardText-ec067fb4.js";import"./VTextField-251e47e8.js";import"./VImg-c3c51305.js";import"./forwardRefs-6ea3df5c.js";import"./VSelect-00885f47.js";import"./VList-63aa9283.js";import"./VAvatar-28a02eb0.js";import"./VDivider-bdae5753.js";import"./VOverlay-5a96f554.js";import"./VMenu-e4df9f05.js";import"./index-9c720871.js";import"./useAbility-c9217b08.js";const G=f=>(j("data-v-8e1e0dae"),f=f(),k(),f),O=G(()=>_("div",{class:"d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6"},[_("div",{class:"d-flex flex-column justify-center"},[_("h4",{class:"text-h4 font-weight-medium"}," Ajouter une caution ")])],-1)),Z={class:"d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6"},z={class:"d-flex flex-column justify-center"},H={class:"d-flex gap-4 align-center flex-wrap"},J={__name:"add",setup(f){const E=T(),c=A(),e=V({civility:null,first_name:null,last_name:null,birth_date:null,birth_place:null,nationality:null,home_address:null,type_of_identity_document:null,number_of_identity_document:null,date_of_issue_of_identity_document:null,function:null,phone_number:null}),b=()=>({civility:"",first_name:"",last_name:"",birth_date:"",birth_place:"",nationality:"",home_address:"",type_of_identity_document:"",number_of_identity_document:"",date_of_issue_of_identity_document:"",function:"",phone_number:""}),i=V(b()),w=[{value:"Mr",title:"Mr"},{value:"Mme",title:"Mme"},{value:"Mlle",title:"Mlle"}],x=[{value:"cni",title:"Carte d'identité nationale"},{value:"passport",title:"Passeport"},{value:"residence_certificate",title:"Certificat de résidence"},{value:"driving_licence",title:"Permis de conduire"}],v=V(),U=()=>{var l;(l=v.value)==null||l.validate().then(async({valid:t})=>{if(t){const m=await P("/guarantor",{method:"POST",body:{notification_id:c.params.notification_id,civility:e.value.civility,first_name:e.value.first_name,last_name:e.value.last_name,birth_date:e.value.birth_date,birth_place:e.value.birth_place,nationality:e.value.nationality,home_address:e.value.home_address,type_of_identity_document:e.value.type_of_identity_document,number_of_identity_document:e.value.number_of_identity_document,date_of_issue_of_identity_document:e.value.date_of_issue_of_identity_document,function:e.value.function,phone_number:e.value.phone_number}});if(i.value=b(),m.status==201)E.push({name:"notification-notification_id-guarantor",params:{notification_id:c.params.notification_id}});else for(const d in m.errors)m.errors[d].forEach(p=>{i.value[d]+=p+`
`});N(()=>{var d;(d=v.value)==null||d.resetValidation()})}})};return(l,t)=>{const m=L,d=F,p=S;return I(),C("div",null,[O,a($,{ref_key:"refForm",ref:v,onSubmit:M(U,["prevent"])},{default:o(()=>[a(q,null,{default:o(()=>[a(s,{md:"12"},{default:o(()=>[a(B,{class:"mb-6",title:"Information de la caution"},{default:o(()=>[a(R,null,{default:o(()=>[a(q,null,{default:o(()=>[a(s,{cols:"12",md:"6",lg:"4"},{default:o(()=>[a(m,{modelValue:e.value.civility,"onUpdate:modelValue":t[0]||(t[0]=r=>e.value.civility=r),items:w,"error-messages":i.value.civility,label:"Civilité",placeholder:"Ex: Mr",rules:["requiredValidator"in l?l.requiredValidator:u(n)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(s,{cols:"12",md:"6",lg:"4"},{default:o(()=>[a(d,{modelValue:e.value.first_name,"onUpdate:modelValue":t[1]||(t[1]=r=>e.value.first_name=r),"error-messages":i.value.first_name,label:"Prénom",placeholder:"Ex: Cesar",rules:["requiredValidator"in l?l.requiredValidator:u(n)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(s,{cols:"12",md:"6",lg:"4"},{default:o(()=>[a(d,{modelValue:e.value.last_name,"onUpdate:modelValue":t[2]||(t[2]=r=>e.value.last_name=r),"error-messages":i.value.last_name,label:"Nom",placeholder:"Ex: DEFALGO",rules:["requiredValidator"in l?l.requiredValidator:u(n)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(s,{cols:"12",md:"6",lg:"4"},{default:o(()=>[a(p,{modelValue:e.value.birth_date,"onUpdate:modelValue":t[3]||(t[3]=r=>e.value.birth_date=r),"error-messages":i.value.birth_date,label:"Date de naissance",placeholder:"Ex: 2000-12-12",rules:["requiredValidator"in l?l.requiredValidator:u(n)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(s,{cols:"12",md:"6",lg:"4"},{default:o(()=>[a(d,{modelValue:e.value.birth_place,"onUpdate:modelValue":t[4]||(t[4]=r=>e.value.birth_place=r),"error-messages":i.value.birth_place,label:"Lieu de naissance",placeholder:"Ex: Lomé",rules:["requiredValidator"in l?l.requiredValidator:u(n)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(s,{cols:"12",md:"6",lg:"4"},{default:o(()=>[a(d,{modelValue:e.value.nationality,"onUpdate:modelValue":t[5]||(t[5]=r=>e.value.nationality=r),"error-messages":i.value.nationality,label:"Nationalité",placeholder:"Ex: Togolaise",rules:["requiredValidator"in l?l.requiredValidator:u(n)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(s,{cols:"12",md:"6",lg:"4"},{default:o(()=>[a(d,{modelValue:e.value.home_address,"onUpdate:modelValue":t[6]||(t[6]=r=>e.value.home_address=r),"error-messages":i.value.home_address,label:"Addresse du domicile",placeholder:"Ex: Adewi, Lomé",rules:["requiredValidator"in l?l.requiredValidator:u(n)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(s,{cols:"12",md:"6",lg:"4"},{default:o(()=>[a(m,{modelValue:e.value.type_of_identity_document,"onUpdate:modelValue":t[7]||(t[7]=r=>e.value.type_of_identity_document=r),items:x,"error-messages":i.value.type_of_identity_document,label:"Type de la pièce d'identité",placeholder:"Ex: Passeport",rules:["requiredValidator"in l?l.requiredValidator:u(n)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(s,{cols:"12",md:"6",lg:"4"},{default:o(()=>[a(d,{modelValue:e.value.number_of_identity_document,"onUpdate:modelValue":t[8]||(t[8]=r=>e.value.number_of_identity_document=r),"error-messages":i.value.number_of_identity_document,label:"Numéro de la pièce d'identité",placeholder:"Ex: 251012345678",rules:["requiredValidator"in l?l.requiredValidator:u(n)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(s,{cols:"12",md:"6",lg:"4"},{default:o(()=>[a(p,{modelValue:e.value.date_of_issue_of_identity_document,"onUpdate:modelValue":t[9]||(t[9]=r=>e.value.date_of_issue_of_identity_document=r),"error-messages":i.value.date_of_issue_of_identity_document,label:"Date de délivrance de la pièce d'identité",placeholder:"Ex: 2022-01-01",rules:["requiredValidator"in l?l.requiredValidator:u(n)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(s,{cols:"12",md:"6",lg:"4"},{default:o(()=>[a(d,{modelValue:e.value.function,"onUpdate:modelValue":t[10]||(t[10]=r=>e.value.function=r),"error-messages":i.value.function,label:"Fonction",placeholder:"Ex: Agent de change",rules:["requiredValidator"in l?l.requiredValidator:u(n)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(s,{cols:"12",md:"6",lg:"4"},{default:o(()=>[a(d,{modelValue:e.value.phone_number,"onUpdate:modelValue":t[11]||(t[11]=r=>e.value.phone_number=r),"error-messages":i.value.phone_number,label:"Numéro de téléphone",placeholder:"Ex: +228 96 96 96 96",rules:["requiredValidator"in l?l.requiredValidator:u(n)]},null,8,["modelValue","error-messages","rules"])]),_:1})]),_:1})]),_:1})]),_:1})]),_:1}),a(s,{cols:"12"},{default:o(()=>[_("div",Z,[_("div",z,[a(y,{"prepend-icon":"tabler-arrow-left",to:{name:"notification-notification_id-guarantor",params:{notification_id:u(c).params.notification_id}}},{default:o(()=>[g(" Cautions ")]),_:1},8,["to"])]),_("div",H,[a(y,{type:"reset",variant:"tonal",color:"primary"},{default:o(()=>[a(h,{start:"",icon:"tabler-circle-minus"}),g(" Effacer ")]),_:1}),a(y,{type:"submit",class:"me-3"},{default:o(()=>[g(" Enregistrer "),a(h,{end:"",icon:"tabler-checkbox"})]),_:1})])])]),_:1})]),_:1})]),_:1},512)])}}},ye=D(J,[["__scopeId","data-v-8e1e0dae"]]);export{ye as default};