import{ac as I,r as L,a1 as j,s as M,br as w,bL as v,aY as O}from"./main-35f434e5.js";const S=(t,c,e)=>t==null||c==null?-1:t.toString().toLocaleLowerCase().indexOf(c.toString().toLocaleLowerCase()),A=I({customFilter:Function,customKeyFilter:Object,filterKeys:[Array,String],filterMode:{type:String,default:"intersection"},noFilter:Boolean},"filter");function x(t,c,e){var g;const f=[],s=(e==null?void 0:e.default)??S,m=e!=null&&e.filterKeys?v(e.filterKeys):!1,F=Object.keys((e==null?void 0:e.customKeyFilter)??{}).length;if(!(t!=null&&t.length))return f;e:for(let r=0;r<t.length;r++){const[a,y=a]=v(t[r]),i={},n={};let l=-1;if(c&&!(e!=null&&e.noFilter)){if(typeof a=="object"){const K=m||Object.keys(y);for(const u of K){const k=O(y,u,y),b=(g=e==null?void 0:e.customKeyFilter)==null?void 0:g[u];if(l=b?b(k,c,a):s(k,c,a),l!==-1&&l!==!1)b?i[u]=l:n[u]=l;else if((e==null?void 0:e.filterMode)==="every")continue e}}else l=s(a,c,a),l!==-1&&l!==!1&&(n.title=l);const d=Object.keys(n).length,h=Object.keys(i).length;if(!d&&!h||(e==null?void 0:e.filterMode)==="union"&&h!==F&&!d||(e==null?void 0:e.filterMode)==="intersection"&&(h!==F||!d))continue}f.push({index:r,matches:{...n,...i}})}return f}function C(t,c,e,f){const s=L([]),m=L(new Map),F=j(()=>f!=null&&f.transform?M(c).map(r=>[r,f.transform(r)]):M(c));w(()=>{const r=typeof e=="function"?e():M(e),a=typeof r!="string"&&typeof r!="number"?"":String(r),y=x(F.value,a,{customKeyFilter:t.customKeyFilter,default:t.customFilter,filterKeys:t.filterKeys,filterMode:t.filterMode,noFilter:t.noFilter}),i=M(c),n=[],l=new Map;y.forEach(d=>{let{index:h,matches:K}=d;const u=i[h];n.push(u),l.set(u.value,K)}),s.value=n,m.value=l});function g(r){return m.value.get(r.value)}return{filteredItems:s,filteredMatches:m,getMatches:g}}export{A as m,C as u};
