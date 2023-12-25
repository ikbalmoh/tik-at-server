import { Pagination as Prop } from "@/types";
import { Link } from "@inertiajs/react";

function Pagination({ pagination }: { pagination: Prop }) {
    return (
        <div className="flex justify-between mt-2 items-center">
            <div className="flex-1 text-xs">{pagination.total} data</div>
            <div className="flex my-1">
                {pagination.links.map((link, index) =>
                    link.url ? (
                        <Link
                            key={index}
                            href={link.url}
                            className={
                                "px-3 py-2 font-semibold text-sm border rounded mx-0.5 hover:bg-purple-200 " +
                                (link.active ? "bg-purple-100" : "bg-white")
                            }
                            dangerouslySetInnerHTML={{ __html: link.label }}
                        />
                    ) : (
                        <div
                            key={index}
                            className="px-3 py-2 font-semibold text-sm border rounded mx-0.5 cursor-not-allowed"
                            dangerouslySetInnerHTML={{ __html: link.label }}
                        />
                    )
                )}
            </div>
        </div>
    );
}

export default Pagination;
