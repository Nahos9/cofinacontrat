import{r as o}from"./validators-be8c4c00.js";import{r as f,w as R,ah as W,a1 as X,c as N,b as a,e as u,a2 as Y,n as x,E as ee,o as c,s as d,f as P,F as ae,A as le,d as p,a3 as k,M as A,N as j,Z as $,C as re,D as se,ai as ue}from"./main-a1bb8d1c.js";import{_ as ie}from"./AppSelect-ec953df4.js";import{_ as ne}from"./AppDateTimePicker-127836d3.js";import{_ as te}from"./AppAutocomplete-5e56137d.js";import{_ as de}from"./AppTextField-6136aa1e.js";import{V as oe,_ as _e}from"./PledgeEdit-f4c7c9fa.js";import{u as me}from"./useApi-ee532938.js";import{c as ve}from"./createUrl-82711664.js";import{$ as pe}from"./api-0fd30a7b.js";import{_ as fe}from"./_plugin-vue_export-helper-c27b6911.js";import{V as b}from"./VRow-4fd62cc5.js";import{V as ce}from"./VForm-0ba8db0b.js";import{V as n}from"./VCol-c9fbba03.js";import{V as h}from"./VCard-0a0db0fa.js";import{V as q}from"./VCardText-ec067fb4.js";import"./VTextField-251e47e8.js";import"./VImg-c3c51305.js";import"./forwardRefs-6ea3df5c.js";import"./VSelect-00885f47.js";import"./VList-63aa9283.js";import"./VAvatar-28a02eb0.js";import"./VDivider-bdae5753.js";import"./VOverlay-5a96f554.js";import"./VMenu-e4df9f05.js";import"./filter-22cdad36.js";import"./index-9c720871.js";import"./useAbility-c9217b08.js";const F=U=>(re("data-v-10c6754f"),U=U(),se(),U),be=F(()=>p("div",{class:"d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6"},[p("div",{class:"d-flex flex-column justify-center"},[p("h4",{class:"text-h4 font-weight-medium"},"Ajouter un nouveau contrat"),p("span",null,"Contrat pour un Procès verbal")])],-1)),Ve={class:"my-4 ma-sm-4"},ye={class:"mt-4 ma-sm-4"},ge={class:"d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6"},he=F(()=>p("div",{class:"d-flex flex-column justify-center"},null,-1)),qe={class:"d-flex gap-4 align-center flex-wrap"},Ue={__name:"add",async setup(U){let w,T;const E=x(),L=ee(),e=f({verbal_trial_id:null,representative_birth_date:null,date_of_first_echeance:null,date_of_last_echeance:null,representative_birth_place:null,representative_nationality:null,representative_home_address:null,representative_phone_number:null,representative_type_of_identity_document:null,representative_number_of_identity_document:null,representative_date_of_issue_of_identity_document:null,representative_office_delivery:null,total_amount_of_interest:null,number_of_pret:null,montant_fudiciaire:null,due_amount:null,montant_second_ech:null,montant_troisieme_ech:null,number_of_due_dates:null,type:null,has_pledges:null,company_denomination:null,company_legal_status:null,company_head_office_address:null,company_rccm_number:null,company_phone_number:null,company_nif:null,company_bp:null,company_commune:null,individual_business_denomination:null,individual_business_head_office_address:null,individual_business_rccm_number:null,individual_business_nif_number:null,individual_business_civility:null,individual_business_phone_number:null,individual_business_first_name:null,individual_business_last_name:null,individual_business_date_naiss:null,individual_business_lieux_naiss:null,individual_business_nationalite:null,individual_business_home_address:null,individual_business_num_piece:null,individual_business_date_delivrance:null,individual_business_number_phone:null,individual_business_office_delivery:null,individual_business_type_of_identity_document:null,individual_business_bp:null,individual_business_commune:null,pledges:[{type:"vehicle",comment:"",montant_estime:"",marque:"",date_mise_en_circulation:"",date_carte_crise:"",immatriculation:"",nume_serie:"",genre:"",model:""}]}),V=f(""),y=f(""),_=f(null),v=f(null),S=async()=>{if(y.value){const l=await fetch(`/api/clients/search?search=${encodeURIComponent(y.value)}`);if(!l.ok)throw new Error("Erreur lors de la récupération des données");return await l.json()}},B=async()=>{if(V.value){const l=await fetch(`/api/clients/prets?search=${encodeURIComponent(V.value)}`);if(!l.ok)throw new Error("Erreur lors de la récupération des données");return await l.json()}};R(y,async l=>{l?(_.value=await S(),console.log("dans le wacth",_.value),e.value.representative_birth_date=_.value.data.data[0].date_naissance_entrepreneur,e.value.representative_birth_place=_.value.data.data[0].lieu_naissance,e.value.representative_nationality=_.value.data.data[0].no_compte,e.value.representative_number_of_identity_document=_.value.data.data[0].numero_piece_identite,e.value.representative_date_of_issue_of_identity_document=_.value.data.data[0].date_delivrance_piece,e.value.representative_home_address=_.value.data.data[0].adresse_1,e.value.representative_phone_number=_.value.data.data[0].tel_port,e.value.representative_office_delivery=_.value.data.data[0].lieu_delivrance_piece,e.value.individual_business_last_name=_.value.data.data[0].nom_replegal,e.value.individual_business_first_name=_.value.data.data[0].nom_replegal,e.value.individual_business_date_naiss=_.value.data.data[0].date_naissance_entrepreneur,e.value.individual_business_lieux_naiss=_.value.data.data[0].lieu_naissance,e.value.individual_business_date_delivrance=_.value.data.data[0].date_delivrance_piece,e.value.individual_business_num_piece=_.value.data.data[0].numero_piece_identite,e.value.individual_business_home_address=_.value.data.data[0].adr_replegal,e.value.individual_business_number_phone=_.value.data.data[0].tel_port,e.value.company_denomination=_.value.data.data[0].raison_sociale_client,e.value.individual_business_denomination=_.value.data.data[0].raison_sociale_client,e.value.company_head_office_address=_.value.data.data[0].adresse_1,e.value.individual_business_head_office_address=_.value.data.data[0].adresse_1,e.value.individual_business_rccm_number=_.value.data.data[0].numero_piece_identite,e.value.individual_business_phone_number=_.value.data.data[0].tel_port):_.value=[]}),R(V,async l=>{l?(v.value=await B(),console.log("dans le wacth",v.value),e.value.total_amount_of_interest=v.value.data.data[0].mt_pret_int,e.value.montant_troisieme_ech=v.value.data.data[0].mt_ech_pret,e.value.number_of_due_dates=v.value.data.data[0].nb_ech_pret,e.value.date_of_first_echeance=v.value.data.data[0].d_prem_ech,e.value.date_of_last_echeance=v.value.data.data[0].d_der_ech,e.value.number_of_pret=v.value.data.data[0].no_pret):_.value=[]});const D=()=>({verbal_trial_id:"",representative_birth_date:"",representative_birth_place:"",representative_nationality:"",representative_home_address:"",representative_phone_number:"",date_of_first_echeance:"",date_of_last_echeance:"",representative_type_of_identity_document:"",representative_number_of_identity_document:"",representative_date_of_issue_of_identity_document:"",representative_office_delivery:"",total_amount_of_interest:"",due_amount:"",number_of_due_dates:"",number_of_pret:"",montant_fudiciaire:"",montant_second_ech:"",montant_troisieme_ech:"",type:"",has_pledges:"",company_denomination:"",company_legal_status:"",company_head_office_address:"",company_rccm_number:"",company_phone_number:"",company_nif:"",company_bp:"",company_commune:"",individual_business_denomination:"",individual_business_head_office_address:"",individual_business_rccm_number:"",individual_business_nif_number:"",individual_business_phone_number:"",individual_business_bp:"",individual_business_commune:""}),t=f(D()),{data:O}=([w,T]=W(()=>me(ve("/verbal-trial",{query:{has_contract:0,paginate:0,has_mortgage:0,status:"w"}}))),w=await w,T(),w),M=X(()=>O.value.data),G=[{value:"company",title:"Société"},{value:"individual_business",title:"Entreprise Individuel"},{value:"particular",title:"Particulier"},{value:"ong",title:"Mutuelles-Association-ONG"},{value:"professions_libérales",title:"Professions libérales"}],Z={1:"Avec gage",0:"Sans gage"},z=[{value:"cni",title:"Carte d'identité nationale"},{value:"passport",title:"Passeport"},{value:"residence_certificate",title:"Certificat de résidence"},{value:"driving_licence",title:"Permis de conduire"},{value:"carte_sej",title:"Carte de séjour"},{value:"recep",title:"Récépissé de la carte nationale d’identité "}],I=f(),H=()=>{var l;(l=I.value)==null||l.validate().then(async({valid:r})=>{if(r){const i={verbal_trial_id:e.value.verbal_trial_id,representative_birth_date:e.value.representative_birth_date,representative_birth_place:e.value.representative_birth_place,representative_nationality:e.value.representative_nationality,representative_home_address:e.value.representative_home_address,representative_phone_number:e.value.representative_phone_number,number_of_pret:e.value.number_of_pret,montant_fudiciaire:e.value.montant_fudiciaire,montant_second_ech:e.value.montant_second_ech,montant_troisieme_ech:e.value.montant_troisieme_ech,representative_type_of_identity_document:e.value.representative_type_of_identity_document,date_of_first_echeance:e.value.date_of_first_echeance,date_of_last_echeance:e.value.date_of_last_echeance,representative_number_of_identity_document:e.value.representative_number_of_identity_document,representative_office_delivery:e.value.representative_office_delivery,representative_date_of_issue_of_identity_document:e.value.representative_date_of_issue_of_identity_document,total_amount_of_interest:e.value.total_amount_of_interest,due_amount:e.value.due_amount,number_of_due_dates:e.value.number_of_due_dates,type:e.value.type,has_pledges:e.value.has_pledges};e.value.type=="company"?(i.company_denomination=e.value.company_denomination,i.company_legal_status=e.value.company_legal_status,i.company_bp=e.value.company_bp,i.company_nif=e.value.company_nif,i.company_commune=e.value.company_commune,i.company_head_office_address=e.value.company_head_office_address,i.company_rccm_number=e.value.company_rccm_number,i.company_phone_number=e.value.company_phone_number):e.value.type=="individual_business"&&(i.individual_business_denomination=e.value.individual_business_denomination,i.individual_business_head_office_address=e.value.individual_business_head_office_address,i.individual_business_rccm_number=e.value.individual_business_rccm_number,i.individual_business_nif_number=e.value.individual_business_nif_number,i.individual_business_phone_number=e.value.individual_business_phone_number,i.individual_business_bp=e.value.individual_business_bp,i.individual_business_commune=e.value.individual_business_commune),e.value.has_pledges=="1"&&(i.pledges=e.value.pledges);const g=await pe("/contract",{method:"POST",body:i});if(t.value=D(),g.status==201)L.push("/contract");else for(const m in g.errors)g.errors[m].forEach(C=>{t.value[m]+=C+`
`});ue(()=>{var m;(m=I.value)==null||m.resetValidation()})}})},J=l=>{e.value.pledges.splice(l,1)},K=()=>{e.value.pledges.push({type:"vehicule",comment:""})};if(E.query.id){const l=parseInt(E.query.id);M.value.find(r=>r.id==l)&&(e.value.verbal_trial_id=l)}return(l,r)=>{const i=de,g=te,m=ne,C=ie;return c(),N("div",null,[be,a(b,null,{default:u(()=>[a(n,{cols:"12",md:"6",lg:"4"},{default:u(()=>[a(i,{modelValue:y.value,"onUpdate:modelValue":r[0]||(r[0]=s=>y.value=s),label:"Numéro du matricule client"},null,8,["modelValue"])]),_:1}),a(n,{cols:"12",md:"6",lg:"4"},{default:u(()=>[a(i,{modelValue:V.value,"onUpdate:modelValue":r[1]||(r[1]=s=>V.value=s),label:"Numéro du prêt"},null,8,["modelValue"])]),_:1})]),_:1}),a(ce,{ref_key:"refForm",ref:I,onSubmit:Y(H,["prevent"])},{default:u(()=>[a(b,null,{default:u(()=>[a(n,{md:"12"},{default:u(()=>[a(h,{class:"mb-6",title:"Information sur contrat"},{default:u(()=>[a(q,null,{default:u(()=>[a(b,null,{default:u(()=>[a(n,{cols:"12",md:"6",lg:"4"},{default:u(()=>[a(g,{modelValue:e.value.verbal_trial_id,"onUpdate:modelValue":r[2]||(r[2]=s=>e.value.verbal_trial_id=s),items:d(M),"error-messages":t.value.verbal_trial_id,label:"Procès verbal",rules:["requiredValidator"in l?l.requiredValidator:d(o)],"item-title":"label","item-value":"id"},null,8,["modelValue","items","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6",lg:"4"},{default:u(()=>[a(i,{modelValue:e.value.total_amount_of_interest,"onUpdate:modelValue":r[3]||(r[3]=s=>e.value.total_amount_of_interest=s),type:"number","error-messages":t.value.total_amount_of_interest,label:"Montant total des intérêts",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6",lg:"4"},{default:u(()=>[a(i,{modelValue:e.value.montant_fudiciaire,"onUpdate:modelValue":r[4]||(r[4]=s=>e.value.montant_fudiciaire=s),type:"number","error-messages":t.value.montant_fudiciaire,label:"Montant transfère fudiciaire",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6",lg:"4"},{default:u(()=>[a(m,{modelValue:e.value.date_of_first_echeance,"onUpdate:modelValue":r[5]||(r[5]=s=>e.value.date_of_first_echeance=s),"error-messages":t.value.date_of_first_echeance,label:"Date première écheance",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6",lg:"4"},{default:u(()=>[a(m,{modelValue:e.value.date_of_last_echeance,"onUpdate:modelValue":r[6]||(r[6]=s=>e.value.date_of_last_echeance=s),"error-messages":t.value.date_of_last_echeance,label:"Date dernière écheance",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"4",lg:"4"},{default:u(()=>[a(i,{modelValue:e.value.number_of_due_dates,"onUpdate:modelValue":r[7]||(r[7]=s=>e.value.number_of_due_dates=s),type:"number","error-messages":t.value.number_of_due_dates,label:"Nombre d'échéance",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"4",lg:"4"},{default:u(()=>[a(i,{modelValue:e.value.due_amount,"onUpdate:modelValue":r[8]||(r[8]=s=>e.value.due_amount=s),type:"number","error-messages":t.value.due_amount,label:"Montant intercalaire",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"4",lg:"4"},{default:u(()=>[a(i,{modelValue:e.value.montant_second_ech,"onUpdate:modelValue":r[9]||(r[9]=s=>e.value.montant_second_ech=s),type:"number","error-messages":t.value.montant_second_ech,label:"Montant echéance",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"4",lg:"4"},{default:u(()=>[a(i,{modelValue:e.value.montant_troisieme_ech,"onUpdate:modelValue":r[10]||(r[10]=s=>e.value.montant_troisieme_ech=s),type:"number","error-messages":t.value.montant_troisieme_ech,label:"Montant dernière échéance",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"4",lg:"4"},{default:u(()=>[a(i,{modelValue:e.value.number_of_pret,"onUpdate:modelValue":r[11]||(r[11]=s=>e.value.number_of_pret=s),"error-messages":t.value.number_of_pret,label:"Numéro de prêt",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"4",lg:"4"},{default:u(()=>[a(C,{modelValue:e.value.type,"onUpdate:modelValue":r[12]||(r[12]=s=>e.value.type=s),items:G,"error-messages":t.value.type,label:"Type",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"2"},{default:u(()=>[a(oe,{modelValue:e.value.has_pledges,"onUpdate:modelValue":r[13]||(r[13]=s=>e.value.has_pledges=s),"true-value":"1","false-value":"0",label:Z[e.value.has_pledges],"error-messages":t.value.has_pledges,"true-icon":"tabler-check","false-icon":"tabler-circle-x",color:"success"},null,8,["modelValue","label","error-messages"])]),_:1})]),_:1})]),_:1})]),_:1}),e.value.has_pledges=="1"?(c(),P(h,{key:0,class:"mb-6",title:"Informations sur les gages"},{default:u(()=>[a(q,{class:"add-products-form"},{default:u(()=>[(c(!0),N(ae,null,le(e.value.pledges,(s,Q)=>(c(),N("div",Ve,[a(_e,{id:Q,data:s,onRemovePledge:J},null,8,["id","data"])]))),256)),p("div",ye,[a(k,{"prepend-icon":"tabler-plus",onClick:K},{default:u(()=>[A(" Ajouter ")]),_:1})])]),_:1})]),_:1})):j("",!0),a(h,{class:"mb-6",title:"Information sur le client"},{default:u(()=>[a(q,null,{default:u(()=>[a(b,null,{default:u(()=>[a(n,{cols:"12",md:"6",lg:"4"},{default:u(()=>[a(m,{modelValue:e.value.representative_birth_date,"onUpdate:modelValue":r[14]||(r[14]=s=>e.value.representative_birth_date=s),"error-messages":t.value.representative_birth_date,label:"Date de naissance",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6",lg:"4"},{default:u(()=>[a(i,{modelValue:e.value.representative_birth_place,"onUpdate:modelValue":r[15]||(r[15]=s=>e.value.representative_birth_place=s),"error-messages":t.value.representative_birth_place,label:"Lieu de naissance",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6",lg:"4"},{default:u(()=>[a(i,{modelValue:e.value.representative_nationality,"onUpdate:modelValue":r[16]||(r[16]=s=>e.value.representative_nationality=s),"error-messages":t.value.representative_nationality,label:"Nationalité",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6",lg:"4"},{default:u(()=>[a(i,{modelValue:e.value.representative_home_address,"onUpdate:modelValue":r[17]||(r[17]=s=>e.value.representative_home_address=s),"error-messages":t.value.representative_home_address,label:"Addresse du domicile",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6",lg:"4"},{default:u(()=>[a(C,{modelValue:e.value.representative_type_of_identity_document,"onUpdate:modelValue":r[18]||(r[18]=s=>e.value.representative_type_of_identity_document=s),items:z,"error-messages":t.value.representative_type_of_identity_document,label:"Type de la pièce d'identité",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6",lg:"4"},{default:u(()=>[a(i,{modelValue:e.value.representative_number_of_identity_document,"onUpdate:modelValue":r[19]||(r[19]=s=>e.value.representative_number_of_identity_document=s),"error-messages":t.value.representative_number_of_identity_document,label:"Numéro de la pièce d'identité",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6",lg:"4"},{default:u(()=>[a(i,{modelValue:e.value.representative_office_delivery,"onUpdate:modelValue":r[20]||(r[20]=s=>e.value.representative_office_delivery=s),"error-messages":t.value.representative_office_delivery,label:"Delivré par",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6",lg:"6"},{default:u(()=>[a(m,{modelValue:e.value.representative_date_of_issue_of_identity_document,"onUpdate:modelValue":r[21]||(r[21]=s=>e.value.representative_date_of_issue_of_identity_document=s),"error-messages":t.value.representative_date_of_issue_of_identity_document,label:"Date de délivrance de la pièce d'identité",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6",lg:"6"},{default:u(()=>[a(i,{modelValue:e.value.representative_phone_number,"onUpdate:modelValue":r[22]||(r[22]=s=>e.value.representative_phone_number=s),"error-messages":t.value.representative_phone_number,label:"Numéro de téléphone",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1})]),_:1})]),_:1})]),_:1}),e.value.type=="company"||e.value.type=="professions_libérales"||e.value.type=="ong"?(c(),P(h,{key:1,class:"mb-6",title:"Information sur la société"},{default:u(()=>[a(q,null,{default:u(()=>[a(b,{cols:"12"},{default:u(()=>[a(n,{cols:"12",md:"6",lg:"6"},{default:u(()=>[a(i,{modelValue:e.value.company_denomination,"onUpdate:modelValue":r[23]||(r[23]=s=>e.value.company_denomination=s),"error-messages":t.value.company_denomination,label:"Dénomination",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6",lg:"6"},{default:u(()=>[a(i,{modelValue:e.value.company_legal_status,"onUpdate:modelValue":r[24]||(r[24]=s=>e.value.company_legal_status=s),"error-messages":t.value.company_legal_status,label:"Forme juridique",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6",lg:"6"},{default:u(()=>[a(i,{modelValue:e.value.company_rccm_number,"onUpdate:modelValue":r[25]||(r[25]=s=>e.value.company_rccm_number=s),"error-messages":t.value.company_rccm_number,label:"Numero RCCM",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6",lg:"6"},{default:u(()=>[a(i,{modelValue:e.value.company_phone_number,"onUpdate:modelValue":r[26]||(r[26]=s=>e.value.company_phone_number=s),"error-messages":t.value.company_phone_number,label:"Telephone de la société",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6"},{default:u(()=>[a(i,{modelValue:e.value.company_bp,"onUpdate:modelValue":r[27]||(r[27]=s=>e.value.company_bp=s),"error-messages":t.value.company_bp,label:"BP",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6"},{default:u(()=>[a(i,{modelValue:e.value.company_commune,"onUpdate:modelValue":r[28]||(r[28]=s=>e.value.company_commune=s),"error-messages":t.value.company_commune,label:"Commune",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6"},{default:u(()=>[a(i,{modelValue:e.value.company_nif,"onUpdate:modelValue":r[29]||(r[29]=s=>e.value.company_nif=s),"error-messages":t.value.company_nif,label:"NIF",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12"},{default:u(()=>[a(i,{modelValue:e.value.company_head_office_address,"onUpdate:modelValue":r[30]||(r[30]=s=>e.value.company_head_office_address=s),"error-messages":t.value.company_head_office_address,label:"Addresse du siège social",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1})]),_:1})]),_:1})]),_:1})):j("",!0),e.value.type=="individual_business"?(c(),P(h,{key:2,class:"mb-6",title:"Information sur l'entreprise individuele"},{default:u(()=>[a(q,null,{default:u(()=>[a(b,{cols:"12"},{default:u(()=>[a(n,{cols:"12",md:"6",lg:"6"},{default:u(()=>[a(i,{modelValue:e.value.individual_business_denomination,"onUpdate:modelValue":r[31]||(r[31]=s=>e.value.individual_business_denomination=s),"error-messages":t.value.individual_business_denomination,label:"Dénomination",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6",lg:"6"},{default:u(()=>[a(i,{modelValue:e.value.individual_business_head_office_address,"onUpdate:modelValue":r[32]||(r[32]=s=>e.value.individual_business_head_office_address=s),"error-messages":t.value.individual_business_head_office_address,label:"Addresse du siège social",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6",lg:"6"},{default:u(()=>[a(i,{modelValue:e.value.individual_business_commune,"onUpdate:modelValue":r[33]||(r[33]=s=>e.value.individual_business_commune=s),"error-messages":t.value.individual_business_commune,label:"Commune",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6",lg:"6"},{default:u(()=>[a(i,{modelValue:e.value.individual_business_bp,"onUpdate:modelValue":r[34]||(r[34]=s=>e.value.individual_business_bp=s),"error-messages":t.value.individual_business_bp,label:"BP",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6",lg:"6"},{default:u(()=>[a(i,{modelValue:e.value.individual_business_rccm_number,"onUpdate:modelValue":r[35]||(r[35]=s=>e.value.individual_business_rccm_number=s),"error-messages":t.value.individual_business_rccm_number,label:"Numero RCCM",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6",lg:"6"},{default:u(()=>[a(i,{modelValue:e.value.individual_business_nif_number,"onUpdate:modelValue":r[36]||(r[36]=s=>e.value.individual_business_nif_number=s),"error-messages":t.value.individual_business_nif_number,label:"Numero NIF",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1}),a(n,{cols:"12",md:"6",lg:"6"},{default:u(()=>[a(i,{modelValue:e.value.individual_business_phone_number,"onUpdate:modelValue":r[37]||(r[37]=s=>e.value.individual_business_phone_number=s),"error-messages":t.value.individual_business_phone_number,label:"Telephone de la société",rules:["requiredValidator"in l?l.requiredValidator:d(o)]},null,8,["modelValue","error-messages","rules"])]),_:1})]),_:1})]),_:1})]),_:1})):j("",!0)]),_:1}),a(n,{cols:"12"},{default:u(()=>[p("div",ge,[he,p("div",qe,[a(k,{type:"reset",variant:"tonal",color:"primary"},{default:u(()=>[a($,{start:"",icon:"tabler-circle-minus"}),A(" Effacer ")]),_:1}),a(k,{type:"submit",class:"me-3"},{default:u(()=>[A(" Enregistrer "),a($,{end:"",icon:"tabler-checkbox"})]),_:1})])])]),_:1})]),_:1})]),_:1},512)])}}},Ye=fe(Ue,[["__scopeId","data-v-10c6754f"]]);export{Ye as default};
