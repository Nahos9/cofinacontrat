import{_ as se}from"./DialogCloseBtn-fa0ad85d.js";import{_ as ue}from"./AppTextField-4144e217.js";import{V as de,p as ce,a as pe}from"./VPagination-8403895c.js";import{J as me}from"./js-file-downloader-8d1ab2ff.js";import{_ as ve}from"./AppTextarea-fc7a54f9.js";import{u as fe}from"./useApi-f6bf8dd7.js";import{c as _e}from"./createUrl-376a4625.js";import{r as d,ah as be,a1 as q,c as ke,b as t,e,s as n,$ as V,a as ye,o as r,d as b,a3 as m,M as i,f as s,N as u,Z as c,x as _,z as Z,aa as Ve,C as Ce,D as ge}from"./main-35f434e5.js";import{$ as S}from"./api-bc7c6e86.js";import{_ as $e}from"./_plugin-vue_export-helper-c27b6911.js";import{V as E}from"./VCard-de226d5c.js";import{V as G}from"./VDialog-85fb74b8.js";import{V as C}from"./VCardText-2ef94eba.js";import{V as Te}from"./VRow-5bf9819c.js";import{V as we}from"./VSpacer-ccd05c4b.js";import{V as Ae}from"./VSelect-8eaa7a4a.js";import{V as H}from"./VTooltip-14162ccf.js";import{V as he}from"./VMenu-ad7875d9.js";import{V as xe,b as g,a as $}from"./VList-a84ed864.js";import{V as N}from"./VDivider-2199d20e.js";import"./VTextField-31ac9b36.js";import"./VImg-fb08db81.js";import"./forwardRefs-6ea3df5c.js";import"./VTable-b3fce31e.js";import"./filter-e1345ab9.js";import"./index-9c720871.js";import"./useAbility-597cc743.js";import"./VAvatar-5cd70722.js";import"./VOverlay-6080deb7.js";const je=U=>(Ce("data-v-7f9ce6ba"),U=U(),ge(),U),Pe=je(()=>b("h2",null," Liste des CAT ",-1)),De={class:"d-flex flex-wrap gap-4 mx-5"},Se={class:"d-flex align-center"},Ue={class:"d-flex gap-4 flex-wrap align-center"},Re={class:"custom-loader"},Be={class:"d-flex align-center justify-space-between flex-wrap gap-3 pa-5 pt-3"},Ie={class:"text-sm text-medium-emphasis mb-0"},ze={__name:"index",async setup(U){let R,M;const k=d(!1),y=d(0),K=d(),B=d(""),I=d([]),W=[{title:"Numéro comitée",key:"notification.verbal_trial.committee_id"},{title:"Admin Crédit",key:"notification.creator.full_name"},{title:"Nom client",key:"notification.verbal_trial.applicant_full_name"},{title:"Secteur",key:"sector"},{title:"Numéro de prêt",key:"credit_number"},{title:"Montant",key:"notification.verbal_trial.amount"},{title:"Status",key:"status"},{title:"Actions",key:"actions",sortable:!1}],X=l=>{I.value[l]=!0,setTimeout(()=>{I.value[l]=!1},1e3)},z=d(8),v=d(1),Y=l=>{v.value=l.page},{data:L,execute:T}=([R,M]=be(()=>fe(_e("/cat",{query:{search:B,type:K,page:v,with_type_of_applicant:1,with_creator:1,with_notification:1,has_notification:1,is_simple:1}}))),R=await R,M(),R),ee=q(()=>L.value.data),J=q(()=>L.value.total),O=q(()=>L.value.last_page),ae={company:"Société",individual_business:"Entreprise Individuel",particular:"Particulier"},te=async(l,o)=>{const F=Ve("userToken").value;try{new me({url:l,headers:[{name:"Authorization",value:`Bearer ${F}`},{name:"Accept",value:"application/json"}],nameCallback:function(w){return o}})}catch(w){console.error("Erreur lors du téléchargement:",w)}},le=async l=>{await S(`cat/${l}`,{method:"DELETE"}),T()},p=d(!1),A=d(!1),h=d(""),x=d(""),j=d(""),P=d(),f=d(null),oe=async l=>{await S(`cat/validate/${l}`,{method:"PUT"}),f.value=null,T()},ne=async l=>{await S(`cat/unblock/${l}`,{method:"PUT"}),f.value=null,T()},ie=async l=>{await S(`cat/reject-validation/${l}`,{method:"PUT",body:{comment:f.value}}),f.value=null,T()},re=async l=>{await S(`cat/reject-unblock/${l}`,{method:"PUT",body:{comment:f.value}}),f.value=null,T()};return(l,o)=>{const F=ue,w=ye("IconBtn"),Q=se;return r(),ke("div",null,[t(E,{class:"mb-6"},{default:e(()=>[t(C,null,{default:e(()=>[t(Te,null,{default:e(()=>[t(C,null,{default:e(()=>[Pe]),_:1})]),_:1})]),_:1})]),_:1}),t(E,{class:"mb-6"},{default:e(()=>[t(C,null,{default:e(()=>[b("div",De,[b("div",Se,[t(F,{modelValue:n(B),"onUpdate:modelValue":o[0]||(o[0]=a=>V(B)?B.value=a:null),placeholder:"Rechercher un cat",density:"compact",style:{"inline-size":"200px"},class:"me-3"},null,8,["modelValue"])]),t(we),b("div",Ue,[t(m,{variant:"tonal",color:"secondary","prepend-icon":"tabler-download"},{default:e(()=>[i(" Exporter ")]),_:1}),l.$can("create","cat")?(r(),s(m,{key:0,color:"primary","prepend-icon":"tabler-plus",to:{name:"cat-add"}},{default:e(()=>[i(" Ajouter ")]),_:1})):u("",!0),t(m,{loading:n(I)[3],disabled:n(I)[3],"prepend-icon":"tabler-refresh",onClick:o[1]||(o[1]=a=>{n(T)(),X(3)})},{loader:e(()=>[b("span",Re,[t(c,{icon:"tabler-refresh"})])]),default:e(()=>[i(" Recharger ")]),_:1},8,["loading","disabled"])])])]),_:1}),t(n(de),{"items-per-page":n(z),"onUpdate:itemsPerPage":o[3]||(o[3]=a=>V(z)?z.value=a:null),page:n(v),"onUpdate:page":o[4]||(o[4]=a=>V(v)?v.value=a:null),headers:W,items:n(ee),"items-length":n(J),class:"text-no-wrap","onUpdate:options":Y},{"item.type":e(({item:a})=>[i(_(ae[a.type]),1)]),"item.status":e(({item:a})=>[t(Ae,{label:"",color:a.status.color},{default:e(()=>[a.validation_comment&&!l.unblock_comment?(r(),s(H,{key:0,activator:"parent",transition:"scroll-x-transition",location:"start"},{default:e(()=>[i("Raison: "+_(a.validation_comment),1)]),_:2},1024)):u("",!0),a.unblock_comment?(r(),s(H,{key:1,activator:"parent",transition:"scroll-x-transition",location:"start"},{default:e(()=>[i(" Raison: "+_(a.unblock_comment),1)]),_:2},1024)):u("",!0),i(" "+_(a.status.message),1)]),_:2},1032,["color"])]),"item.comment":e(({item:a})=>[i(_(a.comment),1)]),"item.notification.verbal_trial.amount":e(({item:a})=>[i(_(String(a.notification.verbal_trial.amount).replace(/\B(?=(\d{3})+(?!\d))/g," "))+" F CFA ",1)]),"item.actions":e(({item:a})=>[b("span",null,[l.$can("read","cat")?(r(),s(w,{key:0,to:{name:"cat-notification-id",params:{id:a.id}}},{default:e(()=>[t(c,{icon:"tabler-eye"})]),_:2},1032,["to"])):u("",!0),t(m,{icon:"",variant:"text",size:"small",color:"medium-emphasis"},{default:e(()=>[t(c,{size:"24",icon:"tabler-dots-vertical"}),t(he,{activator:"parent"},{default:e(()=>[t(xe,null,{default:e(()=>[l.$can("historical","pv")||l.$can("read","pv")?(r(),s($,{key:0,to:{name:"pv-id",params:{id:a.notification.verbal_trial.id}}},{prepend:e(()=>[t(c,{icon:"tabler-eye"})]),default:e(()=>[t(g,null,{default:e(()=>[i("Voir Pv")]),_:1})]),_:2},1032,["to"])):u("",!0),l.$can("read","notification")||l.$can("historical","notification")?(r(),s($,{key:1,to:{name:"notification-id",params:{id:a.notification.id}}},{prepend:e(()=>[t(c,{icon:"tabler-eye"})]),default:e(()=>[t(g,null,{default:e(()=>[i("Voir la notification")]),_:1})]),_:2},1032,["to"])):u("",!0),l.$can("download","cat")?(r(),s($,{key:2,onClick:D=>te(`/api/cat/download/${a.id}`,`CAT-${a.notification.verbal_trial.committee_id}.docx`)},{prepend:e(()=>[t(c,{icon:"tabler-download"})]),default:e(()=>[t(g,null,{default:e(()=>[i("Télécharger CAT")]),_:1})]),_:2},1032,["onClick"])):u("",!0),l.$can("validate","cat")&&a.validation_status=="waiting"?(r(),s(N,{key:3})):u("",!0),l.$can("validate","cat")&&a.validation_status=="waiting"?(r(),s($,{key:4,onClick:D=>{y.value=a.id,p.value=!0,h.value="Valider CAT",x.value="Voulez vous vraiment valider ce CAT?",P.value=oe,j.value="Valider",A.value=!1}},{prepend:e(()=>[t(c,{icon:"tabler-check"})]),default:e(()=>[t(g,null,{default:e(()=>[i("Valider CAT")]),_:1})]),_:2},1032,["onClick"])):u("",!0),l.$can("reject_validation","cat")&&a.validation_status=="waiting"?(r(),s($,{key:5,onClick:D=>{y.value=a.id,p.value=!0,h.value="Rejeter CAT",x.value="Voulez vous vraiment rejeter ce CAT?",P.value=ie,j.value="Rejeter",A.value=!0}},{prepend:e(()=>[t(c,{icon:"tabler-x"})]),default:e(()=>[t(g,null,{default:e(()=>[i("Rejeter CAT")]),_:1})]),_:2},1032,["onClick"])):u("",!0),l.$can("unblock","cat")&&a.unblock_status=="waiting"&&a.validation_status=="validated"?(r(),s(N,{key:6})):u("",!0),l.$can("unblock","cat")&&a.unblock_status=="waiting"&&a.validation_status=="validated"?(r(),s($,{key:7,onClick:D=>{y.value=a.id,p.value=!0,h.value="Débloquer CAT",x.value="Voulez vous vraiment débloquer ce CAT?",P.value=ne,j.value="Débloquer",A.value=!1}},{prepend:e(()=>[t(c,{icon:"tabler-lock-open"})]),default:e(()=>[t(g,null,{default:e(()=>[i("Débloquer CAT")]),_:1})]),_:2},1032,["onClick"])):u("",!0),l.$can("reject_unblock","cat")&&a.unblock_status=="waiting"&&a.validation_status=="validated"?(r(),s($,{key:8,onClick:D=>{y.value=a.id,p.value=!0,h.value="Rejeter deblocage CAT",x.value="Voulez vous vraiment rejeter le déblocage de ce CAT?",P.value=re,j.value="Rejeter",A.value=!0}},{prepend:e(()=>[t(c,{icon:"tabler-x"})]),default:e(()=>[t(g,null,{default:e(()=>[i("Refuser déblocage CAT")]),_:1})]),_:2},1032,["onClick"])):u("",!0)]),_:2},1024)]),_:2},1024)]),_:2},1024)]),b("span",null,[t(N),l.$can("update","cat")?(r(),s(w,{key:0,to:{name:"cat-edit-id",params:{id:a.id}},disabled:a.validation_status=="validated"},{default:e(()=>[t(c,{icon:"tabler-edit"})]),_:2},1032,["to","disabled"])):u("",!0),l.$can("delete","cat")?(r(),s(w,{key:1,onClick:D=>{y.value=a.id,k.value=!0},disabled:a.validation_status=="validated"},{default:e(()=>[t(c,{icon:"tabler-trash",color:"error"})]),_:2},1032,["onClick","disabled"])):u("",!0)])]),bottom:e(()=>[t(N),b("div",Be,[b("p",Ie,_(n(ce)({page:n(v),itemsPerPage:n(z)},n(J))),1),t(pe,{modelValue:n(v),"onUpdate:modelValue":o[2]||(o[2]=a=>V(v)?v.value=a:null),length:n(O),"total-visible":l.$vuetify.display.xs?1:Math.min(n(O),5)},{prev:e(a=>[t(m,Z({variant:"tonal",color:"default"},a,{icon:!1}),{default:e(()=>[t(c,{start:"",icon:"tabler-arrow-left"}),i(" Précedent ")]),_:2},1040)]),next:e(a=>[t(m,Z({variant:"tonal",color:"default"},a,{icon:!1}),{default:e(()=>[i(" Suivant "),t(c,{end:"",icon:"tabler-arrow-right"})]),_:2},1040)]),_:1},8,["modelValue","length","total-visible"])])]),_:1},8,["items-per-page","page","items","items-length"])]),_:1}),t(G,{modelValue:n(p),"onUpdate:modelValue":o[9]||(o[9]=a=>V(p)?p.value=a:null),class:"v-dialog-sm"},{default:e(()=>[t(Q,{onClick:o[5]||(o[5]=a=>p.value=!n(p))}),t(E,{title:n(h)},{default:e(()=>[t(C,null,{default:e(()=>[i(_(n(x))+" ",1),n(A)?(r(),s(ve,{key:0,class:"mt-3",modelValue:n(f),"onUpdate:modelValue":o[6]||(o[6]=a=>V(f)?f.value=a:null),label:"Commentaire",placeholder:"Ex: RAS"},null,8,["modelValue"])):u("",!0)]),_:1}),t(C,{class:"d-flex justify-end gap-3 flex-wrap"},{default:e(()=>[t(m,{color:"secondary",variant:"tonal",onClick:o[7]||(o[7]=a=>p.value=!1)},{default:e(()=>[i(" Annuler ")]),_:1}),t(m,{onClick:o[8]||(o[8]=a=>{n(P)(n(y)),p.value=!1})},{default:e(()=>[i(_(n(j)),1)]),_:1})]),_:1})]),_:1},8,["title"])]),_:1},8,["modelValue"]),t(G,{modelValue:n(k),"onUpdate:modelValue":o[13]||(o[13]=a=>V(k)?k.value=a:null),class:"v-dialog-sm"},{default:e(()=>[t(Q,{onClick:o[10]||(o[10]=a=>k.value=!n(k))}),t(E,{title:"Suppression"},{default:e(()=>[t(C,null,{default:e(()=>[i(" Etes vous sûr de vouloir supprimer ce CAT? ")]),_:1}),t(C,{class:"d-flex justify-end gap-3 flex-wrap"},{default:e(()=>[t(m,{color:"secondary",variant:"tonal",onClick:o[11]||(o[11]=a=>k.value=!1)},{default:e(()=>[i(" Annuler ")]),_:1}),t(m,{onClick:o[12]||(o[12]=a=>{le(n(y)),k.value=!1})},{default:e(()=>[i(" Supprimer ")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])])}}},ma=$e(ze,[["__scopeId","data-v-7f9ce6ba"]]);export{ma as default};
