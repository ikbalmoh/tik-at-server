import React from "react";
import { PageProps, TableColumn, Pagination } from "@/types";
import { Transaction as TransactionProp } from "@/types/app";
import AppLayout from "@/Layouts/AppLayout";
import { Head, router } from "@inertiajs/react";
import { IconRefresh } from "@tabler/icons-react";
import Table from "@/Components/Table";

interface TransactionData extends Pagination {
    data: Array<any>;
}

interface Props extends PageProps {
    transactions: TransactionData;
}

const column: TableColumn = [
    { header: "Tanggal", value: "date" },
    { header: "Kasir", value: "operator_name" },
    {
        header: "Jumlah Tiket",
        value: (t: TransactionProp) => t?.total_ticket?.all ?? "0",
    },
    {
        header: "Dewasa",
        value: (t: TransactionProp) => t?.total_ticket[1] ?? "0",
    },
    {
        header: "Anak-anak",
        value: (t: TransactionProp) => t?.total_ticket[2] ?? "0",
    },
    {
        header: "Mancanegara",
        value: (t: TransactionProp) => t?.total_ticket[3] ?? "0",
    },
    { header: "Total", value: "grand_total", className: "text-right" },
];

export default function Transaction({ auth, transactions }: Props) {
    const reload = () => router.reload({ only: ["transactions"] });

    const { data, ...pagination } = transactions;

    return (
        <AppLayout title="Laporan Transaksi" user={auth?.user}>
            <Head title="Laporan Transaksi" />

            <div className="py-10">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center w-full mb-5">
                        <h2 className="text-2xl font-medium text-gray-700 flex-1">
                            Laporan Transaksi
                        </h2>
                        <div className="flex items-center">
                            <button
                                onClick={reload}
                                type="button"
                                className="ml-3"
                            >
                                <IconRefresh className="h-4" />
                            </button>
                        </div>
                    </div>

                    <div className="mt-5">
                        <Table
                            column={column}
                            data={data}
                            pagination={pagination}
                            onClickRow={undefined}
                        />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
