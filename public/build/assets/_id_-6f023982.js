import{ah as h,s as n,c,b as t,e as a,N as V,E as x,n as g,o as _,a3 as f,Z as b,M as m,d as o,x as d,F as w,A as C}from"./main-35f434e5.js";import{u as N}from"./useApi-f6bf8dd7.js";import{c as k}from"./createUrl-376a4625.js";import{V as T}from"./VRow-5bf9819c.js";import{V as i}from"./VCol-bbc947c1.js";import{V as A}from"./VCard-de226d5c.js";import{V as B}from"./VCardText-2ef94eba.js";import{V as D}from"./VTable-b3fce31e.js";import"./index-9c720871.js";import"./VAvatar-5cd70722.js";import"./VImg-fb08db81.js";const F={key:0},L={class:"text-center"},R=o("h2",null,"Informations:",-1),E={colspan:"5"},I={colspan:"1"},Q={__name:"[id]",async setup(M){let s,u;const p=x(),l=g(),v={cni:"Carte d'identité nationale",passport:"Passeport",residence_certificate:"Certificat de résidence",driving_licence:"Permis de conduire"},{data:e}=([s,u]=h(()=>N(k(`/guarantor/${l.params.id}`,{query:{}}))),s=await s,u(),s);e.value.status==200?e.value=e.value.data.guarantor:p.push("/guarantor");const y=[{title:"Nom",value:e.value.last_name},{title:"Date de naissance",value:e.value.birth_date},{title:"Lieu de naissance",value:e.value.birth_place},{title:"Nationalité",value:e.value.nationality},{title:"Addresse du domicile",value:e.value.home_address},{title:"Type de la pièce d'identité",value:v[e.value.type_of_identity_document]},{title:"Numéro de la pièce d'identité",value:e.value.number_of_identity_document},{title:"Date de délivrance de la pièce d'identité",value:e.value.date_of_issue_of_identity_document},{title:"Fonction",value:e.value.function},{title:"Numéro de téléphone",value:e.value.phone_number}];return(P,j)=>n(e)?(_(),c("section",F,[t(T,null,{default:a(()=>[t(i,{cols:"12"},{default:a(()=>[t(A,null,{default:a(()=>[t(B,{class:"d-flex flex-wrap justify-space-between flex-column flex-sm-row print-row text-lg"},{default:a(()=>[t(i,{cols:"11"},{default:a(()=>[t(f,{to:{name:"notification-notification_id-guarantor",params:{notification_id:n(l).params.notification_id}}},{default:a(()=>[t(b,{icon:"tabler-arrow-left"}),m(" Cautions ")]),_:1},8,["to"])]),_:1}),t(i,{cols:"1"},{default:a(()=>[t(f,{to:{name:"notification-notification_id-guarantor-edit-id",params:{notification_id:n(l).params.notification_id,id:n(e).id}}},{default:a(()=>[m(" Modifier ")]),_:1},8,["to"])]),_:1}),t(i,{cols:"12"},{default:a(()=>[o("h2",L," Caution N°"+d(n(e).id),1)]),_:1}),t(i,{cols:"12"},{default:a(()=>[R]),_:1}),t(i,{cols:"12"},{default:a(()=>[t(D,{class:"text-no-wrap"},{default:a(()=>[o("tbody",null,[(_(),c(w,null,C(y,r=>o("tr",{key:r.key},[o("td",E,d(r.title),1),o("td",I,d(r.value),1)])),64))])]),_:1})]),_:1})]),_:1})]),_:1})]),_:1})]),_:1})])):V("",!0)}};export{Q as default};
