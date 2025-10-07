import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import Modal from "@/Components/Modal";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import { Ticket } from "@/types/app";
import cls from "@/utils/cls";
import { useForm, router } from "@inertiajs/react";
import { FormEventHandler, useEffect } from "react";
import { Switch } from "@headlessui/react";

export default function FormTicket({
    visible,
    onClose,
    ticket,
}: {
    visible: boolean;
    onClose: CallableFunction;
    ticket?: Ticket;
}) {
    const { data, setData, post, put, processing, errors, reset, clearErrors } =
        useForm<Ticket>(ticket);

    useEffect(() => {
        if (ticket) {
            setData(ticket);
        } else {
            reset();
            clearErrors();
            setData("is_active", true);
        }
    }, [visible]);

    const onSubmited = () => {
        onClose();
        router.reload({ only: ["tickets"] });
    };

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        if (ticket) {
            put(route("ticket.update", { id: ticket.id }), {
                onSuccess: () => onSubmited(),
            });
        } else {
            post(route("ticket.store"), {
                onSuccess: () => onSubmited(),
            });
        }
    };
    return (
        <Modal
            title={ticket ? "Edit Jenis Tiket" : "Tambah Jenis Tiket Baru"}
            show={visible}
            maxWidth="md"
            onClose={() => onClose()}
        >
            <form onSubmit={submit}>
                <div className="flex flex-col gap-4">
                    <div className="flex items-center gap-6">
                        <InputLabel
                            htmlFor="name"
                            value="Nama Tiket"
                            className="w-2/5"
                        />

                        <div className="flex-1">
                            <TextInput
                                id="name"
                                type="text"
                                name="name"
                                value={data.name}
                                className={cls(
                                    "mt-1 block w-full",
                                    data.id <= 3
                                        ? "bg-gray-100 text-gray-500"
                                        : ""
                                )}
                                isFocused={!data.id}
                                placeholder="Nama Tiket"
                                onChange={(e) =>
                                    setData("name", e.target.value)
                                }
                            />

                            <InputError
                                message={errors.name}
                                className="mt-2"
                            />
                        </div>
                    </div>
                    <div className="flex items-center gap-6">
                        <InputLabel
                            htmlFor="regular_price"
                            value="Harga Tiket Reguler"
                            className="w-2/5"
                        />

                        <div className="flex-1">
                            <TextInput
                                id="regular_price"
                                type="number"
                                name="regular_price"
                                value={data.regular_price}
                                className="mt-1 block w-full"
                                placeholder="Harga Tiket"
                                isFocused={data.id != null}
                                onChange={(e) =>
                                    setData(
                                        "regular_price",
                                        parseFloat(e.target.value)
                                    )
                                }
                            />

                            <InputError
                                message={errors.regular_price}
                                className="mt-2"
                            />
                        </div>
                    </div>
                    <div className="flex items-center gap-6">
                        <InputLabel
                            htmlFor="holiday_price"
                            value="Harga Hari Libur"
                            className="w-2/5"
                        />

                        <div className="flex-1">
                            <TextInput
                                id="holiday_price"
                                type="number"
                                name="holiday_price"
                                value={data.holiday_price}
                                className="mt-1 block w-full"
                                placeholder="Harga Hari Libur"
                                onChange={(e) =>
                                    setData(
                                        "holiday_price",
                                        parseFloat(e.target.value)
                                    )
                                }
                            />

                            <InputError
                                message={errors.holiday_price}
                                className="mt-2"
                            />
                        </div>
                    </div>
                    <div className="flex items-center gap-6">
                        <InputLabel
                            htmlFor="is_active"
                            value="Tiket Aktif"
                            className="w-2/5"
                        />

                        <div className="flex-1">
                            <Switch
                                name="is_active"
                                checked={data?.is_active}
                                onChange={(active) =>
                                    setData("is_active", active)
                                }
                                className={`${
                                    data?.is_active
                                        ? "bg-blue-600"
                                        : "bg-gray-200"
                                } relative inline-flex h-6 w-11 items-center rounded-full`}
                            >
                                <span className="sr-only">Tiket Aktif</span>
                                <span
                                    className={`${
                                        data?.is_active
                                            ? "translate-x-6"
                                            : "translate-x-1"
                                    } inline-block h-4 w-4 transform rounded-full bg-white transition`}
                                />
                            </Switch>
                        </div>
                    </div>
                </div>
                <div className="flex items-center justify-end mt-8">
                    <PrimaryButton className="ms-4" disabled={processing}>
                        {ticket?.id ? "Simpan Perubahan" : "Simpan"}
                    </PrimaryButton>
                </div>
            </form>
        </Modal>
    );
}
