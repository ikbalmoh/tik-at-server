import { Summary } from "@/types/app";
import { currency, number } from "@/utils/formater";
import React from "react";

export default function TransactionSummary({ summary }: { summary: Summary }) {
    return (
        <div className="bg-white rounded-lg mb-5 grid grid-cols-12 gap-0 shadow">
            <div className="col-span-12 md:col-span-4 p-5 flex flex-col items-center justify-center">
                <div className="text-base text-gray-500">Periode</div>
                <div className="text-lg font-bold">
                    {summary.from} - {summary.to}
                </div>
            </div>
            <div className="col-span-12 md:col-span-4 p-5 flex flex-col items-center justify-center border-t border-b md:border-t-0 md:border-b-0 md:border-l md:border-r border-gray5100 border-dashed">
                <div className="text-base text-gray-500">Tiket Terjual</div>
                <div className="text-2xl font-bold">{number(summary.qty)}</div>
            </div>
            <div className="col-span-12 md:col-span-4 p-5 flex flex-col items-center justify-center">
                <div className="text-base text-gray-500">Pendapatan</div>
                <div className="text-2xl font-bold">
                    {currency(summary.total)}
                </div>
            </div>
        </div>
    );
}
