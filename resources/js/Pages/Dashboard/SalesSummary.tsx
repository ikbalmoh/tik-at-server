import { Sales, Ticket } from "@/types/app";
import cls from "@/utils/cls";
import { useState } from "react";
import {
    IconUserHeart,
    IconUserDollar,
    IconUser,
    IconRefresh,
    IconUsers,
} from "@tabler/icons-react";
import CountUp from "react-countup";
import { router } from "@inertiajs/react";

const filters: { [key: string]: string } = {
    day: "Hari Ini",
    week: "7 Hari",
    month: "1 Bulan",
    year: "Tahun Ini",
};

interface SalesSummaryProps {
    sales: Sales;
    ticketTypes: Ticket[];
    colors: string[];
}

export default function SalesSummary({
    sales,
    ticketTypes,
    colors,
}: SalesSummaryProps) {
    const [filter, setFilter] = useState<string>("day");

    const reload = () => router.reload({ only: ["sales", "ticketTypes"] });

    return (
        <>
            <div className="flex flex-col md:flex-row md:justify-between md:items-center w-full mb-5">
                <h2 className="text-lg md:text-2xl font-medium text-gray-700 flex-1  mb-2 md:mb-0">
                    Jumlah Pengunjung
                </h2>
                <div className="flex items-center">
                    {Object.keys(filters).map((key) => (
                        <button
                            onClick={() => setFilter(key)}
                            type="button"
                            key={key}
                            className={cls(
                                "text-sm py-2 px-1 mx-1 border-b-2",
                                filter === key
                                    ? "text-blue-700 border-blue-600"
                                    : "text-gray-700 font-medium border-none"
                            )}
                        >
                            {filters[key]}
                        </button>
                    ))}
                    <button
                        onClick={reload}
                        type="button"
                        className="ml-auto md:ml-3"
                    >
                        <IconRefresh className="h-4" />
                    </button>
                </div>
            </div>
            <div className="grid grid-cols-12 gap-6">
                <div className="col-span-12 md:col-span-6 xl:col-span-3">
                    <div className="bg-green-100 border border-green-500 px-5 py-7 rounded-lg text-green-700 flex items-center">
                        <div className="px-5">
                            <IconUsers size="2.5em" />
                        </div>
                        <div className="flex-1 ml-5">
                            <CountUp
                                start={0}
                                end={sales[filter]?.["all"] ?? 0}
                                decimals={0}
                                className="text-2xl font-bold"
                            />
                            <h4>Tiket Terjual</h4>
                        </div>
                    </div>
                </div>
                {ticketTypes.map((type, idx) => (
                    <div
                        key={type.id}
                        className="col-span-12 md:col-span-6 xl:col-span-3"
                    >
                        <div
                            className={`bg-white border px-5 py-7 rounded-lg flex items-center border-gray-300 text-gray-500`}
                        >
                            <div className="px-5">
                                <IconUser size="2.5em" />
                            </div>
                            <div className="flex-1 ml-5">
                                <div>
                                    <CountUp
                                        start={0}
                                        end={sales[filter]?.[type.id] ?? 0}
                                        decimals={0}
                                        className="text-2xl font-bold"
                                    />
                                </div>
                                <h4>{type.name}</h4>
                            </div>
                        </div>
                    </div>
                ))}
            </div>
        </>
    );
}
