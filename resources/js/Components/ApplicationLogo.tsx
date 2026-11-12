import { HTMLAttributes } from "react";
import { usePage } from "@inertiajs/react";
import { PageProps } from "@/types";

export default function ApplicationLogo(props: HTMLAttributes<HTMLElement>) {
    const { uptName } = usePage<PageProps>().props;
    return (
        <div {...props} className="text-left flex flex-col items-start">
            <h4 className="text-xs tracking-widest bg-yellow-500 px-1 py-0.5 text-white rounded-sm -skew-y-2">
                e<span className="font-bold">Tiket</span>
            </h4>
            <h2 className="font-semibold text-base">{uptName}</h2>
        </div>
    );
}
