import{r as d}from"./validators-be8c4c00.js";import{r as v,w as k,ah as U,a1 as x,c as A,b as a,e as i,a2 as le,E as te,o as C,s,F as re,A as ie,d as c,a3 as M,M as h,Z as P,C as ue,D as oe,ai as se}from"./main-f3e13a8f.js";import{_ as de}from"./AppSelect-f9bba79a.js";import{_ as ne}from"./AppAutocomplete-6eb00fbe.js";import{_ as me}from"./AppDateTimePicker-043f5e42.js";import{_ as pe}from"./AppTextField-54cd16a8.js";import{c as I}from"./createUrl-5b827d42.js";import{u as j}from"./useApi-c6ecaa7e.js";import{_ as _e}from"./GuaranteeEdit-1451885c.js";import{$ as ce}from"./api-8717cbb6.js";import{_ as ve}from"./_plugin-vue_export-helper-c27b6911.js";import{V as L}from"./VRow-ae0d89f6.js";import{V as fe}from"./VForm-5cba14f8.js";import{V as o}from"./VCol-0d60ea4f.js";import{V as F}from"./VCard-a32068bc.js";import{V as S}from"./VCardText-b8804f5e.js";import{V as $}from"./VSlider-2c25c026.js";import{V as D}from"./VTextField-adad9b7a.js";import"./VSelect-3f739b67.js";import"./forwardRefs-6ea3df5c.js";import"./VList-28d59c3d.js";import"./VImg-be80b669.js";import"./VAvatar-7db9c562.js";import"./VDivider-c2833469.js";import"./VOverlay-52da89f8.js";import"./VMenu-55bf07f0.js";import"./filter-17a82583.js";import"./index-9c720871.js";import"./AppTextarea-45332e9a.js";import"./useAbility-be84da20.js";const N=b=>(ue("data-v-efd79870"),b=b(),oe(),b),Ve=N(()=>c("div",{class:"d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6"},[c("div",{class:"d-flex flex-column justify-center"},[c("h4",{class:"text-h4 font-weight-medium"},"Ajouter un nouveau PV"),c("span",null,"Procès verbal pour un nouveau crédit")])],-1)),ge={class:"my-4 ma-sm-4"},ye={class:"mt-4 ma-sm-4"},be={class:"d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6"},qe=N(()=>c("div",{class:"d-flex flex-column justify-center"},null,-1)),we={class:"d-flex gap-4 align-center flex-wrap"},Ee={__name:"add",async setup(b){let m,f;const R=te(),g=v(""),_=v(null),V=v(null),y=v(""),e=v({committee_id:"",committee_date:"",caf_id:"",civility:"Mr",applicant_first_name:"",applicant_last_name:"",account_number:"",activity:"",purpose_of_financing:"",type_of_credit_id:"",amount:"",duration:"",periodicity:"mensual",due_amount:"",insurance_premium:"",administrative_fees_percentage:0,tax_fee_interest_rate:14,credit_admin_id:"",guarantees:[{type_of_guarantee_id:1,comment:"..."}]}),B=async()=>{if(y.value){const l=await fetch(`/api/clients/comptes?search=${encodeURIComponent(y.value)}`);if(!l.ok)throw new Error("Erreur lors de la récupération des données");return await l.json()}};k(y,async l=>{l?(V.value=await B(),e.value.applicant_first_name=V.value.data.data[0].nom_replegal,e.value.applicant_last_name=V.value.data.data[0].nom_replegal,e.value.account_number=V.value.data.data[0].no_compte):V.value=[]});const T=()=>({committee_id:"",committee_date:"",caf_id:"",civility:"",applicant_first_name:"",applicant_last_name:"",account_number:"",activity:"",purpose_of_financing:"",type_of_credit_id:"",amount:"",duration:"",periodicity:"",due_amount:"",insurance_premium:"",administrative_fees_percentage:"",tax_fee_interest_rate:"",credit_admin_id:""}),u=v(T()),O=[{value:"Mr",title:"Mr"},{value:"Mme",title:"Mme"},{value:"Mlle",title:"Mlle"}],G=[{value:"mensual",title:"Mensuelle"},{value:"quarterly",title:"Trimestrielle"},{value:"semi-annual",title:"Semestrielle"},{value:"annual",title:"Annuelle"},{value:"in-fine",title:"A la fin"}],{data:z}=([m,f]=U(()=>j(I("/type-of-credit",{query:{paginate:0}}))),m=await m,f(),m),H=x(()=>z.value.data),{data:Z}=([m,f]=U(()=>j(I("/user",{query:{paginate:0,profile:"caf"}}))),m=await m,f(),m),J=x(()=>Z.value.data),{data:K}=([m,f]=U(()=>j(I("/user",{query:{paginate:0,profile:"credit_admin"}}))),m=await m,f(),m),Q=x(()=>K.value.data),E=v(),W=()=>{var l;(l=E.value)==null||l.validate().then(async({valid:t})=>{if(t){const n=await ce("/verbal-trial",{method:"POST",body:{committee_id:e.value.committee_id,committee_date:e.value.committee_date,caf_id:e.value.caf_id,civility:e.value.civility,applicant_first_name:e.value.applicant_first_name,applicant_last_name:e.value.applicant_last_name,account_number:e.value.account_number,activity:e.value.activity,purpose_of_financing:e.value.purpose_of_financing,type_of_credit_id:e.value.type_of_credit_id,amount:e.value.amount,duration:e.value.duration,periodicity:e.value.periodicity,due_amount:e.value.due_amount,insurance_premium:e.value.insurance_premium,administrative_fees_percentage:e.value.administrative_fees_percentage,taf:e.value.taf,tax_fee_interest_rate:e.value.tax_fee_interest_rate,guarantees:e.value.guarantees,credit_admin_id:e.value.credit_admin_id}});let q="/pv";if(u.value=T(),n.status==201)e.value.guarantees.forEach(p=>{p.type_of_guarantee_id==9&&(q="/pv/without-notification")}),R.push(q);else for(const p in n.errors)n.errors[p].forEach(w=>{u.value[p]+=w+`
`});se(()=>{var p;(p=E.value)==null||p.resetValidation()})}})},X=l=>{e.value.guarantees.splice(l,1)},Y=()=>{e.value.guarantees.push({type_of_guarantee_id:1,expiration_date:"",value:"",comment:""})},ee=async()=>{if(g.value){const l=await fetch(`/api/clients/prets?search=${encodeURIComponent(g.value)}`);if(!l.ok)throw new Error("Erreur lors de la récupération des données");return await l.json()}};return k(g,async l=>{l?(_.value=await ee(),console.log("dans le wacth",_.value),e.value.amount=_.value.data.data[0].mt_demande,e.value.due_amount=_.value.data.data[0].mt_pret_int,e.value.insurance_premium=_.value.data.data[0].mt_assurance,e.value.duration=_.value.data.data[0].nb_ech_pret,e.value.tax_fee_interest_rate=_.value.data.data[0].tx_int_pret,e.value.committee_id=_.value.data.data[0].ref_comite):V.value=[]}),(l,t)=>{const n=pe,q=me,p=ne,w=de;return C(),A("div",null,[Ve,a(L,null,{default:i(()=>[a(o,{cols:"12",md:"6",lg:"4"},{default:i(()=>[a(n,{modelValue:y.value,"onUpdate:modelValue":t[0]||(t[0]=r=>y.value=r),label:"Numéro du matricule"},null,8,["modelValue"])]),_:1}),a(o,{cols:"12",md:"6",lg:"4"},{default:i(()=>[a(n,{modelValue:g.value,"onUpdate:modelValue":t[1]||(t[1]=r=>g.value=r),label:"Numéro du prêt"},null,8,["modelValue"])]),_:1})]),_:1}),a(fe,{ref_key:"refForm",ref:E,onSubmit:le(W,["prevent"])},{default:i(()=>[a(L,null,{default:i(()=>[a(o,{md:"12"},{default:i(()=>[a(F,{class:"mb-6",title:"Information du pv"},{default:i(()=>[a(S,null,{default:i(()=>[a(L,null,{default:i(()=>[a(o,{cols:"12",md:"6",lg:"4"},{default:i(()=>[a(n,{modelValue:e.value.committee_id,"onUpdate:modelValue":t[2]||(t[2]=r=>e.value.committee_id=r),"error-messages":u.value.committee_id,label:"Numéro du comitée",rules:["requiredValidator"in l?l.requiredValidator:s(d)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(o,{cols:"12",md:"6",lg:"4"},{default:i(()=>[a(q,{modelValue:e.value.committee_date,"onUpdate:modelValue":t[3]||(t[3]=r=>e.value.committee_date=r),"error-messages":u.value.committee_date,label:"Date du comitée",rules:["requiredValidator"in l?l.requiredValidator:s(d)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(o,{cols:"12",md:"6",lg:"4"},{default:i(()=>[a(p,{modelValue:e.value.caf_id,"onUpdate:modelValue":t[4]||(t[4]=r=>e.value.caf_id=r),items:s(J),"error-messages":u.value.caf_id,label:"Chargé d'affaire","item-title":"full_name","item-value":"id",rules:["requiredValidator"in l?l.requiredValidator:s(d)]},null,8,["modelValue","items","error-messages","rules"])]),_:1}),a(o,{cols:"12",md:"6",lg:"4"},{default:i(()=>[a(p,{modelValue:e.value.credit_admin_id,"onUpdate:modelValue":t[5]||(t[5]=r=>e.value.credit_admin_id=r),items:s(Q),"error-messages":u.value.credit_admin_id,label:"Administrateur Crédit","item-title":"full_name","item-value":"id",rules:["requiredValidator"in l?l.requiredValidator:s(d)]},null,8,["modelValue","items","error-messages","rules"])]),_:1}),a(o,{cols:"12",md:"6",lg:"4"},{default:i(()=>[a(w,{modelValue:e.value.civility,"onUpdate:modelValue":t[6]||(t[6]=r=>e.value.civility=r),items:O,"error-messages":u.value.civility,label:"Civilité",placeholder:"Ex: Mr",rules:["requiredValidator"in l?l.requiredValidator:s(d)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(o,{cols:"12",md:"6",lg:"4"},{default:i(()=>[a(n,{modelValue:e.value.applicant_first_name,"onUpdate:modelValue":t[7]||(t[7]=r=>e.value.applicant_first_name=r),"error-messages":u.value.applicant_first_name,label:"Prénom du demandeur",placeholder:"Ex: Cesar",rules:["requiredValidator"in l?l.requiredValidator:s(d)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(o,{cols:"12",md:"6",lg:"4"},{default:i(()=>[a(n,{modelValue:e.value.applicant_last_name,"onUpdate:modelValue":t[8]||(t[8]=r=>e.value.applicant_last_name=r),"error-messages":u.value.applicant_last_name,label:"Nom du demandeur",placeholder:"Ex: Endure",rules:["requiredValidator"in l?l.requiredValidator:s(d)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(o,{cols:"12",md:"6",lg:"4"},{default:i(()=>[a(n,{modelValue:e.value.account_number,"onUpdate:modelValue":t[9]||(t[9]=r=>e.value.account_number=r),"error-messages":u.value.account_number,label:"Numéro de compte",placeholder:"Ex: 251012345678",rules:["requiredValidator"in l?l.requiredValidator:s(d)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(o,{cols:"12",md:"6",lg:"4"},{default:i(()=>[a(n,{modelValue:e.value.activity,"onUpdate:modelValue":t[10]||(t[10]=r=>e.value.activity=r),"error-messages":u.value.activity,label:"Fonction",placeholder:"Ex: Homme d'affaire",rules:["requiredValidator"in l?l.requiredValidator:s(d)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(o,{cols:"12",md:"6",lg:"4"},{default:i(()=>[a(n,{modelValue:e.value.purpose_of_financing,"onUpdate:modelValue":t[11]||(t[11]=r=>e.value.purpose_of_financing=r),"error-messages":u.value.purpose_of_financing,label:"Objet du financement",placeholder:"Ex: Achat nouveau locaux",rules:["requiredValidator"in l?l.requiredValidator:s(d)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(o,{cols:"12",md:"6",lg:"4"},{default:i(()=>[a(p,{modelValue:e.value.type_of_credit_id,"onUpdate:modelValue":t[12]||(t[12]=r=>e.value.type_of_credit_id=r),items:s(H),"error-messages":u.value.type_of_credit_id,label:"Type de credit",placeholder:"Ex: Avance sur salaire","item-title":"full_name","item-value":"id",rules:["requiredValidator"in l?l.requiredValidator:s(d)]},null,8,["modelValue","items","error-messages","rules"])]),_:1}),a(o,{cols:"12",md:"6",lg:"4"},{default:i(()=>[a(n,{modelValue:e.value.amount,"onUpdate:modelValue":t[13]||(t[13]=r=>e.value.amount=r),type:"number","error-messages":u.value.amount,label:"Montant",placeholder:"Ex: 15 000 000",rules:["requiredValidator"in l?l.requiredValidator:s(d)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(o,{cols:"12",md:"6",lg:"4"},{default:i(()=>[a(n,{modelValue:e.value.due_amount,"onUpdate:modelValue":t[14]||(t[14]=r=>e.value.due_amount=r),type:"number","error-messages":u.value.due_amount,label:"Montant des interêts",placeholder:"Ex: 300 000",rules:["requiredValidator"in l?l.requiredValidator:s(d)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(o,{cols:"12",md:"6",lg:"4"},{default:i(()=>[a(n,{modelValue:e.value.duration,"onUpdate:modelValue":t[15]||(t[15]=r=>e.value.duration=r),type:"number","error-messages":u.value.duration,label:"Durée du crédit en mois",placeholder:"Ex: 18","append-inner-icon":"tabler-calendar",rules:["requiredValidator"in l?l.requiredValidator:s(d)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(o,{cols:"12",md:"6",lg:"4"},{default:i(()=>[a(w,{modelValue:e.value.periodicity,"onUpdate:modelValue":t[16]||(t[16]=r=>e.value.periodicity=r),items:G,"error-messages":u.value.periodicity,label:"Periodicité",placeholder:"Ex: Mensuelle",rules:["requiredValidator"in l?l.requiredValidator:s(d)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(o,{cols:"12",md:"6",lg:"4"},{default:i(()=>[a(n,{modelValue:e.value.insurance_premium,"onUpdate:modelValue":t[17]||(t[17]=r=>e.value.insurance_premium=r),type:"number","error-messages":u.value.insurance_premium,label:"Prime d'assurance",placeholder:"Ex: 25000",rules:["requiredValidator"in l?l.requiredValidator:s(d)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(o,{cols:"12"},{default:i(()=>[a($,{modelValue:e.value.administrative_fees_percentage,"onUpdate:modelValue":t[19]||(t[19]=r=>e.value.administrative_fees_percentage=r),label:"Frais de dossier(%)","error-messages":u.value.administrative_fees_percentage,"thumb-size":15,"thumb-label":"always",rules:["requiredValidator"in l?l.requiredValidator:s(d)],step:"0.1"},{append:i(()=>[a(D,{modelValue:e.value.administrative_fees_percentage,"onUpdate:modelValue":t[18]||(t[18]=r=>e.value.administrative_fees_percentage=r),"error-messages":u.value.administrative_fees_percentage,type:"number",style:{width:"80px"},density:"compact","hide-details":"",variant:"outlined",suffix:"%"},null,8,["modelValue","error-messages"])]),_:1},8,["modelValue","error-messages","rules"])]),_:1}),a(o,{cols:"12"},{default:i(()=>[a($,{modelValue:e.value.tax_fee_interest_rate,"onUpdate:modelValue":t[21]||(t[21]=r=>e.value.tax_fee_interest_rate=r),label:"Taux d'intérêt HT(%)","error-messages":u.value.tax_fee_interest_rate,"thumb-size":15,"thumb-label":"always",rules:["requiredValidator"in l?l.requiredValidator:s(d)],step:"0.1"},{append:i(()=>[a(D,{modelValue:e.value.tax_fee_interest_rate,"onUpdate:modelValue":t[20]||(t[20]=r=>e.value.tax_fee_interest_rate=r),"error-messages":u.value.tax_fee_interest_rate,type:"number",style:{width:"80px"},density:"compact","hide-details":"",variant:"outlined",suffix:"%"},null,8,["modelValue","error-messages"])]),_:1},8,["modelValue","error-messages","rules"])]),_:1})]),_:1})]),_:1})]),_:1}),a(F,{class:"mb-6",title:"Information des garanties"},{default:i(()=>[a(S,{class:"add-products-form"},{default:i(()=>[(C(!0),A(re,null,ie(e.value.guarantees,(r,ae)=>(C(),A("div",ge,[a(_e,{id:ae,data:r,onRemoveGuarantee:X},null,8,["id","data"])]))),256)),c("div",ye,[a(M,{"prepend-icon":"tabler-plus",onClick:Y},{default:i(()=>[h(" Ajouter ")]),_:1})])]),_:1})]),_:1})]),_:1}),a(o,{cols:"12"},{default:i(()=>[c("div",be,[qe,c("div",we,[a(M,{type:"reset",variant:"tonal",color:"primary"},{default:i(()=>[a(P,{start:"",icon:"tabler-circle-minus"}),h(" Effacer ")]),_:1}),a(M,{type:"submit",class:"me-3"},{default:i(()=>[h(" Enregistrer "),a(P,{end:"",icon:"tabler-checkbox"})]),_:1})])])]),_:1})]),_:1})]),_:1},512)])}}},ea=ve(Ee,[["__scopeId","data-v-efd79870"]]);export{ea as default};
