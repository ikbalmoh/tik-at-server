import AppLayout from "@/Layouts/AppLayout";
import { Head } from "@inertiajs/react";
import { PageProps } from "@/types";
import { Sales, Ticket } from "@/types/app";
import SalesSummary from "./SalesSummary";
import VisitorChart from "./VisitorChart";
import { ChartData } from "chart.js";

interface Props extends PageProps {
    sales: Sales;
    chart: { [key: string]: ChartData<"bar", number, string> };
    ticketTypes: Ticket[];
    colors: string[];
}

export default function Dashboard({
    auth,
    sales,
    chart,
    ticketTypes,
    colors,
}: Props) {
    return (
        <AppLayout user={auth.user} title="Dashboard">
            <Head title="Dashboard" />

            <div className="py-10">
                <div className="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 flex flex-col gap-5">
                    <SalesSummary
                        colors={colors}
                        ticketTypes={ticketTypes}
                        sales={sales}
                    />
                    <VisitorChart data={chart} />
                </div>
            </div>
        </AppLayout>
    );
}
