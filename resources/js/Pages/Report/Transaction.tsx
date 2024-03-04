import { useState } from "react";
import { PageProps, TableColumn, Pagination } from "@/types";
import { Summary, Transaction as TransactionProp } from "@/types/app";
import AppLayout from "@/Layouts/AppLayout";
import { Head, router, usePage } from "@inertiajs/react";
import Table from "@/Components/Table";
import { currency } from "@/utils/formater";
import Datepicker, { DateValueType } from "react-tailwindcss-datepicker";
import TransactionSummary from "@/Components/TransactionSummary";

interface TransactionData extends Pagination {
    data: Array<any>;
}

interface Props extends PageProps {
    transactions: TransactionData;
    dates: {
        from: string | null;
        to: string | null;
    };
    summary: Summary;
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

export default function Transaction({
    auth,
    transactions,
    dates: range,
    summary,
}: Props) {
    const { data, ...pagination } = transactions;

    const { from, to } = range;

    const [dates, setDates] = useState<DateValueType>({
        startDate: from,
        endDate: to,
    });

    const handleDatesChange = (newDates: DateValueType) => {
        setDates(newDates);
        router.reload({
            only: ["transactions", "summary"],
            data: { page: 1, from: newDates?.startDate, to: newDates?.endDate },
        });
    };

    return (
        <AppLayout title="Laporan Transaksi" user={auth?.user}>
            <Head title="Laporan Transaksi" />

            <div className="py-10">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" px-4>
                    <div className="flex flex-col sm:flex-row sm:justify-between sm:items-center w-full mb-5">
                        <h2 className="text-lg md:text-2xl font-medium text-gray-700 flex-1 mb-3 sm:mb-0">
                            Laporan Transaksi
                        </h2>
                        <div className="flex items-center">
                            <Datepicker
                                value={dates}
                                onChange={handleDatesChange}
                                placeholder="Filter Tanggal"
                                i18n="id"
                                readOnly
                                showShortcuts
                                displayFormat="DD MMM"
                                startWeekOn="mon"
                                configs={{
                                    shortcuts: {
                                        today: "Hari Ini",
                                        yesterday: "Kemarin",
                                        past: period => `${period}  hari terakhir`,
                                        currentMonth: "Bulan Ini",
                                        pastMonth: "Bulan Kemarin" 
                                    },
                                }}
                            />
                        </div>
                    </div>

                    <div className="mt-5">
                        <TransactionSummary summary={summary} />
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
