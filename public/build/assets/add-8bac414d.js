import{r as u}from"./validators-be8c4c00.js";import{r as y,ah as $,a1 as B,c as L,b as t,e as l,a2 as R,n as G,E as H,o as g,s as n,f as A,N as T,d as _,a3 as U,Z as C,M as N,C as M,D as J,ai as z}from"./main-e84ce0a6.js";import{_ as K}from"./AppDateTimePicker-b73077cf.js";import{_ as O}from"./AppSelect-bd457cdc.js";import{_ as Z}from"./AppTextField-cfb27a57.js";import{_ as Q}from"./AppAutocomplete-02a87663.js";import{u as W}from"./useApi-7dd63682.js";import{c as X}from"./createUrl-f896560b.js";import{$ as Y}from"./api-6638b469.js";import{_ as ee}from"./_plugin-vue_export-helper-c27b6911.js";import{V as te}from"./VForm-b81ac70c.js";import{V as v}from"./VRow-d22e34f5.js";import{V as s}from"./VCol-a817eccc.js";import{V as q}from"./VCard-fe1def77.js";import{V as h}from"./VCardText-c43c5a35.js";import{V as re}from"./VSlider-538640ca.js";import{V as ae}from"./VTextField-bed2e038.js";import"./VSelect-0f27dc1c.js";import"./forwardRefs-6ea3df5c.js";import"./VList-31615f1a.js";import"./VImg-291e1ee2.js";import"./VAvatar-b9338bff.js";import"./VDivider-a6507b5e.js";import"./VOverlay-3c60a455.js";import"./VMenu-4db2c15b.js";import"./filter-7bde97a1.js";import"./index-9c720871.js";import"./useAbility-d056d777.js";const I=p=>(M("data-v-84aca38d"),p=p(),J(),p),le=I(()=>_("div",{class:"d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6"},[_("div",{class:"d-flex flex-column justify-center"},[_("h4",{class:"text-h4 font-weight-medium"}," Ajouter une nouvelle notification "),_("span",null,"Notification pour un Procès verbal")])],-1)),oe={class:"d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6"},ie=I(()=>_("div",{class:"d-flex flex-column justify-center"},null,-1)),se={class:"d-flex gap-4 align-center flex-wrap"},ne={__name:"add",async setup(p){let f,k;const E=G(),P=H(),e=y({verbal_trial_id:null,representative_phone_number:"+228 90 90 90 90",representative_home_address:"Adewi",number_of_due_dates:15,risk_premium_percentage:0,total_amount_of_interest:15e5,representative_type_of_identity_document:"passport",representative_number_of_identity_document:"KJH-VCVG-FGH-HBJN",representative_date_of_issue_of_identity_document:"2026-02-02",type:"company",business_denomination:"ETS Alberta"}),w=()=>({verbal_trial_id:"",representative_phone_number:"",representative_home_address:"",number_of_due_dates:"",risk_premium_percentage:"",total_amount_of_interest:"",representative_type_of_identity_document:"",representative_number_of_identity_document:"",representative_date_of_issue_of_identity_document:"",type:"",business_denomination:""}),i=y(w()),{data:S}=([f,k]=$(()=>W(X("/verbal-trial",{query:{has_notification:0,paginate:0,has_mortgage:1,status:"v"}}))),f=await f,k(),f),x=B(()=>S.value.data),c=y(),j=()=>{var r;(r=c.value)==null||r.validate().then(async({valid:a})=>{if(a){const V={verbal_trial_id:e.value.verbal_trial_id,representative_phone_number:e.value.representative_phone_number,representative_home_address:e.value.representative_home_address,number_of_due_dates:e.value.number_of_due_dates,risk_premium_percentage:e.value.risk_premium_percentage,total_amount_of_interest:e.value.total_amount_of_interest,representative_type_of_identity_document:e.value.representative_type_of_identity_document,representative_number_of_identity_document:e.value.representative_number_of_identity_document,representative_date_of_issue_of_identity_document:e.value.representative_date_of_issue_of_identity_document,type:e.value.type,business_denomination:e.value.business_denomination},d=await Y("/notification",{method:"POST",body:V});if(i.value=w(),d.status==201)P.push("/notification");else for(const m in d.errors)d.errors[m].forEach(b=>{i.value[m]+=b+`
`});z(()=>{var m;(m=c.value)==null||m.resetValidation()})}})};if(E.query.id){const r=parseInt(E.query.id);x.value.find(a=>a.id==r)&&(e.value.verbal_trial_id=r)}const F=[{value:"company",title:"Société"},{value:"individual_business",title:"Entreprise Individuel"},{value:"particular",title:"Particulier"}],D=[{value:"cni",title:"Carte d'identité nationale"},{value:"passport",title:"Passeport"},{value:"residence_certificate",title:"Certificat de résidence"},{value:"driving_licence",title:"Permis de conduire"}];return(r,a)=>{const V=Q,d=Z,m=O,b=K;return g(),L("div",null,[le,t(te,{ref_key:"refForm",ref:c,onSubmit:R(j,["prevent"])},{default:l(()=>[t(v,null,{default:l(()=>[t(s,{md:"12"},{default:l(()=>[t(q,{class:"mb-6",title:"Information sur notification"},{default:l(()=>[t(h,null,{default:l(()=>[t(v,null,{default:l(()=>[t(s,{cols:"12",md:"6",lg:"4"},{default:l(()=>[t(V,{modelValue:e.value.verbal_trial_id,"onUpdate:modelValue":a[0]||(a[0]=o=>e.value.verbal_trial_id=o),items:n(x),"error-messages":i.value.verbal_trial_id,label:"Procès verbal",placeholder:"Ex: CFNTG-044-13-12-23-01212",rules:["requiredValidator"in r?r.requiredValidator:n(u)],"item-title":"label","item-value":"id"},null,8,["modelValue","items","error-messages","rules"])]),_:1}),t(s,{cols:"12",md:"6",lg:"4"},{default:l(()=>[t(d,{modelValue:e.value.representative_phone_number,"onUpdate:modelValue":a[1]||(a[1]=o=>e.value.representative_phone_number=o),"error-messages":i.value.representative_phone_number,label:"Numéro de téléphone",placeholder:"Ex: +228 96 96 96 96",rules:["requiredValidator"in r?r.requiredValidator:n(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),t(s,{cols:"12",md:"6",lg:"4"},{default:l(()=>[t(d,{modelValue:e.value.representative_home_address,"onUpdate:modelValue":a[2]||(a[2]=o=>e.value.representative_home_address=o),"error-messages":i.value.representative_home_address,label:"Addresse",placeholder:"Ex: Adewi",rules:["requiredValidator"in r?r.requiredValidator:n(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),t(s,{cols:"12",md:"6",lg:"4"},{default:l(()=>[t(d,{type:"number",modelValue:e.value.number_of_due_dates,"onUpdate:modelValue":a[3]||(a[3]=o=>e.value.number_of_due_dates=o),"error-messages":i.value.number_of_due_dates,label:"Nombre d'échéance",placeholder:"Ex: 4",rules:["requiredValidator"in r?r.requiredValidator:n(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),t(s,{cols:"12",md:"6",lg:"4"},{default:l(()=>[t(d,{modelValue:e.value.total_amount_of_interest,"onUpdate:modelValue":a[4]||(a[4]=o=>e.value.total_amount_of_interest=o),type:"number","error-messages":i.value.total_amount_of_interest,label:"Montant total des intérêts",placeholder:"Ex: 15 000 000",rules:["requiredValidator"in r?r.requiredValidator:n(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),t(s,{cols:"12",md:"6",lg:"4"},{default:l(()=>[t(m,{modelValue:e.value.type,"onUpdate:modelValue":a[5]||(a[5]=o=>e.value.type=o),items:F,"error-messages":i.value.type,label:"Type",placeholder:"Ex: Particulier",rules:["requiredValidator"in r?r.requiredValidator:n(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),t(s,{cols:"12",md:"6",lg:"4"},{default:l(()=>[t(m,{modelValue:e.value.representative_type_of_identity_document,"onUpdate:modelValue":a[6]||(a[6]=o=>e.value.representative_type_of_identity_document=o),items:D,"error-messages":i.value.representative_type_of_identity_document,label:"Type de la pièce d'identité",placeholder:"Ex: Passeport",rules:["requiredValidator"in r?r.requiredValidator:n(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),t(s,{cols:"12",md:"6",lg:"4"},{default:l(()=>[t(d,{modelValue:e.value.representative_number_of_identity_document,"onUpdate:modelValue":a[7]||(a[7]=o=>e.value.representative_number_of_identity_document=o),"error-messages":i.value.representative_number_of_identity_document,label:"Numéro de la pièce d'identité",placeholder:"Ex: 251012345678",rules:["requiredValidator"in r?r.requiredValidator:n(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),t(s,{cols:"12",md:"12",lg:"4"},{default:l(()=>[t(b,{modelValue:e.value.representative_date_of_issue_of_identity_document,"onUpdate:modelValue":a[8]||(a[8]=o=>e.value.representative_date_of_issue_of_identity_document=o),"error-messages":i.value.representative_date_of_issue_of_identity_document,label:"Date de délivrance de la pièce d'identité",placeholder:"Ex: 2022-01-01",rules:["requiredValidator"in r?r.requiredValidator:n(u)]},null,8,["modelValue","error-messages","rules"])]),_:1}),t(s,{cols:"12"},{default:l(()=>[t(re,{modelValue:e.value.risk_premium_percentage,"onUpdate:modelValue":a[10]||(a[10]=o=>e.value.risk_premium_percentage=o),label:"Prime de risque (en pourcentage) du demandeur","error-messages":i.value.risk_premium_percentage,"thumb-size":15,"thumb-label":"always",rules:["requiredValidator"in r?r.requiredValidator:n(u)],step:"0.1"},{append:l(()=>[t(ae,{modelValue:e.value.risk_premium_percentage,"onUpdate:modelValue":a[9]||(a[9]=o=>e.value.risk_premium_percentage=o),"error-messages":i.value.risk_premium_percentage,type:"number",style:{width:"80px"},density:"compact","hide-details":"",variant:"outlined",suffix:"%"},null,8,["modelValue","error-messages"])]),_:1},8,["modelValue","error-messages","rules"])]),_:1})]),_:1})]),_:1})]),_:1}),e.value.type=="company"?(g(),A(q,{key:0,class:"mb-6",title:"Information sur la société"},{default:l(()=>[t(h,null,{default:l(()=>[t(v,{cols:"12"},{default:l(()=>[t(s,{cols:"12"},{default:l(()=>[t(d,{modelValue:e.value.business_denomination,"onUpdate:modelValue":a[11]||(a[11]=o=>e.value.business_denomination=o),"error-messages":i.value.business_denomination,label:"Dénomination",placeholder:"Ex: Adjovidjo",rules:["requiredValidator"in r?r.requiredValidator:n(u)]},null,8,["modelValue","error-messages","rules"])]),_:1})]),_:1})]),_:1})]),_:1})):T("",!0),e.value.type=="individual_business"?(g(),A(q,{key:1,class:"mb-6",title:"Information sur l'entreprise individuele"},{default:l(()=>[t(h,null,{default:l(()=>[t(v,{cols:"12"},{default:l(()=>[t(s,{cols:"12"},{default:l(()=>[t(d,{modelValue:e.value.business_denomination,"onUpdate:modelValue":a[12]||(a[12]=o=>e.value.business_denomination=o),"error-messages":i.value.business_denomination,label:"Dénomination",placeholder:"Ex: Agban",rules:["requiredValidator"in r?r.requiredValidator:n(u)]},null,8,["modelValue","error-messages","rules"])]),_:1})]),_:1})]),_:1})]),_:1})):T("",!0)]),_:1}),t(s,{cols:"12"},{default:l(()=>[_("div",oe,[ie,_("div",se,[t(U,{type:"reset",variant:"tonal",color:"primary"},{default:l(()=>[t(C,{start:"",icon:"tabler-circle-minus"}),N(" Effacer ")]),_:1}),t(U,{type:"submit",class:"me-3"},{default:l(()=>[N(" Enregistrer "),t(C,{end:"",icon:"tabler-checkbox"})]),_:1})])])]),_:1})]),_:1})]),_:1},512)])}}},De=ee(ne,[["__scopeId","data-v-84aca38d"]]);export{De as default};