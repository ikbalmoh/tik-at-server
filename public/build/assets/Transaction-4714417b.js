import{j as e,a as i,y as n}from"./app-49f065ad.js";import{A as o}from"./AppLayout-deca7822.js";import{T as c}from"./Table-60cd8527.js";import{I as m}from"./IconRefresh-b15a07f7.js";import"./ApplicationLogo-bd142d9c.js";import"./ResponsiveNavLink-061e91d5.js";import"./transition-afcbb519.js";const d=[{header:"Tanggal",value:"date"},{header:"Kasir",value:"operator_name"},{header:"Jumlah Tiket",value:a=>{var s;return((s=a==null?void 0:a.total_ticket)==null?void 0:s.all)??"0"}},{header:"Dewasa",value:a=>(a==null?void 0:a.total_ticket[1])??"0"},{header:"Anak-anak",value:a=>(a==null?void 0:a.total_ticket[2])??"0"},{header:"Mancanegara",value:a=>(a==null?void 0:a.total_ticket[3])??"0"},{header:"Total",value:"grand_total",className:"text-right"}];function k({auth:a,transactions:s}){const l=()=>n.reload({only:["transactions"]}),{data:r,...t}=s;return e.jsxs(o,{title:"Laporan Transaksi",user:a==null?void 0:a.user,children:[e.jsx(i,{title:"Laporan Transaksi"}),e.jsx("div",{className:"py-10",children:e.jsxs("div",{className:"max-w-7xl mx-auto sm:px-6 lg:px-8",children:[e.jsxs("div",{className:"flex justify-between items-center w-full mb-5",children:[e.jsx("h2",{className:"text-2xl font-medium text-gray-700 flex-1",children:"Laporan Transaksi"}),e.jsx("div",{className:"flex items-center",children:e.jsx("button",{onClick:l,type:"button",className:"ml-3",children:e.jsx(m,{className:"h-4"})})})]}),e.jsx("div",{className:"mt-5",children:e.jsx(c,{column:d,data:r,pagination:t,onClickRow:void 0})})]})})]})}export{k as default};