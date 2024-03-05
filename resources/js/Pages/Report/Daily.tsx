import { useState } from "react";
import { PageProps, TableColumn, Pagination } from "@/types";
import { Summary, TicketType, TransactionDetail } from "@/types/app";
import AppLayout from "@/Layouts/AppLayout";
import { Head, router } from "@inertiajs/react";
import Table from "@/Components/Table";
import { currency, number } from "@/utils/formater";
import Datepicker, { DateValueType } from "react-tailwindcss-datepicker";
import TransactionSummary from "@/Components/TransactionSummary";

interface Props extends PageProps {
    transactions: Array<any>;
    dates: {
        from: string | null;
        to: string | null;
    };
    summary: Summary;
    ticket_types: Array<TicketType>
}

export default function Daily({
    auth,
    transactions,
    summary,
    dates: range,
    ticket_types
}: Props) {

    const { from, to } = range;

    const [dates, setDates] = useState<DateValueType>({
        startDate: from,
        endDate: to,
    });


const [column] = useState<TableColumn>([
    { header: "Tanggal", value: "date" },
    ...ticket_types.map(type => ({
        header: type.name,
        value: (t: any) => number(t[type.id] ?? '0'),
        className: "text-center",
    })),
    {
        header: "Total",
        value: (t: TransactionDetail) => currency(t.total),
        className: "text-right font-medium",
    },
]);

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
                                placeholder="Filter Tanggal"
                                i18n="id"
                                readOnly={true}
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
                            data={transactions}
                            onClickRow={undefined}
                        />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
