import{_ as de}from"./DialogCloseBtn-fa0ad85d.js";import{_ as ce}from"./AppTextField-4144e217.js";import{V as ue,p as pe,a as me}from"./VPagination-8403895c.js";import{J as _e}from"./js-file-downloader-8d1ab2ff.js";import{r as v,ah as J,a1 as L,c as $,b as e,e as t,s as r,$ as x,n as fe,a as ge,o as s,d as h,a3 as m,M as n,f as _,N as c,Z as i,F as G,A as q,x as N,z as Q,aa as Z}from"./main-35f434e5.js";import{u as H}from"./useApi-f6bf8dd7.js";import{c as K}from"./createUrl-376a4625.js";import{$ as ve}from"./api-bc7c6e86.js";import{_ as ye}from"./_plugin-vue_export-helper-c27b6911.js";import{V as W}from"./VCard-de226d5c.js";import{V as be}from"./VDialog-85fb74b8.js";import{V as ke}from"./VRow-5bf9819c.js";import{V as X}from"./VCol-bbc947c1.js";import{V as he}from"./VSpacer-ccd05c4b.js";import{V as I}from"./VDivider-2199d20e.js";import{V as Y,b as y,a as b}from"./VList-a84ed864.js";import{V as ee}from"./VSelect-8eaa7a4a.js";import{V as R}from"./VTooltip-14162ccf.js";import{V as Ve}from"./VMenu-ad7875d9.js";import{V as te}from"./VCardText-2ef94eba.js";import"./VTextField-31ac9b36.js";import"./VImg-fb08db81.js";import"./forwardRefs-6ea3df5c.js";import"./VTable-b3fce31e.js";import"./filter-e1345ab9.js";import"./index-9c720871.js";import"./useAbility-597cc743.js";import"./VAvatar-5cd70722.js";import"./VOverlay-6080deb7.js";const Ce={class:"d-flex flex-wrap gap-4 mx-5"},$e={class:"d-flex align-center"},xe={class:"d-flex gap-4 flex-wrap align-center"},we={class:"custom-loader"},Te=["onInput"],De={key:0},Be={key:1},Ae={class:"d-flex align-center justify-space-between flex-wrap gap-3 pa-5 pt-3"},Ie={class:"text-sm text-medium-emphasis mb-0"},Pe={__name:"index",async setup(Ee){let f,w;const V=fe(),g=v(!1),U=v(0),T=v(""),P=v(),E=v("signed_contract");let z="/contract";const ae=[{title:"Nom",key:"full_name"},{title:"Fonction",key:"function"},{title:"Pièce didentité",key:"number_of_identity_document"},{title:"Numéro de téléphone",key:"phone_number"},{title:"Observations",key:"observations"},{title:"Actions",key:"actions",sortable:!1}],D=v([]),oe=l=>{D.value[l]=!0,setTimeout(()=>{D.value[l]=!1},1e3)},B=v(8),p=v(1),re=l=>{p.value=l.page},{data:S,execute:j}=([f,w]=J(()=>H(K("/guarantor",{query:{search:T,with_verbal_trial:1,contract_id:V.params.contract_id,page:p}}))),f=await f,w(),f),{data:ne}=([f,w]=J(()=>H(K(`/contract/${V.params.contract_id}`))),f=await f,w(),f),le=L(()=>S.value.data),M=L(()=>S.value.total),O=L(()=>S.value.last_page),A=async(l,o)=>{const k=Z("userToken").value;try{new _e({url:l,headers:[{name:"Authorization",value:`Bearer ${k}`},{name:"Accept",value:"application/json"}],nameCallback:function(u){return o}})}catch(u){console.error("Erreur lors du téléchargement:",u)}},se=async(l,o)=>{const{files:k}=o.target;if(k&&k.length===1){const u=new FileReader;u.onload=async()=>{const F=u.result;try{(await fetch(`/api/guarantor/upload/${l}`,{method:"POST",headers:{"Content-Type":"application/json",Authorization:`Bearer ${Z("userToken").value}`},body:JSON.stringify({[E.value]:F})})).ok?j():console.error("Échec de l'envoi du document.")}catch(a){console.error("Erreur lors de l'envoi du document:",a)}},u.readAsDataURL(k[0])}else console.error("Veuillez sélectionner un seul fichier.")},ie=async l=>{await ve(`guarantor/${l}`,{method:"DELETE"}),j()};return ne.value.data.contract.observations.length==0&&(z="/contract/historical"),(l,o)=>{const k=ce,u=ge("IconBtn"),F=de;return s(),$("div",null,[e(W,{title:"Liste des cautions",class:"mb-6"},{default:t(()=>[h("div",Ce,[h("div",$e,[e(ke,null,{default:t(()=>[e(X,null,{default:t(()=>[e(m,{"prepend-icon":"tabler-arrow-left",to:r(z)},{default:t(()=>[n(" Contrats ")]),_:1},8,["to"])]),_:1}),e(X,null,{default:t(()=>[e(k,{modelValue:r(T),"onUpdate:modelValue":o[0]||(o[0]=a=>x(T)?T.value=a:null),placeholder:"Rechercher",density:"compact",style:{"inline-size":"200px"},class:"me-3"},null,8,["modelValue"])]),_:1})]),_:1})]),e(he),h("div",xe,[e(m,{variant:"tonal",color:"secondary","prepend-icon":"tabler-upload"},{default:t(()=>[n(" Export ")]),_:1}),l.$can("create","guarantor")?(s(),_(m,{key:0,color:"primary","prepend-icon":"tabler-plus",to:{name:"contract-contract_id-guarantor-add",params:{contract_id:r(V).params.contract_id}}},{default:t(()=>[n(" Ajouter ")]),_:1},8,["to"])):c("",!0),e(m,{loading:r(D)[3],disabled:r(D)[3],"prepend-icon":"tabler-refresh",onClick:o[1]||(o[1]=a=>{r(j)(),oe(3)})},{loader:t(()=>[h("span",we,[e(i,{icon:"tabler-refresh"})])]),default:t(()=>[n(" Recharger ")]),_:1},8,["loading","disabled"])])]),e(I,{class:"mt-4"}),e(r(ue),{"items-per-page":r(B),"onUpdate:itemsPerPage":o[5]||(o[5]=a=>x(B)?B.value=a:null),page:r(p),"onUpdate:page":o[6]||(o[6]=a=>x(p)?p.value=a:null),headers:ae,items:r(le),"items-length":r(M),class:"text-no-wrap","onUpdate:options":re},{"item.observations":t(({item:a})=>[e(Y,{density:"compact"},{default:t(()=>[(s(!0),$(G,null,q(a.observations,d=>(s(),_(b,null,{default:t(()=>[e(y,null,{default:t(()=>[e(ee,{label:""},{default:t(()=>[n(N(d),1)]),_:2},1024)]),_:2},1024)]),_:2},1024))),256)),a.observations.length==0?(s(),$(G,{key:0},q(["Dossier complet"],d=>e(b,null,{default:t(()=>[e(y,null,{default:t(()=>[e(ee,{color:"success",label:""},{default:t(()=>[n(N(d),1)]),_:2},1024)]),_:2},1024)]),_:2},1024)),64)):c("",!0)]),_:2},1024)]),"item.actions":t(({item:a})=>[l.$can("read","guarantor")?(s(),_(u,{key:0,to:{name:"contract-contract_id-guarantor-id",params:{contract_id:r(V).params.contract_id,id:a.id}}},{default:t(()=>[e(R,{activator:"parent",transition:"scroll-x-transition",location:"top"},{default:t(()=>[n("Details")]),_:1}),e(i,{icon:"tabler-eye"})]),_:2},1032,["to"])):c("",!0),l.$can("update","guarantor")?(s(),_(u,{key:1,to:{name:"contract-contract_id-guarantor-edit-id",params:{contract_id:r(V).params.contract_id,id:a.id}}},{default:t(()=>[e(R,{activator:"parent",transition:"scroll-x-transition",location:"top"},{default:t(()=>[n("Modifier")]),_:1}),e(i,{icon:"tabler-edit"})]),_:2},1032,["to"])):c("",!0),l.$can("delete","guarantor")?(s(),_(u,{key:2,onClick:d=>{U.value=a.id,g.value=!0}},{default:t(()=>[e(R,{activator:"parent",transition:"scroll-x-transition",location:"top"},{default:t(()=>[n("Supprimer")]),_:1}),e(i,{icon:"tabler-trash",color:"error"})]),_:2},1032,["onClick"])):c("",!0),e(m,{icon:"",variant:"text",size:"small",color:"medium-emphasis"},{default:t(()=>[e(i,{size:"24",icon:"tabler-dots-vertical"}),e(Ve,{activator:"parent"},{default:t(()=>[e(Y,null,{default:t(()=>[h("input",{ref_key:"refInputEl",ref:P,type:"file",name:"signed_contract",accept:".pdf,.png,.jpg",hidden:"",onInput:d=>se(a.id,d)},null,40,Te),l.$can("download","guarantor")?(s(),$("div",De,[e(b,{onClick:d=>{console.log(a.contract),A(`/api/guarantor/download/${a.id}`,`Contrat-Caution-${a.contract.verbal_trial.committee_id}.docx`)}},{prepend:t(()=>[e(i,{icon:"tabler-download"})]),default:t(()=>[e(y,null,{default:t(()=>[n("Télécharger Contrat non-signé")]),_:1})]),_:2},1032,["onClick"]),a.signed_contract_path?(s(),_(b,{key:0,onClick:d=>A(a.signed_contract_path,`Contrat-Caution-${a.signed_contract_path.split("/").slice(-1)[0]}`)},{prepend:t(()=>[e(i,{icon:"tabler-download"})]),default:t(()=>[e(y,null,{default:t(()=>[n("Télécharger Contrat signé")]),_:1})]),_:2},1032,["onClick"])):c("",!0),e(b,{onClick:d=>A(`/api/guarantor/promissory-note/download/${a.id}`,`Billet-à-ordre-Caution-${a.contract.verbal_trial.committee_id}.docx`)},{prepend:t(()=>[e(i,{icon:"tabler-download"})]),default:t(()=>[e(y,null,{default:t(()=>[n("Télécharger Billet à ordre non signé")]),_:1})]),_:2},1032,["onClick"]),a.signed_promissory_note_path?(s(),_(b,{key:1,onClick:d=>A(a.signed_promissory_note_path,`Billet-à-ordre-Caution-${a.signed_promissory_note_path.split("/").slice(-1)[0]}`)},{prepend:t(()=>[e(i,{icon:"tabler-download"})]),default:t(()=>[e(y,null,{default:t(()=>[n("Télécharger Billet à ordre signé")]),_:1})]),_:2},1032,["onClick"])):c("",!0)])):c("",!0),l.$can("upload","guarantor")?(s(),$("div",Be,[e(I),a.signed_contract_path==null?(s(),_(b,{key:0,onClick:o[2]||(o[2]=d=>{var C;E.value="signed_contract",(C=r(P))==null||C.click()})},{prepend:t(()=>[e(i,{icon:"tabler-cloud-upload"})]),default:t(()=>[e(y,{color:"error"},{default:t(()=>[n("Ajouter contrat signé")]),_:1})]),_:1})):c("",!0),a.signed_promissory_note_path==null?(s(),_(b,{key:1,onClick:o[3]||(o[3]=d=>{var C;E.value="signed_promissory_note",(C=r(P))==null||C.click()})},{prepend:t(()=>[e(i,{icon:"tabler-cloud-upload"})]),default:t(()=>[e(y,null,{default:t(()=>[n("Ajouter billet à ordre signé")]),_:1})]),_:1})):c("",!0)])):c("",!0),e(I)]),_:2},1024)]),_:2},1024)]),_:2},1024)]),bottom:t(()=>[e(I),h("div",Ae,[h("p",Ie,N(r(pe)({page:r(p),itemsPerPage:r(B)},r(M))),1),e(me,{modelValue:r(p),"onUpdate:modelValue":o[4]||(o[4]=a=>x(p)?p.value=a:null),length:r(O),"total-visible":l.$vuetify.display.xs?1:Math.min(r(O),5)},{prev:t(a=>[e(m,Q({variant:"tonal",color:"default"},a,{icon:!1}),{default:t(()=>[e(i,{start:"",icon:"tabler-arrow-left"}),n(" Précedent ")]),_:2},1040)]),next:t(a=>[e(m,Q({variant:"tonal",color:"default"},a,{icon:!1}),{default:t(()=>[n(" Suivant "),e(i,{end:"",icon:"tabler-arrow-right"})]),_:2},1040)]),_:1},8,["modelValue","length","total-visible"])])]),_:1},8,["items-per-page","page","items","items-length"])]),_:1}),e(be,{modelValue:r(g),"onUpdate:modelValue":o[10]||(o[10]=a=>x(g)?g.value=a:null),class:"v-dialog-sm"},{default:t(()=>[e(F,{onClick:o[7]||(o[7]=a=>g.value=!r(g))}),e(W,{title:"Suppression"},{default:t(()=>[e(te,null,{default:t(()=>[n(" Etes vous sûr de vouloir supprimer cette caution? ")]),_:1}),e(te,{class:"d-flex justify-end gap-3 flex-wrap"},{default:t(()=>[e(m,{color:"secondary",variant:"tonal",onClick:o[8]||(o[8]=a=>g.value=!1)},{default:t(()=>[n(" Annuler ")]),_:1}),e(m,{onClick:o[9]||(o[9]=a=>{ie(r(U)),g.value=!1})},{default:t(()=>[n(" Supprimer ")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])])}}},dt=ye(Pe,[["__scopeId","data-v-82484f0b"]]);export{dt as default};
