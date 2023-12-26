import{j as e,r as l,W as h}from"./app-8928a867.js";import{T as w,I as b}from"./TextInput-74d2a0ae.js";import{I as j}from"./InputLabel-8a6e36a6.js";import{M as k}from"./Modal-8c73d1d7.js";import"./transition-5a2c9ac8.js";import"./createReactComponent-7c07301c.js";function u({className:o="",disabled:r,children:t,...s}){return e.jsx("button",{...s,className:`inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ${r&&"opacity-25"} `+o,disabled:r,children:t})}function N({type:o="button",className:r="",disabled:t,children:s,...a}){return e.jsx("button",{...a,type:o,className:`inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 ${t&&"opacity-25"} `+r,disabled:t,children:s})}function A({className:o=""}){const[r,t]=l.useState(!1),s=l.useRef(),{data:a,setData:m,delete:f,processing:p,reset:c,errors:x}=h({password:""}),g=()=>{t(!0)},y=i=>{i.preventDefault(),f(route("profile.destroy"),{preserveScroll:!0,onSuccess:()=>n(),onError:()=>{var d;return(d=s.current)==null?void 0:d.focus()},onFinish:()=>c()})},n=()=>{t(!1),c()};return e.jsxs("section",{className:`space-y-6 ${o}`,children:[e.jsxs("header",{children:[e.jsx("h2",{className:"text-lg font-medium text-gray-900 dark:text-gray-100",children:"Delete Account"}),e.jsx("p",{className:"mt-1 text-sm text-gray-600 dark:text-gray-400",children:"Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain."})]}),e.jsx(u,{onClick:g,children:"Delete Account"}),e.jsx(k,{show:r,onClose:n,title:"Are you sure you want to delete your account?",children:e.jsxs("form",{onSubmit:y,className:"p-6",children:[e.jsx("p",{className:"mt-1 text-sm text-gray-600 dark:text-gray-400",children:"Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account."}),e.jsxs("div",{className:"mt-6",children:[e.jsx(j,{htmlFor:"password",value:"Password",className:"sr-only"}),e.jsx(w,{id:"password",type:"password",name:"password",ref:s,value:a.password,onChange:i=>m("password",i.target.value),className:"mt-1 block w-3/4",isFocused:!0,placeholder:"Password"}),e.jsx(b,{message:x.password,className:"mt-2"})]}),e.jsxs("div",{className:"mt-6 flex justify-end",children:[e.jsx(N,{onClick:n,children:"Cancel"}),e.jsx(u,{className:"ms-3",disabled:p,children:"Delete Account"})]})]})})]})}export{A as default};
