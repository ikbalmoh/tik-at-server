import { Sales, Ticket } from "@/types/app";
import cls from "@/utils/cls";
import { useMemo, useState } from "react";
import { IconUser, IconRefresh, IconUsers } from "@tabler/icons-react";
import CountUp from "react-countup";
import { router } from "@inertiajs/react";
import Datepicker, { DateValueType } from "react-tailwindcss-datepicker";
import moment from "moment";
import "moment/locale/id";

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
    const [dates, setDates] = useState<DateValueType>({
        startDate: new Date(),
        endDate: new Date(),
    });

    const onChangeDate = (value: DateValueType) => {
        setDates(value);
        router.reload({
            only: ["sales", "ticketTypes"],
            data: { date: value?.startDate },
        });
    };

    const filters: { [key: string]: string } = useMemo(() => {
        const date = moment(dates?.startDate).locale("id");
        return {
            day: "1 Hari",
            week:
                date.clone().subtract(6, "days").format("D MMM") +
                " - " +
                date.format("D MMM"),
            month: date.format("MMMM YYYY"),
            year: date.format("YYYY"),
        };
    }, [dates]);

    return (
        <div>
            <div className="flex flex-col md:flex-row md:justify-between md:items-center w-full mb-3">
                <div className="flex items-center gap-5">
                    <h2 className="text-base md:text-lg font-medium text-slate-500 flex-1 mb-2 md:mb-0 whitespace-nowrap">
                        Jumlah Pengunjung
                    </h2>
                    <Datepicker
                        value={dates}
                        onChange={onChangeDate}
                        asSingle
                        useRange={false}
                        startWeekOn="mon"
                        displayFormat={"D MMM YYYY"}
                        i18n="id"
                    />
                </div>
                <div className="flex items-center gap-0">
                    {Object.keys(filters).map((key) => (
                        <button
                            onClick={() => setFilter(key)}
                            type="button"
                            key={key}
                            className={cls(
                                "text-sm whitespace-nowrap py-2 px-1 mx-1 border-b-2",
                                filter === key
                                    ? "text-blue-700 border-blue-600"
                                    : "text-gray-700 font-medium border-none",
                            )}
                        >
                            {filters[key]}
                        </button>
                    ))}
                </div>
            </div>
            <div className="grid grid-cols-12 gap-6">
                <div className="col-span-12 md:col-span-6 xl:col-span-3">
                    <div className="bg-white border border-salte-200 px-5 py-7 rounded-lg text-green-600 flex items-center">
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
                            <h4>Total Pengunjung</h4>
                        </div>
                    </div>
                </div>
                {ticketTypes.map((type, idx) => (
                    <div
                        key={type.id}
                        className="col-span-12 md:col-span-6 xl:col-span-3"
                    >
                        <div
                            className={`bg-white border px-5 py-7 rounded-lg flex items-center border-slate-200 text-gray-500`}
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
        </div>
    );
}
