import{_ as ve}from"./AppTextarea-fc7a54f9.js";import{_ as me}from"./DialogCloseBtn-fa0ad85d.js";import{V as ge,p as ye,a as he}from"./VPagination-8403895c.js";import{J as be}from"./js-file-downloader-8d1ab2ff.js";import{$ as Y}from"./api-bc7c6e86.js";import{r as u,ah as ke,a1 as Q,c as V,b as a,e as t,s as i,$ as A,E as Ve,a as Ce,o as r,d as m,a3 as k,M as o,f as d,N as s,Z as c,x as y,F as $e,A as we,z as ee,aa as te,C as xe,D as je}from"./main-35f434e5.js";import{u as Te}from"./useApi-f6bf8dd7.js";import{c as Ae}from"./createUrl-376a4625.js";import{_ as Be}from"./_plugin-vue_export-helper-c27b6911.js";import{V as Z}from"./VCard-de226d5c.js";import{V as Se}from"./VDialog-85fb74b8.js";import{V as O}from"./VCardText-2ef94eba.js";import{V as ae}from"./VRow-5bf9819c.js";import{V as Ie}from"./VSpacer-ccd05c4b.js";import{V as C}from"./VDivider-2199d20e.js";import{V as oe,b as v,a as f}from"./VList-a84ed864.js";import{V as G}from"./VSelect-8eaa7a4a.js";import{V as $}from"./VTooltip-14162ccf.js";import{V as Pe}from"./VMenu-ad7875d9.js";import{V as De}from"./VBadge-07def383.js";import{V as Ne}from"./VCol-bbc947c1.js";import"./VTextField-31ac9b36.js";import"./VImg-fb08db81.js";import"./forwardRefs-6ea3df5c.js";import"./VTable-b3fce31e.js";import"./filter-e1345ab9.js";import"./index-9c720871.js";import"./useAbility-597cc743.js";import"./VAvatar-5cd70722.js";import"./VOverlay-6080deb7.js";const ne=B=>(xe("data-v-807dcc16"),B=B(),je(),B),Ee=ne(()=>m("h2",null," Liste des contrats ",-1)),Re={class:"d-flex flex-wrap gap-4 mt-4 mx-5"},Ue=ne(()=>m("div",{class:"d-flex align-center"},null,-1)),ze={class:"d-flex gap-4 flex-wrap align-center"},Fe={class:"custom-loader"},Le=["onInput"],Me={key:2},Oe={key:3},qe={key:4},Je={key:0},Qe={key:1},Ze={class:"d-flex align-center justify-space-between flex-wrap gap-3 pa-5 pt-3"},Ge={class:"text-sm text-medium-emphasis mb-0"},He={__name:"without-signed-contract",async setup(B){let S,H;const le=Ve(),ie=u(),re=u(""),I=u(),P=u("signed_notification"),D=u(8),h=u(1),N=u([]),E=u(0),g=u(!1),R=u(""),U=u(""),z=u(""),F=u(),w=u(""),L=u(!1),M=u("waiting"),se=[{title:"Numéro comitée",key:"verbal_trial.committee_id"},{title:"Admin Crédit",key:"creator.full_name"},{title:"Nom client",key:"verbal_trial.applicant_full_name"},{title:"Téléphone",key:"representative_phone_number"},{title:"Montant",key:"verbal_trial.amount"},{title:"Observations",key:"observations"},{title:"Actions",key:"actions",sortable:!1}],{data:q,execute:x}=([S,H]=ke(()=>Te(Ae("/notification",{query:{search:re,type:ie,page:h,with_type_of_credit:1,with_company:1,with_individual_business:1,with_creator:1,head_credit_validation:"v",has_cat:0,is_simple:0}}))),S=await S,H(),S),ce=n=>{N.value[n]=!0,setTimeout(()=>{N.value[n]=!1},1e3)},de=n=>{h.value=n.page},ue={company:"Société",individual_business:"Entreprise Individuel",particular:"Particulier"},j=async(n,l)=>{try{new be({url:n,headers:[{name:"Authorization",value:`Bearer ${te("userToken").value}`},{name:"Accept",value:"application/json"}],nameCallback:function(_){return l}}),x()}catch(_){console.error("Erreur lors du téléchargement:",_)}},K=async n=>{await Y(`notification/change-status/${n}`,{method:"PUT",body:{status:M.value,comment:w.value}}),w.value="",M.value=="validated"&&le.push(`/cat/notification/add?id=${n}`),x()},pe=async n=>{await Y(`notification/send/${n}`,{method:"PUT"}),x()},fe=async(n,l)=>{const{files:_}=l.target;if(_&&_.length===1){const T=new FileReader;T.onload=async()=>{const J=T.result;try{(await fetch(`/api/notification/upload/${n}`,{method:"POST",headers:{"Content-Type":"application/json",Authorization:`Bearer ${te("userToken").value}`},body:JSON.stringify({[P.value]:J})})).ok?(console.log("Document envoyé avec succès."),x()):console.error("Échec de l'envoi du document.")}catch(e){console.error("Erreur lors de l'envoi du document:",e)}},T.readAsDataURL(_[0])}else console.error("Veuillez sélectionner un seul fichier.")},_e=Q(()=>q.value.data),W=Q(()=>q.value.total),X=Q(()=>q.value.last_page);return(n,l)=>{const _=Ce("IconBtn"),T=me,J=ve;return r(),V("div",null,[a(Z,{class:"mb-6"},{default:t(()=>[a(O,null,{default:t(()=>[a(ae,null,{default:t(()=>[a(O,null,{default:t(()=>[Ee]),_:1})]),_:1})]),_:1})]),_:1}),a(Z,{class:"mb-6"},{default:t(()=>[m("div",Re,[Ue,a(Ie),m("div",ze,[a(k,{variant:"tonal",color:"secondary","prepend-icon":"tabler-download"},{default:t(()=>[o(" Export ")]),_:1}),n.$can("create","notification")?(r(),d(k,{key:0,color:"primary","prepend-icon":"tabler-plus",to:{name:"notification-add"}},{default:t(()=>[o(" Ajouter ")]),_:1})):s("",!0),a(k,{loading:i(N)[3],disabled:i(N)[3],"prepend-icon":"tabler-refresh",onClick:l[0]||(l[0]=e=>{i(x)(),ce(3)})},{loader:t(()=>[m("span",Fe,[a(c,{icon:"tabler-refresh"})])]),default:t(()=>[o(" Recharger ")]),_:1},8,["loading","disabled"])])]),a(C,{class:"mt-4"}),a(i(ge),{"items-per-page":i(D),"onUpdate:itemsPerPage":l[5]||(l[5]=e=>A(D)?D.value=e:null),page:i(h),"onUpdate:page":l[6]||(l[6]=e=>A(h)?h.value=e:null),headers:se,items:i(_e),"items-length":i(W),class:"text-no-wrap","onUpdate:options":de},{"item.type":t(({item:e})=>[o(y(ue[e.type]),1)]),"item.verbal_trial.amount":t(({item:e})=>[o(y(String(e.verbal_trial.amount).replace(/\B(?=(\d{3})+(?!\d))/g," "))+" F CFA ",1)]),"item.observations":t(({item:e})=>[a(oe,{density:" compact"},{default:t(()=>[e.observations.length>0?(r(!0),V($e,{key:0},we(e.observations,p=>(r(),d(f,null,{default:t(()=>[a(v,null,{default:t(()=>[a(G,{label:""},{default:t(()=>[o(y(p),1)]),_:2},1024)]),_:2},1024)]),_:2},1024))),256)):s("",!0),e.observations.length==0?(r(),d(f,{key:1},{default:t(()=>[a(G,{label:"",color:{validated:"success",rejected:"error",waiting:e.sent?"warning":"success"}[e.status]},{default:t(()=>[e.status?(r(),d($,{key:0,activator:"parent",transition:"scroll-x-transition",location:"start"},{default:t(()=>[o(" Raison: "+y(e.status_observation),1)]),_:2},1024)):s("",!0),o(" "+y(e.status=="validated"?"Dossier validé":null)+" "+y(e.status=="waiting"?e.sent?"Dossier en attente de validation":"Dossier prêt":null)+" "+y(e.status=="rejected"?"Dossier rejeté":null),1)]),_:2},1032,["color"])]),_:2},1024)):s("",!0),e.signed_promissory_note_path?s("",!0):(r(),d(f,{key:2},{default:t(()=>[a(v,null,{default:t(()=>[a(G,{label:"",color:"warning"},{default:t(()=>[o(" Billet à ordre manquant ")]),_:1})]),_:1})]),_:1}))]),_:2},1024)]),"item.actions":t(({item:e})=>[m("span",null,[a(_,{to:{name:"notification-id",params:{id:e.id}}},{default:t(()=>[a($,{activator:"parent",transition:"scroll-x-transition",location:"start"},{default:t(()=>[o("Details")]),_:1}),a(c,{icon:"tabler-eye"})]),_:2},1032,["to"]),a(k,{icon:"",variant:"text",size:"small",color:"medium-emphasis"},{default:t(()=>[a(c,{size:"24",icon:"tabler-dots-vertical"}),a(Pe,{activator:"parent"},{default:t(()=>[a(oe,null,{default:t(()=>[m("input",{ref_key:"refInputEl",ref:I,type:"file",name:"signed_notification",accept:".pdf,.png,.jpg",hidden:"",onInput:p=>fe(e.id,p)},null,40,Le),n.$can("read","guarantor")?(r(),d(De,{key:0,inline:"",content:e.guarantors_count},{default:t(()=>[a(f,{to:{name:"notification-notification_id-guarantor",params:{notification_id:e.id}}},{prepend:t(()=>[a(c,{icon:"tabler-users"})]),default:t(()=>[a(v,null,{default:t(()=>[o(" Voir les Cautions ")]),_:1})]),_:2},1032,["to"])]),_:2},1032,["content"])):s("",!0),n.$can("read","pv")?(r(),d(f,{key:1,to:{name:"pv-id",params:{id:e.verbal_trial.id}}},{prepend:t(()=>[a(c,{icon:"tabler-eye"})]),default:t(()=>[a(v,null,{default:t(()=>[o("Voir le Pv")]),_:1})]),_:2},1032,["to"])):s("",!0),n.$can("download","notification")?(r(),V("span",Me,[m("span",null,[a(C),a(f,{onClick:p=>{j(`/api/notification/download/${e.id}`,`Notification-${e.verbal_trial.committee_id}.docx`)}},{prepend:t(()=>[a(c,{icon:"tabler-download"})]),default:t(()=>[a(v,null,{default:t(()=>[o("Télécharger Notification non signé")]),_:1})]),_:2},1032,["onClick"]),a(f,{onClick:p=>{j(`/api/notification/promissory-note/download/${e.id}`,`Billet-à-ordre-${e.verbal_trial.committee_id}.docx`)}},{prepend:t(()=>[a(c,{icon:"tabler-download"})]),default:t(()=>[a(v,null,{default:t(()=>[o("Télécharger Billet à ordre non signé")]),_:1})]),_:2},1032,["onClick"])]),m("span",null,[a(C),e.signed_notification_path?(r(),d(f,{key:0,onClick:p=>j(e.signed_notification_path,`Notification-${e.signed_notification_path.split("/").slice(-1)[0]}`)},{prepend:t(()=>[a(c,{icon:"tabler-download"})]),default:t(()=>[a(v,null,{default:t(()=>[o("Télécharger Notification signé")]),_:1})]),_:2},1032,["onClick"])):s("",!0),e.signed_contract_path?(r(),d(f,{key:1,onClick:p=>j(e.signed_contract_path,`Contrat-${e.signed_contract_path.split("/").slice(-1)[0]}`)},{prepend:t(()=>[a(c,{icon:"tabler-download"})]),default:t(()=>[a(v,null,{default:t(()=>[o("Télécharger Contrat signé")]),_:1})]),_:2},1032,["onClick"])):s("",!0),e.signed_promissory_note_path?(r(),d(f,{key:2,onClick:p=>j(e.signed_promissory_note_path,`Billet-à-ordre-${e.signed_promissory_note_path.split("/").slice(-1)[0]}`)},{prepend:t(()=>[a(c,{icon:"tabler-download"})]),default:t(()=>[a(v,null,{default:t(()=>[o("Télécharger Billet à ordre signé")]),_:1})]),_:2},1032,["onClick"])):s("",!0)])])):s("",!0),n.$can("upload_signed_notification","notification")?(r(),V("span",Oe,[e.signed_notification_path==null||e.status=="rejected"?(r(),d(f,{key:0,onClick:l[1]||(l[1]=p=>{var b;P.value="signed_notification",(b=i(I))==null||b.click()})},{prepend:t(()=>[a(c,{icon:"tabler-cloud-upload"})]),default:t(()=>[a(v,{color:"error"},{default:t(()=>[o("Ajouter notification signé")]),_:1})]),_:1})):s("",!0)])):s("",!0),n.$can("upload","notification")?(r(),V("span",qe,[(e.signed_contract_path==null||e.status=="rejected")&&e.signed_notification_path!=null?(r(),d(f,{key:0,onClick:l[2]||(l[2]=p=>{var b;P.value="signed_contract",(b=i(I))==null||b.click()})},{prepend:t(()=>[a(c,{icon:"tabler-cloud-upload"})]),default:t(()=>[a(v,{color:"error"},{default:t(()=>[o("Ajouter contrat signé ")]),_:1})]),_:1})):s("",!0),(e.signed_promissory_note_path==null||e.status=="rejected")&&e.signed_notification_path!=null?(r(),d(f,{key:1,onClick:l[3]||(l[3]=p=>{var b;P.value="signed_promissory_note",(b=i(I))==null||b.click()})},{prepend:t(()=>[a(c,{icon:"tabler-cloud-upload"})]),default:t(()=>[a(v,null,{default:t(()=>[o("Ajouter billet à ordre signé")]),_:1})]),_:1})):s("",!0)])):s("",!0)]),_:2},1024)]),_:2},1024)]),_:2},1024)]),e.observations.length==0&&n.$can("send","notification")?(r(),V("span",Je,[a(C),a(ae,null,{default:t(()=>[a(Ne,{col:"12",class:"text-center"},{default:t(()=>[e.sent?s("",!0):(r(),d(_,{key:0,onClick:p=>{E.value=e.id,R.value="Envoyer le dossier de la notification",U.value="Voulez vous vraiment envoyer le dossier de cette notification?",F.value=pe,z.value="Envoyer",L.value=!1,g.value=!0}},{default:t(()=>[a($,{activator:"parent",transition:"scroll-x-transition",location:"start"},{default:t(()=>[o("Envoyer")]),_:1}),a(c,{icon:"tabler-send",color:"success"})]),_:2},1032,["onClick"]))]),_:2},1024)]),_:2},1024)])):s("",!0),e.sent&&(n.$can("reject","pv")&&e.status!="rejected"&&e.observations.length==0||n.$can("validate","pv")&&e.status=="waiting"&&e.observations.length==0||n.$can("create","cat")&&e.status=="validated")?(r(),V("span",Qe,[a(C),n.$can("reject","pv")&&e.status!="rejected"&&e.observations.length==0?(r(),d(_,{key:0,onClick:p=>{E.value=e.id,R.value="Rejeter la notification",U.value="Voulez vous vraiment rejeter cette notification?",F.value=K,z.value="Rejeter",L.value=!0,M.value="rejected",g.value=!0}},{default:t(()=>[a($,{activator:"parent",transition:"scroll-x-transition",location:"start"},{default:t(()=>[o("Rejeter")]),_:1}),a(c,{icon:"tabler-x",color:"error"})]),_:2},1032,["onClick"])):s("",!0),n.$can("validate","pv")&&e.status=="waiting"&&e.observations.length==0?(r(),d(_,{key:1,onClick:p=>{E.value=e.id,R.value="Valider la notification",U.value="Voulez vous vraiment valider cette notification?",F.value=K,z.value="Valider",L.value=!1,M.value="validated",g.value=!0}},{default:t(()=>[a($,{activator:"parent",transition:"scroll-x-transition",location:"end"},{default:t(()=>[o("Valider")]),_:1}),a(c,{icon:"tabler-check",color:"success"})]),_:2},1032,["onClick"])):s("",!0),n.$can("create","cat")&&e.status=="validated"?(r(),d(_,{key:2,to:{name:"cat-notification-add",query:{id:e.id}}},{default:t(()=>[a($,{activator:"parent",transition:"scroll-x-transition",location:"end"},{default:t(()=>[o("Créer le CAT")]),_:1}),a(c,{icon:"tabler-file-plus",color:"success"})]),_:2},1032,["to"])):s("",!0)])):s("",!0)]),bottom:t(()=>[a(C),m("div",Ze,[m("p",Ge,y(i(ye)({page:i(h),itemsPerPage:i(D)},i(W))),1),a(he,{modelValue:i(h),"onUpdate:modelValue":l[4]||(l[4]=e=>A(h)?h.value=e:null),length:i(X),"total-visible":n.$vuetify.display.xs?1:Math.min(i(X),5)},{prev:t(e=>[a(k,ee({variant:"tonal",color:"default"},e,{icon:!1}),{default:t(()=>[a(c,{start:"",icon:"tabler-arrow-left"}),o(" Précedent ")]),_:2},1040)]),next:t(e=>[a(k,ee({variant:"tonal",color:"default"},e,{icon:!1}),{default:t(()=>[o(" Suivant "),a(c,{end:"",icon:"tabler-arrow-right"})]),_:2},1040)]),_:1},8,["modelValue","length","total-visible"])])]),_:1},8,["items-per-page","page","items","items-length"])]),_:1}),a(Se,{modelValue:i(g),"onUpdate:modelValue":l[11]||(l[11]=e=>A(g)?g.value=e:null),class:"v-dialog-sm"},{default:t(()=>[a(T,{onClick:l[7]||(l[7]=e=>g.value=!i(g))}),a(Z,{title:i(R)},{default:t(()=>[a(O,null,{default:t(()=>[o(y(i(U))+" ",1),i(L)?(r(),d(J,{key:0,class:"mt-3",modelValue:i(w),"onUpdate:modelValue":l[8]||(l[8]=e=>A(w)?w.value=e:null),label:"Commentaire",placeholder:"Ex: RAS"},null,8,["modelValue"])):s("",!0)]),_:1}),a(O,{class:"d-flex justify-end gap-3 flex-wrap"},{default:t(()=>[a(k,{color:"secondary",variant:"tonal",onClick:l[9]||(l[9]=e=>g.value=!1)},{default:t(()=>[o(" Annuler ")]),_:1}),a(k,{onClick:l[10]||(l[10]=e=>{i(F)(i(E)),g.value=!1})},{default:t(()=>[o(y(i(z)),1)]),_:1})]),_:1})]),_:1},8,["title"])]),_:1},8,["modelValue"])])}}},xt=Be(He,[["__scopeId","data-v-807dcc16"]]);export{xt as default};
