import{b as w,r as c}from"./app-ad1d5839.js";function z(...e){return e.join(" ")}var v={exports:{}},R="SECRET_DO_NOT_PASS_THIS_OR_YOU_WILL_BE_FIRED",k=R,E=k;function u(){}function _(){}_.resetWarningCache=u;var x=function(){function e(o,l,f,i,m,a){if(a!==E){var p=new Error("Calling PropTypes validators directly is not supported by the `prop-types` package. Use PropTypes.checkPropTypes() to call them. Read more at http://fb.me/use-check-prop-types");throw p.name="Invariant Violation",p}}e.isRequired=e;function r(){return e}var t={array:e,bigint:e,bool:e,func:e,number:e,object:e,string:e,symbol:e,any:e,arrayOf:r,element:e,elementType:e,instanceOf:r,node:e,objectOf:r,oneOf:r,oneOfType:r,shape:r,exact:r,checkPropTypes:_,resetWarningCache:u};return t.PropTypes=t,t};v.exports=x();var S=v.exports;const n=w(S);var j={xmlns:"http://www.w3.org/2000/svg",width:24,height:24,viewBox:"0 0 24 24",fill:"none",stroke:"currentColor",strokeWidth:2,strokeLinecap:"round",strokeLinejoin:"round"},C=Object.defineProperty,I=Object.defineProperties,N=Object.getOwnPropertyDescriptors,s=Object.getOwnPropertySymbols,d=Object.prototype.hasOwnProperty,g=Object.prototype.propertyIsEnumerable,y=(e,r,t)=>r in e?C(e,r,{enumerable:!0,configurable:!0,writable:!0,value:t}):e[r]=t,h=(e,r)=>{for(var t in r||(r={}))d.call(r,t)&&y(e,t,r[t]);if(s)for(var t of s(r))g.call(r,t)&&y(e,t,r[t]);return e},W=(e,r)=>I(e,N(r)),D=(e,r)=>{var t={};for(var o in e)d.call(e,o)&&r.indexOf(o)<0&&(t[o]=e[o]);if(e!=null&&s)for(var o of s(e))r.indexOf(o)<0&&g.call(e,o)&&(t[o]=e[o]);return t},F=(e,r,t)=>{const o=c.forwardRef((l,f)=>{var i=l,{color:m="currentColor",size:a=24,stroke:p=2,children:O}=i,P=D(i,["color","size","stroke","children"]);return c.createElement("svg",h(W(h({ref:f},j),{width:a,height:a,stroke:m,strokeWidth:p,className:`tabler-icon tabler-icon-${e}`}),P),[...t.map(([T,b])=>c.createElement(T,b)),...O||[]])});return o.propTypes={color:n.string,size:n.oneOfType([n.string,n.number]),stroke:n.oneOfType([n.string,n.number])},o.displayName=`${r}`,o},$=F("refresh","IconRefresh",[["path",{d:"M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4",key:"svg-0"}],["path",{d:"M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4",key:"svg-1"}]]);export{$ as I,z as a,F as c};
