import React from "react";
import { PageProps } from "@/types";
import AppLayout from "@/Layouts/AppLayout";
import { Head } from "@inertiajs/react";

interface Props extends PageProps {
    transaction: any;
}

export default function Daily({ auth, transaction }: Props) {
    return (
        <AppLayout title="Laporan Transaksi" user={auth?.user}>
            <Head title="Laporan Transaksi" />
        </AppLayout>
    );
}
