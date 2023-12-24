import AppLayout from "@/Layouts/AppLayout";
import { Head } from "@inertiajs/react";
import { PageProps } from "@/types";
import { sales } from "@/types/app";
import SalesSummary from "./SalesSummary";

interface Props extends PageProps {
    sales: sales;
}

export default function Dashboard({ auth, sales }: Props) {
    return (
        <AppLayout user={auth.user} title="Dashboard">
            <Head title="Dashboard" />

            <div className="py-10">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <SalesSummary sales={sales} />
                </div>
            </div>
        </AppLayout>
    );
}
