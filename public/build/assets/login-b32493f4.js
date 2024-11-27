import{r as h,e as B}from"./validators-be8c4c00.js";import{r as m,f as L,e as o,n as R,E as D,o as M,b as s,d as n,s as a,_,M as c,x as N,a2 as S,a3 as F,aa as f,ai as $}from"./main-e84ce0a6.js";import{_ as j}from"./AppTextField-cfb27a57.js";import{u as v,m as A,a as E}from"./misc-mask-light-4a29f7ad.js";import{V as P}from"./VNodeRenderer-615a3642.js";import{u as z}from"./useAbility-d056d777.js";import{$ as U}from"./api-6638b469.js";import{V as b}from"./VRow-d22e34f5.js";import{V as y}from"./VImg-291e1ee2.js";import{V as p}from"./VCol-a817eccc.js";import{V as G}from"./VCard-fe1def77.js";import{V as w}from"./VCardText-c43c5a35.js";import{V as O}from"./VForm-b81ac70c.js";import"./VTextField-bed2e038.js";import"./forwardRefs-6ea3df5c.js";import"./index-9c720871.js";import"./VAvatar-b9338bff.js";const H="/build/assets/auth-v2-login-illustration-bordered-dark-a595a9b7.png",J="/build/assets/auth-v2-login-illustration-bordered-light-47ee3625.png",K="/build/assets/auth-v2-login-illustration-dark-0878e8b9.png",Q="/build/assets/auth-v2-login-illustration-light-d1fd488d.png";const W={class:"position-relative bg-background rounded-lg w-100 ma-8 me-0"},X={class:"d-flex align-center justify-center w-100 h-100"},Y={class:"text-h4 mb-1"},Z={class:"text-capitalize"},ee=n("p",{class:"mb-0"},"Veuillez vous connecter",-1),ve={__name:"login",setup(ae){const k=v(Q,K,J,H,!0),x=v(E,A),u=m(!1),g=R(),T=D(),C=z(),l=m({email:void 0,password:void 0}),V=m(),i=m({email:"",password:""}),I=async()=>{try{const e=await U("/auth/login",{method:"POST",body:{email:i.value.email,password:i.value.password},onResponseError({response:t}){l.value=t._data.errors}});if(l.value.email=void 0,l.value.password=void 0,e.status==200){const{userToken:t,user:r}=e.data;f("userAbilityRules").value=r.ability_rules,C.update(r.ability_rules),f("userData").value={id:r.id,fullName:r.full_name,username:r.name,avatar:"/images/avatars/avatar-1.png",signatory:r.signatory_path?"http://credit.cofina.localhost"+r.signatory_path:"/images/avatars/avatar-14.png",email:r.email,role:r.profile,role_fr:r.profile_fr},f("userToken").value=t,await $(()=>{T.replace(g.query.to?String(g.query.to):"/")})}else e.status==400&&(e.errors.email&&(l.value.email=e.errors.email[0]),e.errors.password&&(l.value.password=e.errors.password[0]))}catch(e){console.error(e)}},q=()=>{var e;(e=V.value)==null||e.validate().then(({valid:t})=>{t&&I()})};return(e,t)=>{const r=j;return M(),L(b,{"no-gutters":"",class:"auth-wrapper bg-surface"},{default:o(()=>[s(p,{lg:"8",class:"d-none d-lg-flex"},{default:o(()=>[n("div",W,[n("div",X,[s(y,{"max-width":"505",src:a(k),class:"auth-illustration mt-16 mb-2"},null,8,["src"])]),s(y,{src:a(x),class:"auth-footer-mask"},null,8,["src"])])]),_:1}),s(p,{cols:"12",lg:"4",class:"auth-card-v2 d-flex align-center justify-center"},{default:o(()=>[s(G,{flat:"","max-width":500,class:"mt-12 mt-sm-0 pa-4"},{default:o(()=>[s(w,null,{default:o(()=>[s(a(P),{nodes:a(_).app.logo,class:"mb-6"},null,8,["nodes"]),n("h4",Y,[c(" Bienvenue sur "),n("span",Z,N(a(_).app.title),1),c("! 👋🏻 ")]),ee]),_:1}),s(w,null,{default:o(()=>[s(a(O),{ref_key:"refVForm",ref:V,onSubmit:S(q,["prevent"])},{default:o(()=>[s(b,null,{default:o(()=>[s(p,{cols:"12"},{default:o(()=>[s(r,{modelValue:a(i).email,"onUpdate:modelValue":t[0]||(t[0]=d=>a(i).email=d),label:"Email",placeholder:"johndoe@email.com",type:"email",autofocus:"",rules:["requiredValidator"in e?e.requiredValidator:a(h),"emailValidator"in e?e.emailValidator:a(B)],"error-messages":a(l).email},null,8,["modelValue","rules","error-messages"])]),_:1}),s(p,{cols:"12"},{default:o(()=>[s(r,{modelValue:a(i).password,"onUpdate:modelValue":t[1]||(t[1]=d=>a(i).password=d),label:"Password",placeholder:"············",rules:["requiredValidator"in e?e.requiredValidator:a(h)],type:a(u)?"text":"password","error-messages":a(l).password,"append-inner-icon":a(u)?"tabler-eye-off":"tabler-eye","onClick:appendInner":t[2]||(t[2]=d=>u.value=!a(u)),class:"mb-8"},null,8,["modelValue","rules","type","error-messages","append-inner-icon"]),s(F,{block:"",type:"submit"},{default:o(()=>[c(" Connexion ")]),_:1})]),_:1})]),_:1})]),_:1},512)]),_:1})]),_:1})]),_:1})]),_:1})}}};export{ve as default};