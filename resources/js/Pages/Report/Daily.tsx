import { useState } from "react";
import { PageProps, TableColumn } from "@/types";
import { TicketType, TransactionDetail } from "@/types/app";
import AppLayout from "@/Layouts/AppLayout";
import { Head, router } from "@inertiajs/react";
import Table from "@/Components/Table";
import { currency, number } from "@/utils/formater";
import moment from "moment";
import { IconDownload } from "@tabler/icons-react";

interface Props extends PageProps {
    transactions: Array<any>;
    ticket_types: Array<TicketType>;
    years: Array<string>;
    months: { [key: string | number]: string | number };
}

export default function Daily({
    auth,
    transactions,
    ticket_types,
    years,
    months,
}: Props) {
    const [month, setMonth] = useState<string | number>(moment().format("M"));
    const [year, setYear] = useState<string | number>(moment().format("YYYY"));

    const [column] = useState<TableColumn>([
        { header: "Tanggal", value: "day", className: "text-center font-bold" },
        ...ticket_types.map((type) => ({
            header: type.name,
            value: (t: any) => (t[type.id] ? number(t[type.id] ?? "0") : "-"),
            className: "text-center",
        })),
        {
            header: "Total",
            value: (t: TransactionDetail) => currency(t.total),
            className: "text-right font-medium",
        },
    ]);

    const handleMonthChange = (value: string | number) => {
        setMonth(value);
        router.reload({
            only: ["transactions", "summary"],
            data: { month: value, year },
        });
    };

    const handleYearChange = (value: string | number) => {
        setYear(value);
        router.reload({
            only: ["transactions", "summary"],
            data: { month, year: value },
        });
    };

    return (
        <AppLayout title="Rekap Bulanan" user={auth?.user}>
            <Head title="Rekap Bulanan" />

            <div className="py-10">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4 flex flex-col gap-5">
                    <div className="flex flex-wrap md:items-center w-full -mx-1.5">
                        <div className="md:flex-1 mb-3 md:mb-0 p-1.5">
                            <h2 className="text-xl xl:text-2xl font-semibold text-gray-700 flex-1">
                                Rekap Bulanan
                            </h2>
                        </div>
                        <div className="w-6/12 md:w-2/12 px-1.5">
                            <select
                                value={month}
                                onChange={(e) =>
                                    handleMonthChange(e.target.value)
                                }
                                className="w-full rounded-md bg-white border-slate-200 shadow"
                                placeholder="Bulan"
                            >
                                op
                                {Object.keys(months).map((key) => (
                                    <option key={key} value={key}>
                                        {months[key]}
                                    </option>
                                ))}
                            </select>
                        </div>
                        <div className="w-6/12 md:w-2/12 px-1.5">
                            <select
                                value={year}
                                onChange={(e) =>
                                    handleYearChange(e.target.value)
                                }
                                className="w-full rounded-md bg-white border-slate-200 shadow"
                                placeholder="Tahun"
                            >
                                {years.map((y) => (
                                    <option key={y} value={y}>
                                        {y}
                                    </option>
                                ))}
                            </select>
                        </div>
                        <div className="w-full md:w-min px-1.5 mt-3 md:mt-0 flex">
                            <a
                                href={route("report.download", { year, month })}
                                target="_blank"
                                className="text-sm w-full bg-blue-600 text-white text-center py-2.5 px-5 rounded-md flex items-center justify-center"
                            >
                                <IconDownload
                                    className="mr-2"
                                    size={"1.2rem"}
                                />{" "}
                                Download
                            </a>
                        </div>
                    </div>

                    <Table
                        column={column}
                        data={transactions}
                        onClickRow={undefined}
                        numbering={false}
                    />
                </div>
            </div>
        </AppLayout>
    );
}
