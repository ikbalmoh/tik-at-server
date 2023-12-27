import { PageProps, TableColumn } from "@/types";
import { User } from "@/types";
import AppLayout from "@/Layouts/AppLayout";
import { Head, router } from "@inertiajs/react";
import Table from "@/Components/Table";
import { IconEdit, IconPlus, IconTrash } from "@tabler/icons-react";
import PrimaryButton from "@/Components/PrimaryButton";
import { useState } from "react";
import FormTicket from "./FormTicket";
import Modal from "@/Components/Modal";

interface Props extends PageProps {
    users: Array<User>;
}

export default function Users({ auth, users }: Props) {
    const [modalVisible, setModalVisible] = useState<boolean>(false);
    const [deleteUser, setDeleteUser] = useState<User>();
    const [editUser, setEditUser] = useState<User>();

    const onHideModal = () => {
        setModalVisible(false);
        setEditUser(undefined);
    };

    const onEditUser = (user: User | undefined) => {
        setEditUser(user);
        setModalVisible(true);
    };

    const onDeleteUser = () => {
        router.delete(route("user.destroy", { id: deleteUser?.id }), {
            onSuccess: () => {
                setDeleteUser(undefined);
            },
        });
    };

    const column: TableColumn = [
        { header: "Nama Operator", value: "name" },
        { header: "Username", value: "username" },
        // {
        //     header: "",
        //     value: (t: User) => (
        //         <div className="flex gap-3 justify-end">
        //             <button
        //                 type="button"
        //                 onClick={() => onEditUser(t)}
        //                 className="text-gray-600"
        //             >
        //                 <IconEdit />
        //             </button>
        //             <button
        //                 type="button"
        //                 onClick={() => setDeleteUser(t)}
        //                 className="text-red-500"
        //             >
        //                 <IconTrash />
        //             </button>
        //         </div>
        //     ),
        //     className: "text-right font-bold",
        // },
    ];

    return (
        <AppLayout title="Laporan Transaksi" user={auth?.user}>
            <Head title="Laporan Transaksi" />

            <div className="py-10">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col sm:flex-row sm:justify-between sm:items-center w-full mb-5">
                        <h2 className="text-2xl font-medium text-gray-700 flex-1 mb-3 sm:mb-0">
                            Operator Loket
                        </h2>
                        <div className="flex items-center hidden">
                            <PrimaryButton
                                onClick={() => onEditUser(undefined)}
                            >
                                <IconPlus size={"1.2rem"} className="mr-2" />
                                Operator Baru
                            </PrimaryButton>
                        </div>
                    </div>

                    <div className="mt-5">
                        <Table column={column} data={users} />
                    </div>
                </div>
            </div>

            <FormTicket visible={modalVisible} onClose={onHideModal} />

            <Modal
                show={deleteUser != undefined}
                title={`Hapus Tiket ${deleteUser ? deleteUser?.name : ""} ?`}
                maxWidth="sm"
                onClose={() => setDeleteUser(undefined)}
            >
                <div>
                    <div className="text-gray-70 dark:text-gray-200">
                        Jenis tiket tidak dapat dikembalikan setelah dihapus
                    </div>
                    <div className="mt-5 flex justify-end gap-3">
                        <PrimaryButton
                            className="bg-red-100 dark:bg-red-100 text-red-500 dark:text-red-500"
                            type="button"
                            onClick={onDeleteUser}
                        >
                            Ya
                        </PrimaryButton>
                        <PrimaryButton
                            className="bg-white text-gray-500"
                            onClick={() => setDeleteUser(undefined)}
                        >
                            Tidak
                        </PrimaryButton>
                    </div>
                </div>
            </Modal>
        </AppLayout>
    );
}
