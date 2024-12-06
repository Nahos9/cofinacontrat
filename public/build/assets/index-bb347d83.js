import{_ as ge}from"./AppTextarea-6d6d429f.js";import{_ as ye}from"./DialogCloseBtn-469f552f.js";import{_ as be}from"./AppTextField-6136aa1e.js";import{_ as he}from"./AppSelect-ec953df4.js";import{V as Ve,p as ke,a as we}from"./VPagination-c405f961.js";import"./js-file-downloader-6f69effd.js";import{$ as oe}from"./api-0fd30a7b.js";import{r as c,ah as $e,a1 as J,c as T,b as e,e as t,s as l,$ as w,E as Ce,a as xe,o as s,d as h,a3 as V,M as r,f as d,N as i,Z as u,x as g,F as Te,A as je,z as ne,aa as Q,C as Ae,D as Ee}from"./main-a1bb8d1c.js";import{u as Se}from"./useApi-ee532938.js";import{c as Pe}from"./createUrl-82711664.js";import{_ as De}from"./_plugin-vue_export-helper-c27b6911.js";import{V as Z}from"./VCard-0a0db0fa.js";import{V as Ie}from"./VDialog-206f78ae.js";import{V as E}from"./VCardText-ec067fb4.js";import{V as le}from"./VRow-4fd62cc5.js";import{V as Re}from"./VCol-c9fbba03.js";import{V as $}from"./VDivider-bdae5753.js";import{V as Ue}from"./VSpacer-75169579.js";import{a as C,b as j,V as G}from"./VList-63aa9283.js";import{V as re}from"./VSelect-00885f47.js";import{V as x}from"./VTooltip-2ee1d7e2.js";import{V as Be}from"./VMenu-e4df9f05.js";import{V as Le}from"./VBadge-1de13457.js";import"./VTextField-251e47e8.js";import"./VImg-c3c51305.js";import"./forwardRefs-6ea3df5c.js";import"./VTable-b1027623.js";import"./filter-22cdad36.js";import"./index-9c720871.js";import"./useAbility-c9217b08.js";import"./VAvatar-28a02eb0.js";import"./VOverlay-5a96f554.js";const ze=S=>(Ae("data-v-067c51cd"),S=S(),Ee(),S),Fe=ze(()=>h("h2",null,"Liste des contrats",-1)),Ne={class:"d-flex flex-wrap gap-4 mx-5"},Me={class:"d-flex align-center"},Oe={class:"d-flex gap-4 flex-wrap align-center"},qe={class:"custom-loader"},He=["onInput"],Je={key:2},Qe={key:3},Ze={key:0},Ge={key:1},Ke={class:"d-flex align-center justify-space-between flex-wrap gap-3 pa-5 pt-3"},We={class:"text-sm text-medium-emphasis mb-0"},Xe={__name:"index",async setup(S){let P,K;const se=Ce(),D=c(),I=c(""),W=c(),X=c("signed_contract"),R=c(0),_=c(!1),U=c(""),B=c(""),L=c(""),z=c(),A=c(""),F=c(!1),N=c("waiting"),M=c([]),ie=[{title:"Numéro comitée",key:"verbal_trial.committee_id"},{title:"Admin Crédit",key:"creator.full_name"},{title:"Nom client",key:"verbal_trial.applicant_full_name"},{title:"Type de contrat",key:"type"},{title:"Montant",key:"verbal_trial.amount"},{title:"Observations",key:"observations"},{title:"Actions",key:"actions",sortable:!1}],ce={company:"Société",individual_business:"Entreprise Individuel",particular:"Particulier"},de=o=>{M.value[o]=!0,setTimeout(()=>{M.value[o]=!1},1e3)},O=c(8),y=c(1),ue=o=>{y.value=o.page},{data:H,execute:q}=([P,K]=$e(()=>Se(Pe("/contract",{query:{search:I,type:D,page:y,with_type_of_credit:1,with_company:1,with_individual_business:1,with_creator:1,has_cat:0}}))),P=await P,K(),P),pe=async(o,n)=>{try{const m=await fetch(o,{headers:{Authorization:`Bearer ${Q("userToken").value}`,Accept:"application/pdf"}});if(!m.ok)throw new Error(`Erreur HTTP: ${m.status}`);const p=await m.blob(),f=await p.text();if(console.log("Contenu du fichier téléchargé :",f),p.type!=="application/pdf")throw new Error("Le fichier téléchargé n'est pas un PDF.");const v=window.URL.createObjectURL(p),k=document.createElement("a");k.href=v,k.download=n,document.body.appendChild(k),k.click(),k.remove()}catch(m){console.error("Erreur lors du téléchargement:",m)}},me=async(o,n)=>{const{files:m}=n.target;if(m&&m.length===1){const p=new FileReader;p.onload=async()=>{const f=p.result;try{(await fetch(`/api/contract/upload/${o}`,{method:"POST",headers:{"Content-Type":"application/json",Authorization:`Bearer ${Q("userToken").value}`},body:JSON.stringify({[X.value]:f})})).ok?(console.log("Document envoyé avec succès."),q()):console.error("Échec de l'envoi du document.")}catch(v){console.error("Erreur lors de l'envoi du document:",v)}},p.readAsDataURL(m[0])}else console.error("Veuillez sélectionner un seul fichier.")},fe=async o=>{await oe(`contract/${o}`,{method:"DELETE"}),q()},Y=async o=>{await oe(`contract/change-status/${o}`,{method:"PUT",body:{status:N.value,comment:A.value}}),A.value="",N.value=="validated"&&se.push(`/cat/add?id=${o}`),q()},ve=J(()=>H.value.data),ee=J(()=>H.value.total),te=J(()=>H.value.last_page),_e=async o=>{const n=`/api/contract/download/${o}`,m=`contracts-${o}.zip`;try{const p=await fetch(n,{headers:{Authorization:`Bearer ${Q("userToken").value}`,Accept:"application/json"}});if(!p.ok)throw new Error("Erreur lors du téléchargement du fichier");const f=await p.blob(),v=document.createElement("a");v.href=URL.createObjectURL(f),v.download=m,document.body.appendChild(v),v.click(),document.body.removeChild(v)}catch(p){console.error("Erreur lors du téléchargement:",p)}};return(o,n)=>{const m=he,p=be,f=xe("IconBtn"),v=ye,k=ge;return s(),T("div",null,[e(Z,{class:"mb-6"},{default:t(()=>[e(E,null,{default:t(()=>[e(le,null,{default:t(()=>[e(E,null,{default:t(()=>[Fe]),_:1})]),_:1})]),_:1})]),_:1}),e(Z,{title:"Filtres",class:"mb-6"},{default:t(()=>[e(E,null,{default:t(()=>[e(le,null,{default:t(()=>[e(Re,{cols:"12",sm:"4"},{default:t(()=>[e(m,{modelValue:l(D),"onUpdate:modelValue":n[0]||(n[0]=a=>w(D)?D.value=a:null),placeholder:"Type de contrat",items:[{value:"company",title:"Société"},{value:"particular",title:"Particulier"},{value:"individual_business",title:"Entreprise Individuel"}],clearable:"","clear-icon":"tabler-x"},null,8,["modelValue"])]),_:1})]),_:1})]),_:1}),e($,{class:"my-4"}),h("div",Ne,[h("div",Me,[e(p,{modelValue:l(I),"onUpdate:modelValue":n[1]||(n[1]=a=>w(I)?I.value=a:null),placeholder:"Rechercher un contrat",density:"compact",style:{"inline-size":"200px"},class:"me-3"},null,8,["modelValue"])]),e(Ue),h("div",Oe,[e(V,{variant:"tonal",color:"secondary","prepend-icon":"tabler-download"},{default:t(()=>[r(" Export ")]),_:1}),o.$can("create","contract")?(s(),d(V,{key:0,color:"primary","prepend-icon":"tabler-plus",to:{name:"contract-add"}},{default:t(()=>[r(" Ajouter ")]),_:1})):i("",!0),e(V,{loading:l(M)[3],disabled:l(M)[3],"prepend-icon":"tabler-refresh",onClick:n[2]||(n[2]=a=>{l(q)(),de(3)})},{loader:t(()=>[h("span",qe,[e(u,{icon:"tabler-refresh"})])]),default:t(()=>[r(" Recharger ")]),_:1},8,["loading","disabled"])])]),e($,{class:"mt-4"}),e(l(Ve),{"items-per-page":l(O),"onUpdate:itemsPerPage":n[5]||(n[5]=a=>w(O)?O.value=a:null),page:l(y),"onUpdate:page":n[6]||(n[6]=a=>w(y)?y.value=a:null),headers:ie,items:l(ve),"items-length":l(ee),class:"text-no-wrap","onUpdate:options":ue},{"item.type":t(({item:a})=>[r(g(ce[a.type]),1)]),"item.verbal_trial.amount":t(({item:a})=>[r(g(String(a.verbal_trial.amount).replace(/\B(?=(\d{3})+(?!\d))/g," "))+" F CFA ",1)]),"item.observations":t(({item:a})=>[a.observations.length>0?(s(),d(G,{key:0,density:" compact"},{default:t(()=>[(s(!0),T(Te,null,je(a.observations,b=>(s(),d(C,null,{default:t(()=>[e(j,null,{default:t(()=>[e(re,{label:""},{default:t(()=>[r(g(b),1)]),_:2},1024)]),_:2},1024)]),_:2},1024))),256))]),_:2},1024)):i("",!0),a.observations.length==0?(s(),d(G,{key:1,density:"compact"},{default:t(()=>[e(C,null,{default:t(()=>[e(re,{label:"",color:{validated:"success",rejected:"error",waiting:"warning"}[a.status]},{default:t(()=>[a.status_observation?(s(),d(x,{key:0,activator:"parent",transition:"scroll-x-transition",location:"start"},{default:t(()=>[r("Raison: "+g(a.status_observation),1)]),_:2},1024)):i("",!0),r(" "+g(a.status=="validated"?"Dossier validé":null)+" "+g(a.status=="waiting"?"Dossier en attente de validation":null)+" "+g(a.status=="rejected"?"Dossier rejeté":null),1)]),_:2},1032,["color"])]),_:2},1024)]),_:2},1024)):i("",!0)]),"item.actions":t(({item:a})=>[h("span",null,[e(f,{to:{name:"contract-id",params:{id:a.id}}},{default:t(()=>[e(x,{activator:"parent",transition:"scroll-x-transition",location:"start"},{default:t(()=>[r("Details")]),_:1}),e(u,{icon:"tabler-eye"})]),_:2},1032,["to"]),e(V,{icon:"",variant:"text",size:"small",color:"medium-emphasis"},{default:t(()=>[e(u,{size:"24",icon:"tabler-dots-vertical"}),e(Be,{activator:"parent"},{default:t(()=>[e(G,null,{default:t(()=>[h("input",{ref_key:"refInputEl",ref:W,type:"file",name:"signed_contract",accept:".pdf,.png,.jpg",hidden:"",onInput:b=>me(a.id,b)},null,40,He),o.$can("read","guarantor")?(s(),d(Le,{key:0,inline:"",content:a.guarantors_count},{default:t(()=>[e(C,{to:{name:"contract-contract_id-guarantor",params:{contract_id:a.id}}},{prepend:t(()=>[e(u,{icon:"tabler-users"})]),default:t(()=>[e(j,null,{default:t(()=>[r(" Voir les Cautions ")]),_:1})]),_:2},1032,["to"])]),_:2},1032,["content"])):i("",!0),o.$can("read","pv")?(s(),d(C,{key:1,to:{name:"pv-id",params:{id:a.verbal_trial.id}}},{prepend:t(()=>[e(u,{icon:"tabler-eye"})]),default:t(()=>[e(j,null,{default:t(()=>[r("Voir le Pv")]),_:1})]),_:2},1032,["to"])):i("",!0),o.$can("download","contract")?(s(),T("div",Je,[e($),e(C,{onClick:b=>_e(a.id)},{prepend:t(()=>[e(u,{icon:"tabler-download"})]),default:t(()=>[e(j,null,{default:t(()=>[r("Télécharger Contrat non-signé")]),_:1})]),_:2},1032,["onClick"]),a.signed_contract_path?(s(),d(C,{key:0,onClick:b=>pe(a.signed_contract_path,`Contrat-${a.signed_contract_path.split("/").slice(-1)[0]}`)},{prepend:t(()=>[e(u,{icon:"tabler-download"})]),default:t(()=>[e(j,null,{default:t(()=>[r("Télécharger Contrat signé")]),_:1})]),_:2},1032,["onClick"])):i("",!0)])):i("",!0),o.$can("upload","contract")?(s(),T("div",Qe,[e($),a.signed_contract_path==null||a.status=="rejected"?(s(),d(C,{key:0,onClick:n[3]||(n[3]=b=>{var ae;X.value="signed_contract",(ae=l(W))==null||ae.click()})},{prepend:t(()=>[e(u,{icon:"tabler-cloud-upload"})]),default:t(()=>[e(j,{color:"error"},{default:t(()=>[r("Ajouter contrat signé")]),_:1})]),_:1})):i("",!0)])):i("",!0)]),_:2},1024)]),_:2},1024)]),_:2},1024)]),o.$can("update","contract")||o.$can("delete","contract")?(s(),T("span",Ze,[e($),o.$can("update","contract")?(s(),d(f,{key:0,to:{name:"contract-edit-id",params:{id:a.id}},disabled:a.status=="validated"},{default:t(()=>[e(x,{activator:"parent",transition:"scroll-x-transition",location:"start"},{default:t(()=>[r("Modifier")]),_:1}),e(u,{icon:"tabler-edit"})]),_:2},1032,["to","disabled"])):i("",!0),o.$can("delete","contract")?(s(),d(f,{key:1,onClick:b=>{R.value=a.id,U.value="Supprimer le contrat",B.value="Voulez vous vraiment supprimer ce contrat?",z.value=fe,L.value="Supprimer",F.value=!1,_.value=!0}},{default:t(()=>[e(x,{activator:"parent",transition:"scroll-x-transition",location:"end"},{default:t(()=>[r("Supprimer")]),_:1}),e(u,{icon:"tabler-trash",color:"error"})]),_:2},1032,["onClick"])):i("",!0)])):i("",!0),o.$can("reject","contract")||o.$can("validate","contract")||o.$can("create","cat")?(s(),T("span",Ge,[e($),o.$can("reject","contract")&&a.status!="rejected"&&a.observations.length==0?(s(),d(f,{key:0,onClick:b=>{R.value=a.id,U.value="Rejeter le contrat",B.value="Voulez vous vraiment rejeter ce contrat?",z.value=Y,L.value="Rejeter",F.value=!0,N.value="rejected",_.value=!0}},{default:t(()=>[e(x,{activator:"parent",transition:"scroll-x-transition",location:"start"},{default:t(()=>[r("Rejeter")]),_:1}),e(u,{icon:"tabler-x",color:"error"})]),_:2},1032,["onClick"])):i("",!0),o.$can("validate","contract")&&a.status=="waiting"&&a.observations.length==0?(s(),d(f,{key:1,onClick:b=>{R.value=a.id,U.value="Valider le contrat",B.value="Voulez vous vraiment valider ce contrat?",z.value=Y,L.value="Valider",F.value=!1,N.value="validated",_.value=!0}},{default:t(()=>[e(x,{activator:"parent",transition:"scroll-x-transition",location:"end"},{default:t(()=>[r("Valider")]),_:1}),e(u,{icon:"tabler-check",color:"success"})]),_:2},1032,["onClick"])):i("",!0),o.$can("create","cat")&&a.status=="validated"?(s(),d(f,{key:2,to:{name:"cat-add",query:{id:a.id}}},{default:t(()=>[e(x,{activator:"parent",transition:"scroll-x-transition",location:"end"},{default:t(()=>[r("Créer le CAT")]),_:1}),e(u,{icon:"tabler-file-plus",color:"success"})]),_:2},1032,["to"])):i("",!0)])):i("",!0)]),bottom:t(()=>[e($),h("div",Ke,[h("p",We,g(l(ke)({page:l(y),itemsPerPage:l(O)},l(ee))),1),e(we,{modelValue:l(y),"onUpdate:modelValue":n[4]||(n[4]=a=>w(y)?y.value=a:null),length:l(te),"total-visible":o.$vuetify.display.xs?1:Math.min(l(te),5)},{prev:t(a=>[e(V,ne({variant:"tonal",color:"default"},a,{icon:!1}),{default:t(()=>[e(u,{start:"",icon:"tabler-arrow-left"}),r(" Précedent ")]),_:2},1040)]),next:t(a=>[e(V,ne({variant:"tonal",color:"default"},a,{icon:!1}),{default:t(()=>[r(" Suivant "),e(u,{end:"",icon:"tabler-arrow-right"})]),_:2},1040)]),_:1},8,["modelValue","length","total-visible"])])]),_:1},8,["items-per-page","page","items","items-length"])]),_:1}),e(Ie,{modelValue:l(_),"onUpdate:modelValue":n[11]||(n[11]=a=>w(_)?_.value=a:null),class:"v-dialog-sm"},{default:t(()=>[e(v,{onClick:n[7]||(n[7]=a=>_.value=!l(_))}),e(Z,{title:l(U)},{default:t(()=>[e(E,null,{default:t(()=>[r(g(l(B))+" ",1),l(F)?(s(),d(k,{key:0,class:"mt-3",modelValue:l(A),"onUpdate:modelValue":n[8]||(n[8]=a=>w(A)?A.value=a:null),label:"Commentaire",placeholder:"Ex: RAS"},null,8,["modelValue"])):i("",!0)]),_:1}),e(E,{class:"d-flex justify-end gap-3 flex-wrap"},{default:t(()=>[e(V,{color:"secondary",variant:"tonal",onClick:n[9]||(n[9]=a=>_.value=!1)},{default:t(()=>[r(" Annuler ")]),_:1}),e(V,{onClick:n[10]||(n[10]=a=>{l(z)(l(R)),_.value=!1})},{default:t(()=>[r(g(l(L)),1)]),_:1})]),_:1})]),_:1},8,["title"])]),_:1},8,["modelValue"])])}}},St=De(Xe,[["__scopeId","data-v-067c51cd"]]);export{St as default};