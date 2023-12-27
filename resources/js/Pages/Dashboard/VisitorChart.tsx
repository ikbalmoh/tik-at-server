import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    BarElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    ChartData,
    PointElement,
} from "chart.js";
import { Bar } from "react-chartjs-2";
import { IconRefresh } from "@tabler/icons-react";
import { router } from "@inertiajs/react";
import { useState } from "react";
import cls from "@/utils/cls";

ChartJS.register(
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement
);

const filters: { [key: string]: string } = {
    daily: "Harian",
    monthly: "Bulanan",
};

export default function VisitorChart({
    data,
}: {
    data: { [key: string]: ChartData<"bar", number, string> };
}) {
    const [filter, setFilter] = useState<string>("daily");

    const reload = () => router.reload({ only: ["chart"] });

    return (
        <>
            <div className="flex flex-col md:flex-row md:justify-between md:items-center w-full mb-8">
                <h2 className="text-2xl font-medium text-gray-700 flex-1 mb-2 md:mb-0">
                    Statistik Pengunjung
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
            <div className=" bg-white px-5 py-10 rounded-lg overflow-auto">
                <div className="w-[200%] md:w-full">
                    <Bar
                        data={data[filter]}
                        options={{
                            scales: {
                                y: {
                                    title: {
                                        display: true,
                                        text: "jumlah pengunjung",
                                    },
                                    beginAtZero: true,
                                },
                                x: {
                                    grid: {
                                        display: false,
                                    },
                                },
                            },
                            plugins: {
                                legend: {
                                    position: "bottom",
                                },
                            },
                        }}
                    />
                </div>
            </div>
        </>
    );
}
