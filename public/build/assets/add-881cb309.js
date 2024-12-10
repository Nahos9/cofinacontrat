import{r as n}from"./validators-be8c4c00.js";import{r as V,c as M,b as l,e as o,a2 as S,E as C,n as j,o as A,s,d as _,a3 as y,M as b,Z as h,C as I,D as T,ai as k}from"./main-6a2f1544.js";import{_ as D}from"./AppDateTimePicker-864bad83.js";import{_ as N}from"./AppTextField-64ff589b.js";import{_ as O}from"./AppSelect-1c872e0a.js";import{$ as P}from"./api-dd65edb4.js";import{_ as F}from"./_plugin-vue_export-helper-c27b6911.js";import{V as R}from"./VForm-04edc871.js";import{V as q}from"./VRow-af72bb7a.js";import{V as u}from"./VCol-01037d3b.js";import{V as $}from"./VCard-1470a4aa.js";import{V as B}from"./VCardText-23bae783.js";import"./VTextField-2bcae35b.js";import"./VImg-6c5408fa.js";import"./forwardRefs-6ea3df5c.js";import"./VSelect-97592589.js";import"./VList-937448c2.js";import"./VAvatar-2395aa1b.js";import"./VDivider-370c9e7d.js";import"./VOverlay-a9808ad2.js";import"./VMenu-e43d3d9d.js";import"./index-9c720871.js";import"./useAbility-dc299178.js";const G=f=>(I("data-v-1fbf8a78"),f=f(),T(),f),L=G(()=>_("div",{class:"d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6"},[_("div",{class:"d-flex flex-column justify-center"},[_("h4",{class:"text-h4 font-weight-medium"},"Ajouter une caution")])],-1)),J={class:"d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6"},Z={class:"d-flex flex-column justify-center"},z={class:"d-flex gap-4 align-center flex-wrap"},H={__name:"add",setup(f){const E=C(),c=j(),e=V({civility:null,first_name:null,last_name:null,birth_date:null,birth_place:null,nationality:null,home_address:null,type_of_identity_document:null,office_delivery:null,number_of_identity_document:null,date_of_issue_of_identity_document:null,function:null,phone_number:null}),g=()=>({civility:"",first_name:"",last_name:"",birth_date:"",birth_place:"",nationality:"",home_address:"",office_delivery:"",type_of_identity_document:"",number_of_identity_document:"",date_of_issue_of_identity_document:"",function:"",phone_number:""}),i=V(g()),U=[{value:"Mr",title:"Mr"},{value:"Mme",title:"Mme"},{value:"Mlle",title:"Mlle"}],w=[{value:"cni",title:"Carte d'identité nationale"},{value:"passport",title:"Passeport"},{value:"residence_certificate",title:"Certificat de résidence"},{value:"driving_licence",title:"Permis de conduire"},{value:"carte_sej",title:"Carte de séjour"},{value:"recep",title:"Récépissé de la carte nationale d’identité"}],v=V(),x=()=>{var a;(a=v.value)==null||a.validate().then(async({valid:r})=>{if(r){const m=await P("/guarantor",{method:"POST",body:{contract_id:c.params.contract_id,civility:e.value.civility,first_name:e.value.first_name,last_name:e.value.last_name,birth_date:e.value.birth_date,birth_place:e.value.birth_place,nationality:e.value.nationality,home_address:e.value.home_address,type_of_identity_document:e.value.type_of_identity_document,number_of_identity_document:e.value.number_of_identity_document,office_delivery:e.value.office_delivery,date_of_issue_of_identity_document:e.value.date_of_issue_of_identity_document,function:e.value.function,phone_number:e.value.phone_number}});if(i.value=g(),m.status==201)E.push({name:"contract-contract_id-guarantor",params:{contract_id:c.params.contract_id}});else for(const d in m.errors)m.errors[d].forEach(p=>{i.value[d]+=p+`
`});k(()=>{var d;(d=v.value)==null||d.resetValidation()})}})};return(a,r)=>{const m=O,d=N,p=D;return A(),M("div",null,[L,l(R,{ref_key:"refForm",ref:v,onSubmit:S(x,["prevent"])},{default:o(()=>[l(q,null,{default:o(()=>[l(u,{md:"12"},{default:o(()=>[l($,{class:"mb-6",title:"Information de la caution"},{default:o(()=>[l(B,null,{default:o(()=>[l(q,null,{default:o(()=>[l(u,{cols:"12",md:"6",lg:"4"},{default:o(()=>[l(m,{modelValue:e.value.civility,"onUpdate:modelValue":r[0]||(r[0]=t=>e.value.civility=t),items:U,"error-messages":i.value.civility,label:"Civilité",placeholder:"Ex: Mr",rules:["requiredValidator"in a?a.requiredValidator:s(n)]},null,8,["modelValue","error-messages","rules"])]),_:1}),l(u,{cols:"12",md:"6",lg:"4"},{default:o(()=>[l(d,{modelValue:e.value.first_name,"onUpdate:modelValue":r[1]||(r[1]=t=>e.value.first_name=t),"error-messages":i.value.first_name,label:"Prénom",placeholder:"Ex: Jean",rules:["requiredValidator"in a?a.requiredValidator:s(n)]},null,8,["modelValue","error-messages","rules"])]),_:1}),l(u,{cols:"12",md:"6",lg:"4"},{default:o(()=>[l(d,{modelValue:e.value.last_name,"onUpdate:modelValue":r[2]||(r[2]=t=>e.value.last_name=t),"error-messages":i.value.last_name,label:"Nom",placeholder:"Ex: MOUSSAVOU MOUSSAVOU",rules:["requiredValidator"in a?a.requiredValidator:s(n)]},null,8,["modelValue","error-messages","rules"])]),_:1}),l(u,{cols:"12",md:"6",lg:"4"},{default:o(()=>[l(p,{modelValue:e.value.birth_date,"onUpdate:modelValue":r[3]||(r[3]=t=>e.value.birth_date=t),"error-messages":i.value.birth_date,label:"Date de naissance",placeholder:"Ex: 2000-12-12",rules:["requiredValidator"in a?a.requiredValidator:s(n)]},null,8,["modelValue","error-messages","rules"])]),_:1}),l(u,{cols:"12",md:"6",lg:"4"},{default:o(()=>[l(d,{modelValue:e.value.birth_place,"onUpdate:modelValue":r[4]||(r[4]=t=>e.value.birth_place=t),"error-messages":i.value.birth_place,label:"Lieu de naissance",placeholder:"Ex: Libreville",rules:["requiredValidator"in a?a.requiredValidator:s(n)]},null,8,["modelValue","error-messages","rules"])]),_:1}),l(u,{cols:"12",md:"6",lg:"4"},{default:o(()=>[l(d,{modelValue:e.value.nationality,"onUpdate:modelValue":r[5]||(r[5]=t=>e.value.nationality=t),"error-messages":i.value.nationality,label:"Nationalité",placeholder:"Ex: Gabonaise",rules:["requiredValidator"in a?a.requiredValidator:s(n)]},null,8,["modelValue","error-messages","rules"])]),_:1}),l(u,{cols:"12",md:"6",lg:"4"},{default:o(()=>[l(d,{modelValue:e.value.home_address,"onUpdate:modelValue":r[6]||(r[6]=t=>e.value.home_address=t),"error-messages":i.value.home_address,label:"Addresse du domicile",placeholder:"Ex: Owondo",rules:["requiredValidator"in a?a.requiredValidator:s(n)]},null,8,["modelValue","error-messages","rules"])]),_:1}),l(u,{cols:"12",md:"6",lg:"4"},{default:o(()=>[l(m,{modelValue:e.value.type_of_identity_document,"onUpdate:modelValue":r[7]||(r[7]=t=>e.value.type_of_identity_document=t),items:w,"error-messages":i.value.type_of_identity_document,label:"Type de la pièce d'identité",placeholder:"Ex: Passeport",rules:["requiredValidator"in a?a.requiredValidator:s(n)]},null,8,["modelValue","error-messages","rules"])]),_:1}),l(u,{cols:"12",md:"6",lg:"4"},{default:o(()=>[l(d,{modelValue:e.value.number_of_identity_document,"onUpdate:modelValue":r[8]||(r[8]=t=>e.value.number_of_identity_document=t),"error-messages":i.value.number_of_identity_document,label:"Numéro de la pièce d'identité",placeholder:"Ex: GA-265788",rules:["requiredValidator"in a?a.requiredValidator:s(n)]},null,8,["modelValue","error-messages","rules"])]),_:1}),l(u,{cols:"12",md:"6",lg:"4"},{default:o(()=>[l(p,{modelValue:e.value.date_of_issue_of_identity_document,"onUpdate:modelValue":r[9]||(r[9]=t=>e.value.date_of_issue_of_identity_document=t),"error-messages":i.value.date_of_issue_of_identity_document,label:"Date de délivrance de la pièce d'identité",placeholder:"Ex: 2022-01-01",rules:["requiredValidator"in a?a.requiredValidator:s(n)]},null,8,["modelValue","error-messages","rules"])]),_:1}),l(u,{cols:"12",md:"6",lg:"4"},{default:o(()=>[l(d,{modelValue:e.value.function,"onUpdate:modelValue":r[10]||(r[10]=t=>e.value.function=t),"error-messages":i.value.function,label:"Fonction",placeholder:"Ex: Agent de change",rules:["requiredValidator"in a?a.requiredValidator:s(n)]},null,8,["modelValue","error-messages","rules"])]),_:1}),l(u,{cols:"12",md:"6",lg:"4"},{default:o(()=>[l(d,{modelValue:e.value.office_delivery,"onUpdate:modelValue":r[11]||(r[11]=t=>e.value.office_delivery=t),"error-messages":i.value.office_delivery,label:"Delivrée par",placeholder:"Ex: DGDI",rules:["requiredValidator"in a?a.requiredValidator:s(n)]},null,8,["modelValue","error-messages","rules"])]),_:1}),l(u,{cols:"12",md:"6",lg:"4"},{default:o(()=>[l(d,{modelValue:e.value.phone_number,"onUpdate:modelValue":r[12]||(r[12]=t=>e.value.phone_number=t),"error-messages":i.value.phone_number,label:"Numéro de téléphone",placeholder:"Ex: 077906589",rules:["requiredValidator"in a?a.requiredValidator:s(n)]},null,8,["modelValue","error-messages","rules"])]),_:1})]),_:1})]),_:1})]),_:1})]),_:1}),l(u,{cols:"12"},{default:o(()=>[_("div",J,[_("div",Z,[l(y,{"prepend-icon":"tabler-arrow-left",to:{name:"contract-contract_id-guarantor",params:{contract_id:s(c).params.contract_id}}},{default:o(()=>[b(" Cautions ")]),_:1},8,["to"])]),_("div",z,[l(y,{type:"reset",variant:"tonal",color:"primary"},{default:o(()=>[l(h,{start:"",icon:"tabler-circle-minus"}),b(" Effacer ")]),_:1}),l(y,{type:"submit",class:"me-3"},{default:o(()=>[b(" Enregistrer "),l(h,{end:"",icon:"tabler-checkbox"})]),_:1})])])]),_:1})]),_:1})]),_:1},512)])}}},ye=F(H,[["__scopeId","data-v-1fbf8a78"]]);export{ye as default};