import { PageProps, TableColumn } from "@/types";
import { Ticket as TicketProp, Ticket as TicketType } from "@/types/app";
import AppLayout from "@/Layouts/AppLayout";
import { Head, router } from "@inertiajs/react";
import Table from "@/Components/Table";
import { currency } from "@/utils/formater";
import { IconEdit, IconPlus, IconTrash, IconX } from "@tabler/icons-react";
import cls from "@/utils/cls";
import PrimaryButton from "@/Components/PrimaryButton";
import { useState } from "react";
import FormTicket from "./FormTicket";
import Modal from "@/Components/Modal";

interface Props extends PageProps {
    tickets: Array<any>;
}

export default function Ticket({ auth, tickets }: Props) {
    const [modalVisible, setModalVisible] = useState<boolean>(false);
    const [deleteTicket, setDeleteTicket] = useState<TicketType>();
    const [editTicket, setEditTicket] = useState<TicketType>();

    const onHideModal = () => {
        setModalVisible(false);
        setEditTicket(undefined);
    };

    const onEditTicket = (ticket: TicketType | undefined) => {
        setEditTicket(ticket);
        setModalVisible(true);
    };

    const onDeleteTicket = () => {
        router.delete(route("ticket.destroy", { id: deleteTicket?.id }), {
            onSuccess: () => {
                setDeleteTicket(undefined);
            },
        });
    };

    const column: TableColumn = [
        { header: "Nama Tiket", value: "name" },
        {
            header: "Harga Tiket",
            value: (t: TicketProp) => currency(t.regular_price),
            className: "text-right",
        },
        {
            header: "Harga Weekend",
            value: (t: TicketProp) => currency(t.holiday_price),
            className: "text-right",
        },
        {
            header: "Status",
            value: (t: TicketProp) => (
                <span
                    className={cls(
                        "text-xs text-white rounded-md px-3 py-1",
                        t.is_active ? "bg-green-600" : "bg-red-600"
                    )}
                >
                    {t.is_active ? "aktif" : "tidak aktif"}
                </span>
            ),
            className: "text-right font-bold",
        },
        {
            header: "",
            value: (t: TicketProp) => (
                <div className="flex gap-3 justify-end">
                    <button
                        type="button"
                        onClick={() => onEditTicket(t)}
                        className="text-gray-600"
                    >
                        <IconEdit />
                    </button>
                    <button
                        type="button"
                        onClick={() => setDeleteTicket(t)}
                        className={
                            t.can_delete ? "text-red-500" : "text-gray-400/50"
                        }
                        disabled={!t.can_delete}
                    >
                        <IconTrash />
                    </button>
                </div>
            ),
            className: "text-right font-bold",
        },
    ];

    return (
        <AppLayout title="Laporan Transaksi" user={auth?.user}>
            <Head title="Laporan Transaksi" />

            <div className="py-10">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col sm:flex-row sm:justify-between sm:items-center w-full mb-5">
                        <h2 className="text-2xl font-medium text-gray-700 flex-1 mb-3 sm:mb-0">
                            Jenis Tiket
                        </h2>
                        <div className="flex items-center">
                            <PrimaryButton
                                onClick={() => onEditTicket(undefined)}
                            >
                                <IconPlus size={"1.2rem"} className="mr-2" />
                                Tambah Jenis
                            </PrimaryButton>
                        </div>
                    </div>

                    <div className="mt-5">
                        <Table column={column} data={tickets} />
                    </div>
                </div>
            </div>

            <FormTicket
                visible={modalVisible}
                onClose={onHideModal}
                ticket={editTicket}
            />

            <Modal
                show={deleteTicket != undefined}
                title={`Hapus Tiket ${
                    deleteTicket ? deleteTicket?.name : ""
                } ?`}
                maxWidth="sm"
                onClose={() => setDeleteTicket(undefined)}
            >
                <div>
                    <div className="text-gray-70 dark:text-gray-200">
                        Jenis tiket tidak dapat dikembalikan setelah dihapus
                    </div>
                    <div className="mt-5 flex justify-end gap-3">
                        <PrimaryButton
                            className="bg-red-100 dark:bg-red-100 text-red-500 dark:text-red-500"
                            type="button"
                            onClick={onDeleteTicket}
                        >
                            Ya
                        </PrimaryButton>
                        <PrimaryButton
                            className="bg-white text-gray-500"
                            onClick={() => setDeleteTicket(undefined)}
                        >
                            Tidak
                        </PrimaryButton>
                    </div>
                </div>
            </Modal>
        </AppLayout>
    );
}
