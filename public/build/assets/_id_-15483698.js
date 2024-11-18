import{r as u}from"./validators-be8c4c00.js";import{r as c,ah as C,a1 as O,c as z,b as e,e as a,a2 as G,E as Z,n as H,o as x,a3 as g,Z as U,M as v,s as t,f as D,N as I,d as p,x as K,C as Q,D as W,ai as X}from"./main-35f434e5.js";import{_ as Y}from"./AppDateTimePicker-cb72b21d.js";import{_ as ee}from"./AppSelect-f486f450.js";import{_ as te}from"./AppTextField-4144e217.js";import{_ as re}from"./AppAutocomplete-6c363bb0.js";import{u as P}from"./useApi-f6bf8dd7.js";import{c as $}from"./createUrl-376a4625.js";import{$ as ae}from"./api-bc7c6e86.js";import{_ as oe}from"./_plugin-vue_export-helper-c27b6911.js";import{V as ie}from"./VForm-b1b04516.js";import{V as le}from"./VSnackbar-9ea4b62f.js";import{V}from"./VRow-5bf9819c.js";import{V as s}from"./VCol-bbc947c1.js";import{V as S}from"./VCard-de226d5c.js";import{V as N}from"./VCardText-2ef94eba.js";import{V as se}from"./VSlider-96233bbb.js";import{V as ne}from"./VTextField-31ac9b36.js";import"./VSelect-8eaa7a4a.js";import"./forwardRefs-6ea3df5c.js";import"./VList-a84ed864.js";import"./VImg-fb08db81.js";import"./VAvatar-5cd70722.js";import"./VDivider-2199d20e.js";import"./VOverlay-6080deb7.js";import"./VMenu-ad7875d9.js";import"./filter-e1345ab9.js";import"./index-9c720871.js";import"./useAbility-597cc743.js";const j=b=>(Q("data-v-841f2d3b"),b=b(),W(),b),de=j(()=>p("div",{class:"d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6"},[p("div",{class:"d-flex flex-column justify-center"},[p("h4",{class:"text-h4 font-weight-medium"}," Modification de notification "),p("span",null,"Dashboard/Notifications/Modification")])],-1)),ue={class:"d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6"},me=j(()=>p("div",{class:"d-flex flex-column justify-center"},null,-1)),_e={class:"d-flex gap-4 align-center flex-wrap"},pe={__name:"[id]",async setup(b){let _,y;const F=Z(),q=H(),T=()=>({verbal_trial_id:"",representative_phone_number:"",representative_home_address:"",number_of_due_dates:"",risk_premium_percentage:"",total_amount_of_interest:"",representative_type_of_identity_document:"",representative_number_of_identity_document:"",representative_date_of_issue_of_identity_document:"",type:"",business_denomination:""}),n=c(T()),{data:A}=([_,y]=C(()=>P($("/verbal-trial",{query:{has_notification:0,paginate:0,has_mortgage:1,status:"v"}}))),_=await _,y(),_),M=O(()=>A.value.data),{data:B}=([_,y]=C(()=>P($(`/notification/${q.params.id}`,{query:{with_verbal_trial:1}}))),_=await _,y(),_);var r=c(B.value.data.notification);A.value.data.push(JSON.parse(JSON.stringify(r.value.verbal_trial)));const h=c(),R=()=>{var i;(i=h.value)==null||i.validate().then(async({valid:o})=>{if(o){const w={verbal_trial_id:r.value.verbal_trial_id,representative_phone_number:r.value.representative_phone_number,representative_home_address:r.value.representative_home_address,number_of_due_dates:r.value.number_of_due_dates,risk_premium_percentage:r.value.risk_premium_percentage,total_amount_of_interest:r.value.total_amount_of_interest,representative_type_of_identity_document:r.value.representative_type_of_identity_document,representative_number_of_identity_document:r.value.representative_number_of_identity_document,representative_date_of_issue_of_identity_document:r.value.representative_date_of_issue_of_identity_document,type:r.value.type,business_denomination:r.value.business_denomination},d=await ae(`/notification/${q.params.id}`,{method:"PUT",body:w});if(n.value=T(),d.status==200)F.push("/notification");else if(d.status==403){k.value=!0,E.value="";for(const m in d.errors)d.errors[m].forEach(f=>{E.value+=f+`
`})}else for(const m in d.errors)d.errors[m].forEach(f=>{n.value[m]+=f+`
`});X(()=>{var m;(m=h.value)==null||m.resetValidation()})}})},k=c(!1),E=c(""),L=[{value:"company",title:"Société"},{value:"individual_business",title:"Entreprise Individuel"},{value:"particular",title:"Particulier"}],J=[{value:"cni",title:"Carte d'identité nationale"},{value:"passport",title:"Passeport"},{value:"residence_certificate",title:"Certificat de résidence"},{value:"driving_licence",title:"Permis de conduire"}];return(i,o)=>{const w=re,d=te,m=ee,f=Y;return x(),z("div",null,[de,e(ie,{ref_key:"refForm",ref:h,onSubmit:G(R,["prevent"])},{default:a(()=>[e(V,null,{default:a(()=>[e(s,{cols:"11"},{default:a(()=>[e(g,{to:{name:"notification"}},{default:a(()=>[e(U,{icon:"tabler-arrow-left"}),v(" Notifications ")]),_:1})]),_:1}),e(s,{cols:"1"},{default:a(()=>[e(g,{to:{name:"notification-id",params:{id:t(q).params.id}}},{default:a(()=>[v(" Voir ")]),_:1},8,["to"])]),_:1})]),_:1}),e(V,null,{default:a(()=>[e(s,{md:"12"},{default:a(()=>[e(S,{class:"mb-6",title:"Information sur notification"},{default:a(()=>[e(N,null,{default:a(()=>[e(V,null,{default:a(()=>[e(s,{cols:"12",md:"6",lg:"4"},{default:a(()=>[e(w,{modelValue:t(r).verbal_trial_id,"onUpdate:modelValue":o[0]||(o[0]=l=>t(r).verbal_trial_id=l),items:t(M),"error-messages":n.value.verbal_trial_id,label:"Procès verbal",placeholder:"Ex: CFNTG-044-13-12-23-01212",rules:["requiredValidator"in i?i.requiredValidator:t(u)],"item-title":"label","item-value":"id"},null,8,["modelValue","items","error-messages","rules"])]),_:1}),e(s,{cols:"12",md:"6",lg:"4"},{default:a(()=>[e(d,{modelValue:t(r).representative_phone_number,"onUpdate:modelValue":o[1]||(o[1]=l=>t(r).representative_phone_number=l),"error-messages":n.value.representative_phone_number,label:"Numéro de téléphone",placeholder:"Ex: +228 96 96 96 96",rules:["requiredValidator"in i?i.requiredValidator:t(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(s,{cols:"12",md:"6",lg:"4"},{default:a(()=>[e(d,{modelValue:t(r).representative_home_address,"onUpdate:modelValue":o[2]||(o[2]=l=>t(r).representative_home_address=l),"error-messages":n.value.representative_home_address,label:"Addresse",placeholder:"Ex: Adewi",rules:["requiredValidator"in i?i.requiredValidator:t(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(s,{cols:"12",md:"6",lg:"4"},{default:a(()=>[e(d,{type:"number",modelValue:t(r).number_of_due_dates,"onUpdate:modelValue":o[3]||(o[3]=l=>t(r).number_of_due_dates=l),"error-messages":n.value.number_of_due_dates,label:"Nombre d'échéance",placeholder:"Ex: 4",rules:["requiredValidator"in i?i.requiredValidator:t(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(s,{cols:"12",md:"6",lg:"4"},{default:a(()=>[e(d,{modelValue:t(r).total_amount_of_interest,"onUpdate:modelValue":o[4]||(o[4]=l=>t(r).total_amount_of_interest=l),type:"number","error-messages":n.value.total_amount_of_interest,label:"Montant total des intérêts",placeholder:"Ex: 15 000 000",rules:["requiredValidator"in i?i.requiredValidator:t(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(s,{cols:"12",md:"6",lg:"4"},{default:a(()=>[e(m,{modelValue:t(r).type,"onUpdate:modelValue":o[5]||(o[5]=l=>t(r).type=l),items:L,"error-messages":n.value.type,label:"Type",placeholder:"Ex: Particulier",rules:["requiredValidator"in i?i.requiredValidator:t(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(s,{cols:"12",md:"6",lg:"4"},{default:a(()=>[e(m,{modelValue:t(r).representative_type_of_identity_document,"onUpdate:modelValue":o[6]||(o[6]=l=>t(r).representative_type_of_identity_document=l),items:J,"error-messages":n.value.representative_type_of_identity_document,label:"Type de la pièce d'identité",placeholder:"Ex: Passeport",rules:["requiredValidator"in i?i.requiredValidator:t(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(s,{cols:"12",md:"6",lg:"4"},{default:a(()=>[e(d,{modelValue:t(r).representative_number_of_identity_document,"onUpdate:modelValue":o[7]||(o[7]=l=>t(r).representative_number_of_identity_document=l),"error-messages":n.value.representative_number_of_identity_document,label:"Numéro de la pièce d'identité",placeholder:"Ex: 251012345678",rules:["requiredValidator"in i?i.requiredValidator:t(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(s,{cols:"12",md:"12",lg:"4"},{default:a(()=>[e(f,{modelValue:t(r).representative_date_of_issue_of_identity_document,"onUpdate:modelValue":o[8]||(o[8]=l=>t(r).representative_date_of_issue_of_identity_document=l),"error-messages":n.value.representative_date_of_issue_of_identity_document,label:"Date de délivrance de la pièce d'identité",placeholder:"Ex: 2022-01-01",rules:["requiredValidator"in i?i.requiredValidator:t(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),e(s,{cols:"12"},{default:a(()=>[e(se,{modelValue:t(r).risk_premium_percentage,"onUpdate:modelValue":o[10]||(o[10]=l=>t(r).risk_premium_percentage=l),label:"Prime de risque (en pourcentage) du demandeur","error-messages":n.value.risk_premium_percentage,"thumb-size":15,"thumb-label":"always",rules:["requiredValidator"in i?i.requiredValidator:t(u)],step:"0.1"},{append:a(()=>[e(ne,{modelValue:t(r).risk_premium_percentage,"onUpdate:modelValue":o[9]||(o[9]=l=>t(r).risk_premium_percentage=l),"error-messages":n.value.risk_premium_percentage,type:"number",style:{width:"80px"},density:"compact","hide-details":"",variant:"outlined",suffix:"%"},null,8,["modelValue","error-messages"])]),_:1},8,["modelValue","error-messages","rules"])]),_:1})]),_:1})]),_:1})]),_:1}),t(r).type=="company"?(x(),D(S,{key:0,class:"mb-6",title:"Information sur la société"},{default:a(()=>[e(N,null,{default:a(()=>[e(V,{cols:"12"},{default:a(()=>[e(s,{cols:"12"},{default:a(()=>[e(d,{modelValue:t(r).business_denomination,"onUpdate:modelValue":o[11]||(o[11]=l=>t(r).business_denomination=l),"error-messages":n.value.business_denomination,label:"Dénomination",placeholder:"Ex: Adjovidjo",rules:["requiredValidator"in i?i.requiredValidator:t(u)]},null,8,["modelValue","error-messages","rules"])]),_:1})]),_:1})]),_:1})]),_:1})):I("",!0),t(r).type=="individual_business"?(x(),D(S,{key:1,class:"mb-6",title:"Information sur l'entreprise individuele"},{default:a(()=>[e(N,null,{default:a(()=>[e(V,{cols:"12"},{default:a(()=>[e(s,{cols:"12"},{default:a(()=>[e(d,{modelValue:t(r).business_denomination,"onUpdate:modelValue":o[12]||(o[12]=l=>t(r).business_denomination=l),"error-messages":n.value.business_denomination,label:"Dénomination",placeholder:"Ex: Agban",rules:["requiredValidator"in i?i.requiredValidator:t(u)]},null,8,["modelValue","error-messages","rules"])]),_:1})]),_:1})]),_:1})]),_:1})):I("",!0)]),_:1}),e(s,{cols:"12"},{default:a(()=>[p("div",ue,[me,p("div",_e,[e(g,{type:"reset",variant:"tonal",color:"primary"},{default:a(()=>[e(U,{start:"",icon:"tabler-circle-minus"}),v(" Effacer ")]),_:1}),e(g,{type:"submit",class:"me-3"},{default:a(()=>[v(" Enregistrer "),e(U,{end:"",icon:"tabler-checkbox"})]),_:1})])])]),_:1})]),_:1})]),_:1},512),e(le,{modelValue:k.value,"onUpdate:modelValue":o[13]||(o[13]=l=>k.value=l),transition:"scroll-y-reverse-transition",location:"bottom end",color:"error"},{default:a(()=>[v(K(E.value),1)]),_:1},8,["modelValue"])])}}},Je=oe(pe,[["__scopeId","data-v-841f2d3b"]]);export{Je as default};
