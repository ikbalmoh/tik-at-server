import { PageProps } from "@/types";
import { usePage } from "@inertiajs/react";
import { HTMLAttributes, SVGAttributes } from "react";

export default function ApplicationLogo(props: HTMLAttributes<HTMLElement>) {
    const { appName } = usePage<PageProps>().props;

    return <div {...props}>{appName ?? "GarTix"}</div>;
}
