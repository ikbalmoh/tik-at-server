import { useState } from "react";
import { PageProps, TableColumn, Pagination } from "@/types";
import { Summary, Transaction as TransactionProp } from "@/types/app";
import AppLayout from "@/Layouts/AppLayout";
import { Head, router } from "@inertiajs/react";
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
    { header: "Tanggal", value: "date" },
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
        className: "text-right font-medium",
    },
];

export default function Daily({
    auth,
    transactions,
    summary,
    dates: range,
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
        <AppLayout title="Rekap Harian" user={auth?.user}>
            <Head title="Rekap Harian" />

            <div className="py-10">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
                    <div className="flex flex-col sm:flex-row sm:justify-between sm:items-center w-full mb-5">
                        <h2 className="text-lg md:text-2xl font-medium text-gray-700 flex-1 mb-3 sm:mb-0">
                            Rekap Harian
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
