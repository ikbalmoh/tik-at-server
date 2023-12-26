import { useState } from "react";
import { PageProps, TableColumn, Pagination } from "@/types";
import { Transaction as TransactionProp } from "@/types/app";
import AppLayout from "@/Layouts/AppLayout";
import { Head, router, usePage } from "@inertiajs/react";
import Table from "@/Components/Table";
import { currency } from "@/utils/formater";
import Datepicker, { DateValueType } from "react-tailwindcss-datepicker";

interface TransactionData extends Pagination {
    data: Array<any>;
}

interface Props extends PageProps {
    transactions: TransactionData;
    dates: {
        from: string | null;
        to: string | null;
    };
}

const column: TableColumn = [
    { header: "Waktu", value: "date" },
    { header: "Kasir", value: "operator_name" },
    {
        header: "Jumlah Tiket",
        value: (t: TransactionProp) => t?.total_ticket?.all ?? "0",
        className: "text-center",
    },
    {
        header: "Dewasa",
        value: (t: TransactionProp) => t?.total_ticket[1] ?? "0",
        className: "text-center",
    },
    {
        header: "Anak-anak",
        value: (t: TransactionProp) => t?.total_ticket[2] ?? "0",
        className: "text-center",
    },
    {
        header: "Mancanegara",
        value: (t: TransactionProp) => t?.total_ticket[3] ?? "0",
        className: "text-center",
    },
    {
        header: "Total",
        value: (t: TransactionProp) => currency(t.grand_total),
        className: "text-right font-bold",
    },
];

export default function Transaction({ auth, transactions }: Props) {
    const { data, ...pagination } = transactions;

    const { from, to } = usePage<Props>().props.dates;

    const [dates, setDates] = useState<DateValueType>({
        startDate: from,
        endDate: to,
    });

    const handleDatesChange = (newDates: DateValueType) => {
        setDates(newDates);
        router.reload({
            only: ["transactions"],
            data: { page: 1, from: newDates?.startDate, to: newDates?.endDate },
        });
    };

    return (
        <AppLayout title="Laporan Transaksi" user={auth?.user}>
            <Head title="Laporan Transaksi" />

            <div className="py-10">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col sm:flex-row sm:justify-between sm:items-center w-full mb-5">
                        <h2 className="text-2xl font-medium text-gray-700 flex-1 mb-3 sm:mb-0">
                            Laporan Transaksi
                        </h2>
                        <div className="flex items-center">
                            <Datepicker
                                value={dates}
                                onChange={handleDatesChange}
                                maxDate={new Date()}
                                placeholder="Filter Tanggal"
                                i18n="id"
                            />
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
